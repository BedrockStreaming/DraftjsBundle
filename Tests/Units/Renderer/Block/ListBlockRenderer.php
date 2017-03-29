<?php

namespace M6Web\Bundle\DraftjsBundle\Tests\Units\Renderer\Block;

use M6Web\Bundle\DraftjsBundle\Renderer\Block\ListBlockRenderer as TestedClass;
use M6Web\Bundle\DraftjsBundle\Tests\Units\TestsContextTrait;
use M6Web\Bundle\DraftjsBundle\Renderer\Content\ContentRenderer;
use M6Web\Bundle\DraftjsBundle\Guesser\InlineEntityGuesser;
use M6Web\Bundle\DraftjsBundle\Converter\ContentStateConverter;
use mageekguy\atoum;

/**
 * HtmlRenderer
 */
class ListBlockRenderer extends atoum
{
    use TestsContextTrait;

    /**
     * Test exception undefined entityMap
     */
    public function testRender()
    {
        $json = '{"entityMap":{},"blocks":[{"key":"7a6j0","text":"Hello","type":"unordered-list-item","depth":0,"inlineStyleRanges":[],"entityRanges":[],"data":{}},';
        $json .= '{"key":"8mhu6","text":"World","type":"unordered-list-item","depth":0,"inlineStyleRanges":[],"entityRanges":[],"data":{}},';
        $json .= '{"key":"dpc05","text":"Hello World!","type":"unordered-list-item","depth":1,"inlineStyleRanges":[],"entityRanges":[],"data":{}}]}';
        $rawState = $this->getRawState($json);

        $contentState = (new ContentStateConverter())->convertFromRaw($rawState);
        $inlineEntityGuesser = new InlineEntityGuesser(); 
        $contentRender = new ContentRenderer($inlineEntityGuesser);
        $templating = new \mock\Symfony\Component\Templating\EngineInterface();
        $iterator = new \ArrayIterator($contentState->getBlockMap());
  
        $this
            ->if($renderer = new TestedClass($contentRender, $templating))
            ->then
                ->string($renderer->render($iterator, []))
                ->isEqualTo('<ul><li>Hello</li><li>World<ul><li>Hello World!</li></ul></li></ul>')
        ;
    }
}
