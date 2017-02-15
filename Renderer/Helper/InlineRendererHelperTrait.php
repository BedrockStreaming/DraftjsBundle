<?php

namespace M6Web\Bundle\DraftjsBundle\Renderer\Helper;

/**
 * Trait InlineRendererHelperTrait
 *
 * @package M6Web\Bundle\DraftjsBundle\Renderer\Helper
 */
trait InlineRendererHelperTrait
{
    /**
     * @param $tagName
     * @param array $attributes
     *
     * @return string
     */
    protected function openNode($tagName, array $attributes = [])
    {
        $strAttributes = $this->buildAttributes($attributes);

        return sprintf('<%s%s>', $tagName, $strAttributes);
    }

    /**
     * @param $tagName
     *
     * @return string
     */
    protected function closeNode($tagName)
    {
        return sprintf('</%s>', $tagName);
    }

    /**
     * Convert an array of attributes in string like http_build_query
     *
     * @param array $attributes
     *
     * @return string
     */
    protected function buildAttributes(array $attributes = [])
    {
        $strAttributes = array_map(function ($key) use ($attributes) {
            return sprintf('%s="%s"', $key, $attributes[$key]);
        }, array_keys(array_filter($attributes)));

        if (!$strAttributes) {
            return '';
        }

        return sprintf(' %s', implode(' ', $strAttributes));
    }
}
