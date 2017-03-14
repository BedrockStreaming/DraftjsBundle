<?php

namespace M6Web\Bundle\DraftjsBundle\Tests\Units\Builder;

use M6Web\Bundle\DraftjsBundle\Builder\HtmlBuilder as TestedClass;
use M6Web\Bundle\DraftjsBundle\Exception\DraftjsException;
use M6Web\Bundle\DraftjsBundle\Tests\Units\TestsContextTrait;
use mageekguy\atoum;

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
        $contentState = $this->getMockContentState();
        $blockGuesser = $this->getMockBlockGuesser();

        $this
            ->if($builder = new TestedClass($blockGuesser))
            ->then
                ->string($builder->build($contentState))
                ->isEqualTo('<div class="default-block">He<span class="bold italic">ll</span><span class="italic">o wo</span>rld!</div>')
        ;
    }

    /**
     * Test build html with custom classNames for content renderer
     */
    public function testBuildWithCustomClassNames()
    {
        $contentState = $this->getMockContentState();

        $inlineClassNames = [
            'bold' => 'u-strong',
            'italic' => 'custom-class',
        ];

        $blockGuesser = $this->getMockBlockGuesser($inlineClassNames);

        $this
            ->if($builder = new TestedClass($blockGuesser))
            ->then
                ->string($builder->build($contentState))
                ->isEqualTo('<div class="default-block">He<span class="u-strong custom-class">ll</span><span class="custom-class">o wo</span>rld!</div>')
        ;
    }

    /**
     * Test build with unknow block type
     */
    public function testBuildWithUnknowBlockRenderer()
    {
        $contentBlock = new \mock\M6Web\Bundle\DraftjsBundle\Model\ContentBlock('e0vbh', 'custom-block-type', 'Hello world!', [], 0, []);
        $contentState = new \mock\M6Web\Bundle\DraftjsBundle\Model\ContentState([$contentBlock]);
        $blockGuesser = $this->getMockBlockGuesser();

        $this
            ->if($builder = new TestedClass($blockGuesser))
            ->then
                ->exception(
                    function () use ($builder, $contentState) {
                        $builder->build($contentState);
                    }
                )
                ->isInstanceOf(DraftjsException::class)
                ->hasMessage('Undefined block renderer for type "custom-block-type"')
        ;
    }

    /**
     * Test build with unknow inline entity type
     */
    public function testBuildWithUnknowInlineEntityRenderer()
    {
        $entityMap = [
            new \mock\M6Web\Bundle\DraftjsBundle\Model\DraftEntity('custom-entity-type', 'mutable', []),
        ];

        $emptyCharacterMetadata = new \mock\M6Web\Bundle\DraftjsBundle\Model\CharacterMetadata();
        $entityCharacterMetadata = new \mock\M6Web\Bundle\DraftjsBundle\Model\CharacterMetadata([], 0);

        $characterList = [
            $emptyCharacterMetadata,
            $emptyCharacterMetadata,
            $entityCharacterMetadata,
            $entityCharacterMetadata,
            $entityCharacterMetadata,
            $emptyCharacterMetadata,
            $emptyCharacterMetadata,
            $emptyCharacterMetadata,
            $emptyCharacterMetadata,
            $emptyCharacterMetadata,
            $emptyCharacterMetadata,
            $emptyCharacterMetadata,
        ];

        $contentBlock = new \mock\M6Web\Bundle\DraftjsBundle\Model\ContentBlock('e0vbh', 'unstyled', 'Hello world!', $characterList, 0, []);
        $contentState = new \mock\M6Web\Bundle\DraftjsBundle\Model\ContentState([$contentBlock], $entityMap);
        $blockGuesser = $this->getMockBlockGuesser();

        $this
            ->if($builder = new TestedClass($blockGuesser))
            ->then
                ->exception(
                    function () use ($builder, $contentState) {
                        $builder->build($contentState);
                    }
                )
                ->isInstanceOf(DraftjsException::class)
                ->hasMessage('Undefined inline entity renderer for type "CUSTOM-ENTITY-TYPE"')
        ;
    }

    /**
     * Test build with unknow block entity type
     */
    public function testBuildWithUnknowBlockEntityRenderer()
    {
        $entityMap = [
            new \mock\M6Web\Bundle\DraftjsBundle\Model\DraftEntity('custom-entity-type', 'mutable', []),
        ];

        $entityCharacterMetadata = new \mock\M6Web\Bundle\DraftjsBundle\Model\CharacterMetadata([], 0);

        $characterList = [
            $entityCharacterMetadata,
        ];

        $contentBlock = new \mock\M6Web\Bundle\DraftjsBundle\Model\ContentBlock('e0vbh', 'atomic', ' ', $characterList, 0, []);
        $contentState = new \mock\M6Web\Bundle\DraftjsBundle\Model\ContentState([$contentBlock], $entityMap);
        $blockGuesser = $this->getMockBlockGuesser();

        $this
            ->if($builder = new TestedClass($blockGuesser))
            ->then
                ->exception(
                    function () use ($builder, $contentState) {
                        $builder->build($contentState);
                    }
                )
                ->isInstanceOf(DraftjsException::class)
                ->hasMessage('Undefined block entity renderer for type "custom-entity-type"')
        ;
    }
}
