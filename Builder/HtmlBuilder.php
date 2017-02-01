<?php

namespace M6Web\Bundle\DraftjsBundle\Builder;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use M6Web\Bundle\DraftjsBundle\Exception\DraftjsException;
use M6Web\Bundle\DraftjsBundle\Model\ContentState;
use M6Web\Bundle\DraftjsBundle\Model\ContentBlock;
use M6Web\Bundle\DraftjsBundle\Model\DraftEntity;

/**
 * Class HtmlBuilder
 *
 * @package M6Web\Bundle\DraftjsBundle\Builder
 */
class HtmlBuilder implements BuilderInterface
{
    const TEXT_NODE = 'span';
    const ENTITY_NODE = 'a';
    const UNORDERED_LIST_NODE = 'ul';
    const ORDERED_LIST_NODE = 'ol';
    const LIST_CHILD_NODE = 'li';

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var array
     */
    private $customClassNames = [];

    /**
     * @var array
     */
    private $customBlocks = [];

    /**
     * StringBuilder constructor.
     *
     * @param EngineInterface $templating
     * @param array           $classNames
     * @param array           $blocks
     */
    public function __construct(EngineInterface $templating, array $classNames = [], array $blocks = [])
    {
        $this->templating = $templating;
        $this->customClassNames = $classNames;
        $this->customBlocks = $blocks;
    }

    /**
     * @param array $classNames
     */
    public function setCustomClassNames(array $classNames = [])
    {
        $this->customClassNames = $classNames;
    }

    /**
     * @param array $blocks
     */
    public function setCustomBlocks(array $blocks = [])
    {
        $this->customBlocks = $blocks;
    }

    /**
     * Build HTML from contentState
     *
     * @param ContentState $contentState
     *
     * @return string
     *
     * @throws DraftjsException
     */
    public function build(ContentState $contentState)
    {
        $output = '';

        $contentBlocks = $contentState->getBlockMap();
        $entities = $contentState->getEntityMap();

        while ($contentBlock = current($contentBlocks)) {
            $type = strtolower($contentBlock->getType());

            switch ($type) {
                case ContentBlock::ATOMIC:
                    $output .= $this->renderAtomic();
                    next($contentBlocks); // manual cursor move require
                    break;

                case ContentBlock::UNSTYLED:
                case ContentBlock::HEADER_ONE:
                case ContentBlock::HEADER_TWO:
                case ContentBlock::HEADER_THREE:
                    $innerHTML = $this->renderText($contentBlock->getText(), $contentBlock->getCharacterList(), $entities);
                    next($contentBlocks); // manual cursor move require
                    break;

                case ContentBlock::UNORDERED_LIST:
                case ContentBlock::ORDERED_LIST:
                    $listItems = $this->extractContinousListItemsFromIndex($contentBlocks, key($contentBlocks));
                    $listTree = $this->buildListTree($listItems);
                    $innerHTML = $this->renderList($listTree, $listItems, $entities);
                    break;
                default:
                    throw new DraftjsException(sprintf('HtmlBuilder unknow block type "%s"', $type));
            }

            if (isset($this->customBlocks[$type])) {
                $template = $this->customBlocks[$type];
            } else {
                $template = sprintf('M6WebDraftjsBundle:Block:%s.html.twig', $type);
            }

            if (!$this->templating->exists($template)) {
                $template = 'M6WebDraftjsBundle:Block:unstyled.html.twig';
            }

            $output .= $this->templating->render($template, [
                'innerHTML' => $innerHTML,
            ]);
        }

        return $output;
    }

    /**
     * Find block index by key in contentBlocks
     *
     * @param $contentBlocks
     * @param $key
     *
     * @return mixed|null
     */
    private function findKeyIndex($contentBlocks, $key)
    {
        foreach ($contentBlocks as $index => $contentBlock) {
            if ($key === $contentBlock->getKey()) {
                return $index;
            }
        }

        return null;
    }

