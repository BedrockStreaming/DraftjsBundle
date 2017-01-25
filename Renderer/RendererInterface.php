<?php

namespace M6Web\Bundle\DraftjsBundle\Renderer;

/**
 * Interface RendererInterface
 *
 * @package M6Web\Bundle\DraftjsBundle\Renderer
 */
interface RendererInterface
{
    /**
     * @param array $raw
     *
     * @return mixed
     */
    public function render(array $raw = []);
}
