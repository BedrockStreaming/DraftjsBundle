<?php

namespace M6Web\Bundle\DraftjsBundle\Tests\Units\Guesser;

use M6Web\Bundle\DraftjsBundle\Guesser\BlockGuesser as TestedClass;
use mageekguy\atoum;
use M6Web\Bundle\DraftjsBundle\Tests\Units\TestsContextTrait;

/**
 * BlockGuesser
 */
class BlockGuesser extends atoum
{
    use TestsContextTrait;

    /**
     * Test add renderer to guesser
     */
    public function testAddRenderer()
    {
        $inlineEntityGuesser = new \mock\M6Web\Bundle\DraftjsBundle\Guesser\InlineEntityGuesser();
        $contentRenderer = new \mock\M6Web\Bundle\DraftjsBundle\Renderer\Content\ContentRenderer($inlineEntityGuesser);
        $templating = $this->getMockTemplating();

        $blockRenderer = new \mock\M6Web\Bundle\DraftjsBundle\Renderer\Block\DefaultBlockRenderer($contentRenderer, $templating);

        $this
            ->if($blockGuesser = new TestedClass())
            ->then
                ->object($blockGuesser->addRenderer($blockRenderer, 'alias'))
                ->isInstanceOf(TestedClass::class)
        ;
    }
}