    /**
     * Extract continous contentBlock of same type from index
     *
     * @param array $contentBlocks
     * @param int $index
     *
     * @return array
     */
    private function extractContinousListItemsFromIndex(array &$contentBlocks, $index)
    {
        $type = $contentBlocks[$index]->getType();

        $listItems = [];

        for ($nextIndex = $index; $nextIndex < count($contentBlocks); $nextIndex++) {
            $contentBlock = $contentBlocks[$nextIndex];

            if ($contentBlock->getType() !== $type) {
                break;
            }

            $listItems[] = $contentBlock;

            // move pointer to next item for while global iterator
            next($contentBlocks);
        }

        return $listItems;
    }

    /**
     * Build list tree
     *
     * @param array $contentBlocks
     *
     * @return array
     */
    private function buildListTree(array $contentBlocks)
    {
        $buildListTree = function ($key, array &$listTree) use (&$buildListTree, $contentBlocks) {
            $findChilds = function ($index, $depth, $type) use ($contentBlocks) {
                $childs = [];

                for ($nextIndex = ($index + 1); $nextIndex < count($contentBlocks); $nextIndex++) {
                    $currentBlock = $contentBlocks[$nextIndex];

                    if ($currentBlock->getType() !== $type || $currentBlock->getDepth() <= $depth) {
                        break;
                    }

                    if ($currentBlock->getDepth() === ($depth + 1)) {
                        $childs[$currentBlock->getKey()] = [];
                    }
                }

                return $childs;
            };

            $index = $this->findKeyIndex($contentBlocks, $key);

            if (!is_null($index)) {
                $contentBlock = $contentBlocks[$index];
                $childs = $findChilds($index, $contentBlock->getDepth(), $contentBlock->getType());
                $listTree[$key] = $childs;

                foreach ($childs as $childKey => $childs) {
                    $buildListTree($childKey, $listTree[$key]);
                }
            }

            return $listTree;
        };

        $listTree = [];

        foreach ($contentBlocks as $index => $contentBlock) {
            if (0 === $contentBlock->getDepth()) {
                $buildListTree($contentBlock->getKey(), $listTree);
            }
        }

        return $listTree;
    }

    /**
     * Retrieve classNames
     *
     * @param array $styles
     *
     * @return array
     */
    private function getClassNames(array $styles = [])
    {
        return array_map(function ($style) {
            $style = strtolower(str_replace('-', '_', $style));

            // custom classNames from config
            if (isset($this->customClassNames[$style])) {
                return $this->customClassNames[$style];
            }

            // default classNames
            return sprintf('u-%s', $style);
        }, $styles);
    }

    /**
     * Convert an array of attributes in string like http_build_query
     *
     * @param array $attributes
     *
     * @return string
     */
    private function buildAttributes(array $attributes = [])
    {
        $strAttributes = array_map(function ($key) use ($attributes) {
            return sprintf('%s="%s"', $key, $attributes[$key]);
        }, array_keys(array_filter($attributes)));

        if (!$strAttributes) {
            return '';
        }

        return sprintf(' %s', implode(' ', $strAttributes));
    }

    /**
     * @param $tagName
     * @param array $attributes
     *
     * @return string
     */
    private function openNode($tagName, array $attributes = [])
    {
        $strAttributes = $this->buildAttributes($attributes);

        return sprintf('<%s%s>', $tagName, $strAttributes);
    }

    /**
     * @param $tagName
     *
     * @return string
     */
    private function closeNode($tagName)
    {
        return sprintf('</%s>', $tagName);
    }

    /**
     * @param array $styles
     *
     * @return string
     */
    private function openTextNode(array $styles = [])
    {
        $classNames = $this->getClassNames($styles);

        return $this->openNode(self::TEXT_NODE, [
            'class' => implode(' ', $classNames),
        ]);
    }

    /**
     * @return string
     */
    private function closeTextNode()
    {
        return $this->closeNode(self::TEXT_NODE);
    }

    /**
     * @param DraftEntity $entity
     *
     * @return string
     *
     * @throws DraftjsException
     */
    private function openEntityNode(DraftEntity $entity)
    {
        $type = $entity->getType();

        switch ($type) {
            case DraftEntity::LINK:
                $data = $entity->getData();

                $attributes = [];

                if (isset($data['url'])) {
                    $attributes['href'] = $data['url'];
                }

                if (isset($data['target']) && '_self' !== $data['target']) {
                    $attributes['target'] = $data['target'];
                }

                if (isset($data['nofollow']) && true === $data['nofollow']) {
                    $attributes['rel'] = 'nofollow';
                }

                return $this->openNode(self::ENTITY_NODE, $attributes);
            default:
                throw new DraftjsException(sprintf('Unsupported entity type %s', $type));
        }
    }

