<?php

namespace M6Web\Bundle\DraftjsBundle\Tests\Units\Renderer;

use M6Web\Bundle\DraftjsBundle\Renderer\HtmlRenderer as TestedClass;
use mageekguy\atoum;
use M6Web\Bundle\DraftjsBundle\Tests\Units\TestsContextTrait;

/**
 * HtmlRenderer
 */
class HtmlRenderer extends atoum
{
    use TestsContextTrait;

    /**
     * Test exception undefined entityMap
     */
    public function testRender()
    {
        $json = '{"entityMap":{},"blocks":[{"key":"e0vbh","text":"Hello world!","type":"unstyled","depth":0,"inlineStyleRanges":[{"offset":2,"length":2,"style":"BOLD"}],"entityRanges":[],"data":{}}]}';
        $rawState = $this->getRawState($json);

        $templating = $this->getMockTemplating();
        $converter = $this->getMockConverter();
        $builder = $this->getMockBuilder($templating);

        $this
            ->if($renderer = new TestedClass($converter, $builder))
            ->then
                ->string($renderer->render($rawState))
                ->isEqualTo('He<span class="u-bold">ll</span>o world!')
            ->then
                ->mock($converter)->call('convertFromRaw')->withArguments($rawState)->once()
                ->mock($builder)->call('build')->once()
        ;
    }
}
