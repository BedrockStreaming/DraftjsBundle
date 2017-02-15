<?php

namespace M6Web\Bundle\DraftjsBundle\Renderer;

/**
 * Class EngineRendererInterface
 *
 * @package M6Web\Bundle\DraftjsBundle\Renderer
 */
interface EngineRendererInterface extends RendererInterface
{
    /**
     * @param array $rawState
     *
     * @return mixed
     */
    public function render(array $rawState);
}
