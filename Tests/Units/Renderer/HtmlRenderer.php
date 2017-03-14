<?php

namespace M6Web\Bundle\DraftjsBundle\Tests\Units\Renderer;

use M6Web\Bundle\DraftjsBundle\Renderer\HtmlRenderer as TestedClass;
use M6Web\Bundle\DraftjsBundle\Tests\Units\TestsContextTrait;
use mageekguy\atoum;

/**
 * HtmlRenderer
 */
class HtmlRenderer extends atoum
{
    use TestsContextTrait;

    /**
     * Test renderer with default block renderer
     */
    public function testRenderDefaultBlock()
    {
        $json = <<<JSON
{
  "entityMap":{

  },
  "blocks":[
    {
      "key":"e0vbh",
      "text":"Hello world!",
      "type":"unstyled",
      "depth":0,
      "inlineStyleRanges":[
        {
          "offset":2,
          "length":2,
          "style":"BOLD"
        }
      ],
      "entityRanges":[

      ],
      "data":{

      }
    }
  ]
}
JSON;

        $rawState = $this->getRawState($json);

        $blockGuesser = $this->getMockBlockGuesser();
        $converter = $this->getMockConverter();
        $builder = $this->getMockBuilder($blockGuesser);

        $this
            ->if($renderer = new TestedClass($converter, $builder))
            ->then
                ->string($renderer->render($rawState))
                ->isEqualTo('<div class="default-block">He<span class="bold">ll</span>o world!</div>')
            ->then
                ->mock($converter)->call('convertFromRaw')->withArguments($rawState)->once()
                ->mock($builder)->call('build')->once()
        ;
    }

    /**
     * Test renderer with atomic block renderer
     */
    public function testRenderAtomicBlock()
    {
        $json = <<<JSON
{
    "entityMap":{
        "0": {
            "type":"ACME",
            "mutability":"MUTABLE",
            "data":{
                "content": "This is me"
            }
        }
    },
    "blocks":[
        {
            "key":"7sf8s",
            "text":" ",
            "type":"atomic",
            "depth":0,
            "inlineStyleRanges":[
            ],
            "entityRanges":[
                {
                    "offset":0,
                    "length":1,
                    "key":"0"
                }
            ],
            "data":{
            }
        }
    ]
}
JSON;

        $rawState = $this->getRawState($json);

        $blockGuesser = $this->getMockBlockGuesser();
        $converter = $this->getMockConverter();
        $builder = $this->getMockBuilder($blockGuesser);

        $this
            ->if($renderer = new TestedClass($converter, $builder))
                ->then
                    ->string($renderer->render($rawState))
                    ->isEqualTo('<figure class="atomic-block"><div class="acme-block-entity">This is me</div></figure>')
                        ->then
                            ->mock($converter)->call('convertFromRaw')->withArguments($rawState)->once()
                            ->mock($builder)->call('build')->once()
        ;
    }
}
