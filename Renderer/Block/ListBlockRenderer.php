<?php

namespace M6Web\Bundle\DraftjsBundle\Renderer\Block;

use M6Web\Bundle\DraftjsBundle\Model\ContentBlock;
use M6Web\Bundle\DraftjsBundle\Renderer\Helper\InlineRendererHelperTrait;

/**
 * Class ListBlockRenderer
 *
 * @package M6Web\Bundle\DraftjsBundle\Renderer\Block
 */
class ListBlockRenderer extends AbstractBlockRenderer
{
    use InlineRendererHelperTrait;

    const NAME = 'list';

    const UNORDERED_LIST = 'unordered-list-item';
    const ORDERED_LIST = 'ordered-list-item';

    const TYPES = [
        self::ORDERED_LIST,
        self::UNORDERED_LIST,
    ];

    const UL = 'ul';
    const OL = 'ol';
    const LI = 'li';

    const TAGS_NAME = [
        self::UNORDERED_LIST => self::UL,
        self::ORDERED_LIST => self::OL,
    ];

    /**
     * @param \ArrayIterator $iterator
     * @param array          $entities
     *
     * @return string
     */
    public function render(\ArrayIterator &$iterator, array $entities)
    {
        $type = $iterator->current()->getType();
        $extractedItems = $this->extractContinousItems($iterator);

        return $this->getContent($extractedItems, $type, $entities);
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supports($type)
    {
        return in_array($type, self::TYPES);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @param ContentBlock[] $extractedItems
     * @param string         $type
     * @param array          $entities
     *
     * @return string
     */
    private function getContent(array $extractedItems, $type, array $entities)
    {
        if (0 === count($extractedItems)) {
            return '';
        }

        $blockClassName = $this->getBlockClassName();

        $output = $this->openListTag($type, $blockClassName);

        $closePreviousDepth = function ($depth, $previousDepth, &$output) use ($type) {
            $diff = $previousDepth - $depth;
            for ($i = 0; $i < $diff; $i++) {
                $output .= $this->closeNode(self::LI);
                $output .= $this->closeListTag($type);
            }
        };

        $previousDepth = null;
        
        foreach ($extractedItems as $index => $contentBlock) {
            $depth = $contentBlock->getDepth();

            $textAlignment = $this->getTextAlignment($contentBlock);
            $childClassNames = $textAlignment ? $this->getTextAlignmentClassName($textAlignment) : '';

            if (0 === $depth) {
                if ($previousDepth) {
                    $closePreviousDepth($depth, $previousDepth, $output);    
                }
                if ($previousDepth !== null) {
                    $output .= $this->closeChildTag();
                }
                $output .= $this->openChildTag($childClassNames);
            } elseif ($depth > $previousDepth) {
                $output .= $this->openListTag($type, $blockClassName);
                $output .= $this->openChildTag($childClassNames);
            } elseif ($depth === $previousDepth) {
                $output .= $this->closeChildTag();
                $output .= $this->openChildTag($childClassNames);
            } elseif ($depth < $previousDepth) {
                $closePreviousDepth($depth, $previousDepth, $output);
                $output .= $this->closeChildTag();
                $output .= $this->openChildTag($childClassNames);
            }

            $output .= $this->contentRenderer->render($contentBlock->getText(), $contentBlock->getCharacterList(), $entities);
            $previousDepth = $depth;
        }
        $output .= $this->closeChildTag();
        for ( $i = 0; $i <= $previousDepth; $i++) {
            $output .= $this->closeListTag($type);
        }
        
        return $output;
    }

    /**
     * @param string $type
     * @param string $classNames
     *
     * @return string
     */
    private function openListTag($type, $classNames = '')
    {
        $tag = $this::TAGS_NAME[$this::UNORDERED_LIST];
        if ($this::ORDERED_LIST === $type) {
            $tag = $this::TAGS_NAME[$this::ORDERED_LIST];
        }

        return $this->openNode($tag, [
            'class' => $classNames,
        ]);
    }

    /**
     * @param string $type
     *
     * @return string
     */
    private function closeListTag($type)
    {
        $tag = $this::TAGS_NAME[$this::UNORDERED_LIST];
        if ($this::ORDERED_LIST === $type) {
            $tag = $this::TAGS_NAME[$this::ORDERED_LIST];
        }

        return $this->closeNode($tag);
    }

    /**
     * @param string $classNames
     *
     * @return string
     */
    private function openChildTag($classNames = '')
    {
        if (empty($classNames)) {
            return $this->openNode($this::LI);
        }

        return $this->openNode($this::LI, [
            'class' => $classNames,
        ]);
    }

    /**
     * @return string
     */
    private function closeChildTag()
    {
        return $this->closeNode($this::LI);
    }

    /**
     * Extract continous contentBlock of same type from index
     *
     * @param \ArrayIterator $iterator
     *
     * @return array
     */
    private function extractContinousItems(\ArrayIterator &$iterator)
    {
        $index = $iterator->key();
        $type = $iterator->current()->getType();

        $items = [];

        for ($nextIndex = $index; $nextIndex < $iterator->count(); $nextIndex++) {
            $contentBlock = $iterator->offsetGet($nextIndex);

            if ($type !== $contentBlock->getType()) {
                break;
            }

            $items[] = $contentBlock;

            $iterator->next();
        }

        return $items;
    }
}
