<?php

namespace M6Web\Bundle\DraftjsBundle\Builder;

use M6Web\Bundle\DraftjsBundle\Exception\DraftjsException;
use M6Web\Bundle\DraftjsBundle\Model\ContentState;
use M6Web\Bundle\DraftjsBundle\Model\DraftEntity;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * Class HtmlBuilder
 *
 * @package M6Web\Bundle\DraftjsBundle\Builder
 */
class HtmlBuilder implements BuilderInterface
{
    const TEXT_NODE = 'span';
    const ENTITY_NODE = 'a';

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
     * @param ContentState $contentState
     *
     * @return string
     */
    public function build(ContentState $contentState)
    {
        $entities = $contentState->getEntityMap();

        $buildBlock = function ($output, $contentBlock) use ($entities) {
            $blockType = strtolower($contentBlock->getType());
            $innerHTML = $this->renderText($contentBlock->getText(), $contentBlock->getCharacterList(), $entities);

            if (isset($this->customBlocks[$blockType])) {
                $template = $this->customBlocks[$blockType];
            } else {
                $template = sprintf('M6WebDraftjsBundle:Block:%s.html.twig', $blockType);
            }

            if (!$this->templating->exists($template)) {
                $template = 'M6WebDraftjsBundle:Block:unstyled.html.twig';
            }

            $output .= $this->templating->render($template, [
                'innerHTML' => $innerHTML,
            ]);

            return $output;
        };

        return array_reduce($contentState->getBlockMap(), $buildBlock, '');
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
            $style = strtolower($style);

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

                if ($data['url']) {
                    $attributes['href'] = $data['url'];
                }

                if ($data['target'] && '_self' !== $data['target']) {
                    $attributes['target'] = $data['target'];
                }

                if ($data['nofollow'] && true === $data['nofollow']) {
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