    /**
     * @return string
     */
    private function closeEntityNode()
    {
        return $this->closeNode(self::ENTITY_NODE);
    }

    /**
     * @param $type
     *
     * @return string
     */
    private function openListNode($type)
    {
        $classNames = $this->getClassNames([$type]);

        $node = self::UNORDERED_LIST_NODE;
        if (ContentBlock::ORDERED_LIST === $type) {
            $node = self::ORDERED_LIST_NODE;
        }

        return $this->openNode($node, [
            'class' => implode(' ', $classNames),
        ]);
    }

    /**
     * @return string
     */
    private function closeListNode($type)
    {
        $node = self::UNORDERED_LIST_NODE;
        if (ContentBlock::ORDERED_LIST === $type) {
            $node = self::ORDERED_LIST_NODE;
        }

        return $this->closeNode($node);
    }

    /**
     * @return string
     */
    private function openListChildNode()
    {
        return $this->openNode(self::LIST_CHILD_NODE);
    }

    /**
     * @return string
     */
    private function closeListChildNode()
    {
        return $this->closeNode(self::LIST_CHILD_NODE);
    }

    /**
     * @param array $listTree
     * @param array $listItems
     * @param array $entities
     *
     * @return string
     */
    private function renderList(array $listTree, array $listItems, array $entities)
    {
        if (0 === count($listTree)) {
            return '';
        }

        $type = $listItems[0]->getType();

        $renderNode = function ($items, $type) use (&$renderNode, $listItems, $entities) {
            $ret = sprintf('%s', $this->openListNode($type));
            foreach($items as $key => $item) {
                // create list child node
                $ret .= sprintf('%s', $this->openListChildNode($type));

                // add innerHTML
                $index = $this->findKeyIndex($listItems, $key);
                if (!is_null($index)) {
                    $contentBlock = $listItems[$index];
                    $innerHTML = $this->renderText($contentBlock->getText(), $contentBlock->getCharacterList(), $entities);
                    $ret .= sprintf('%s', $innerHTML);
                }

                // create child node
                if (!empty($item)) {
                    $ret .= sprintf('%s', $renderNode($item, $type));
                }

                // close list child node
                $ret .= sprintf('%s', $this->closeListChildNode());
            }
            return sprintf('%s%s', $ret, $this->closeListNode($type));

        };

        return $renderNode($listTree, $type);
    }

    /**
     * @return string
     */
    private function renderAtomic()
    {
        return 'atomic<br><br>';
    }

    /**
     * @param string $text
     * @param array  $characterList
     * @param array  $entities
     *
     * @return string
     */
    private function renderText($text = '', array $characterList = [], array $entities = [])
    {
        $output = '';
        $stack = [];
        $currentEntity = null;

        $chars = str_split($text);

        foreach ($chars as $index => $char) {
            $characterMetadata = $characterList[$index];

            $styles = $characterMetadata->getStyles();
            $entityKey = $characterMetadata->getEntity();

            $currentDepth = count($stack);

            if ($entityKey !== $currentEntity || count($styles) !== $currentDepth) {
                // close text node
                if ($currentDepth > 0) {
                    $output .= $this->closeTextNode();
                }

                // close link node
                if ($entityKey !== $currentEntity && is_null($entityKey)) {
                    $output .= $this->closeEntityNode();
                }

                // create link node
                if ($entityKey !== $currentEntity && !is_null($entityKey)) {
                    $entity = $entities[$entityKey];
                    $output .= $this->openEntityNode($entity);
                }

                // create text node
                if (count($styles) > 0) {
                    $output .= $this->openTextNode($styles);
                }
            }

            $output .= $char;

            $stack = $styles;
            $currentEntity = $entityKey;
        }

        return $output;
    }
}
