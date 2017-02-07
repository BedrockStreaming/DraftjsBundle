<?php

namespace M6Web\Bundle\DraftjsBundle\Tests\Units\Guesser;

use M6Web\Bundle\DraftjsBundle\Guesser\InlineEntityGuesser as TestedClass;
use mageekguy\atoum;
use M6Web\Bundle\DraftjsBundle\Tests\Units\TestsContextTrait;

/**
 * InlineEntityGuesser
 */
class InlineEntityGuesser extends atoum
{
    use TestsContextTrait;

    /**
     * Test add renderer to guesser
     */
    public function testAddRenderer()
    {
        $inlineEntityRenderer = new \mock\M6Web\Bundle\DraftjsBundle\Renderer\Inline\AbstractInlineEntityRenderer();

        $this
            ->if($blockGuesser = new TestedClass())
            ->then
                ->object($blockGuesser->addRenderer($inlineEntityRenderer, 'alias'))
                ->isInstanceOf(TestedClass::class)
        ;
    }
}
