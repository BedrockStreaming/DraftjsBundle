<?php

namespace M6Web\Bundle\DraftjsBundle\Tests\Units\Builder;

use M6Web\Bundle\DraftjsBundle\Builder\HtmlBuilder as TestedClass;
use mageekguy\atoum;
use M6Web\Bundle\DraftjsBundle\Tests\Units\TestsContextTrait;

/**
 * HtmlBuilder
 */
class HtmlBuilder extends atoum
{
    use TestsContextTrait;

    /**
     * Test build
     */
    public function testBuild()
    {
        $templating = $this->getMockTemplating();
        $contentState = $this->getContentState();

        $this
            ->if($builder = new TestedClass($templating))
            ->then
                ->string($builder->build($contentState))
                ->isEqualTo('He<span class="u-bold u-italic">ll</span><span class="u-italic">o wo</span>rld!')
        ;
    }

    /**
     * Test build with custom classNames
     */
    public function testBuildCustomClassNames()
    {
        $templating = $this->getMockTemplating();
        $contentState = $this->getContentState();

        $customClassNames = [
            'bold' => 'u-strong',
            'italic' => 'customclass',
        ];

        $this
            ->if($builder = new TestedClass($templating, $customClassNames))
            ->then
                ->string($builder->build($contentState))
                ->isEqualTo('He<span class="u-strong customclass">ll</span><span class="customclass">o wo</span>rld!')
        ;
    }
}
