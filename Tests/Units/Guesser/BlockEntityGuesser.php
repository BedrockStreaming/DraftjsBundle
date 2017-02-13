<?php

namespace M6Web\Bundle\DraftjsBundle\Tests\Units\Guesser;

use M6Web\Bundle\DraftjsBundle\Guesser\BlockEntityGuesser as TestedClass;
use M6Web\Bundle\DraftjsBundle\Tests\Units\TestsContextTrait;
use mageekguy\atoum;

/**
 * BlockEntityGuesser
 */
class BlockEntityGuesser extends atoum
{
    use TestsContextTrait;

    /**
     * Test add renderer to guesser
     */
    public function testAddRenderer()
    {
        $this->getMockGenerator()->orphanize('__construct');
        $blockEntityRenderer = new \mock\M6Web\Bundle\DraftjsBundle\Renderer\Entity\AbstractBlockEntityRenderer();

        $this
            ->if($blockGuesser = new TestedClass())
            ->then
                ->object($blockGuesser->addRenderer($blockEntityRenderer, 'alias'))
                ->isInstanceOf(TestedClass::class)
        ;
    }
}
