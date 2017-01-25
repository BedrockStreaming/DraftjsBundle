<?php

namespace M6Web\Bundle\DraftjsBundle\Converter;

use M6Web\Bundle\DraftjsBundle\Model\ContentState;

/**
 * Interface ConverterInterface
 *
 * @package M6Web\Bundle\DraftjsBundle\Model
 */
interface ConverterInterface
{
    /**
     * @param array $raw
     *
     * @return ContentState
     */
    public function convertFromRaw(array $raw = []);
}
