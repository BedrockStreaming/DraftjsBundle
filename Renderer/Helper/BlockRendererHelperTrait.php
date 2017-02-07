<?php

namespace M6Web\Bundle\DraftjsBundle\Renderer\Helper;

use M6Web\Bundle\DraftjsBundle\Model\ContentBlock;

/**
 * Trait BlockRendererHelperTrait
 *
 * @package M6Web\Bundle\DraftjsBundle\Renderer\Helper
 */
trait BlockRendererHelperTrait
{
    /**
     * Get text alignment from content block data
     *
     * @param ContentBlock $contentBlock
     *
     * @return null
     */
    protected function getTextAlignment(ContentBlock $contentBlock)
    {
        $data = $contentBlock->getData();

        if (isset($data['textAlignment'])) {
            return $data['textAlignment'];
        }

        return null;
    }

    /**
     * Build string class names from block and text alignment class names
     *
     * @param ContentBlock $contentBlock
     *
     * @return string
     */
    protected function buildClassNames(ContentBlock $contentBlock)
    {
        $textAlignment = $this->getTextAlignment($contentBlock);

        $classNames = [
            $this->getBlockClassName(),
        ];

        if ($textAlignment) {
            $classNames[] = $this->getTextAlignmentClassName($textAlignment);
        }

        return implode(' ', $classNames);
    }
}
