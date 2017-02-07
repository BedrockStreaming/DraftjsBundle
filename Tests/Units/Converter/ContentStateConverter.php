<?php

namespace M6Web\Bundle\DraftjsBundle\Tests\Units\Converter;

use M6Web\Bundle\DraftjsBundle\Converter\ContentStateConverter as TestedClass;
use mageekguy\atoum;
use M6Web\Bundle\DraftjsBundle\Tests\Units\TestsContextTrait;
use M6Web\Bundle\DraftjsBundle\Model\ContentState;
use M6Web\Bundle\DraftjsBundle\Model\ContentBlock;
use M6Web\Bundle\DraftjsBundle\Model\CharacterMetadata;
use M6Web\Bundle\DraftjsBundle\Exception\DraftjsException;

/**
 * ContentStateConverter
 */
class ContentStateConverter extends atoum
{
    use TestsContextTrait;

    /**
     * Test exception undefined entityMap
     */
    public function testConvertFromRawExceptionUndefinedEntityMap()
    {
        $rawState = [];

        $this
            ->if($converter = new TestedClass())
            ->then
                ->exception(
                    function () use ($converter, $rawState) {
                        $converter->convertFromRaw($rawState);
                    }
                )
                ->isInstanceOf(DraftjsException::class)
                ->hasMessage('Undefined entityMap key not allowed')
        ;
    }

    /**
     * Test exception undefined blocks
     */
    public function testConvertFromRawExceptionUndefinedBlocks()
    {

        $rawState = ['entityMap' => []];

        $this
            ->if($converter = new TestedClass())
            ->then
                ->exception(
                    function () use ($converter, $rawState) {
                        $converter->convertFromRaw($rawState);
                    }
                )
                ->isInstanceOf(DraftjsException::class)
                ->hasMessage('Undefined blocks key not allowed')
        ;
    }

    /**
     * Test convert result
     *
     * @throws DraftjsException
     */
    public function testConvertFromRaw()
    {
        $json = '{"entityMap":{},"blocks":[]}';
        $rawState = $this->getRawState($json);

        $this
            ->if($converter = new TestedClass())
            ->then
                ->object($contentState = $converter->convertFromRaw($rawState))
                ->isInstanceOf(ContentState::class)
        ;
    }

    /**
     * Test convert with simple state
     *
     * @throws \Exception
     */
    public function testConvertFromRawWithSimpleState()
    {
        $json = '{"entityMap":{},"blocks":[{"key":"e0vbh","text":"Hello world!","type":"unstyled","depth":0,"inlineStyleRanges":[{"offset":2,"length":2,"style":"BOLD"}],"entityRanges":[],"data":{}}]}';
        $rawState = $this->getRawState($json);

        $emptyCharacterMetadata = new CharacterMetadata();
        $boldCharacterMetadata = new CharacterMetadata(['BOLD']);

        $characterList = [
            $emptyCharacterMetadata,
            $emptyCharacterMetadata,
            $boldCharacterMetadata,
            $boldCharacterMetadata,
            $emptyCharacterMetadata,
            $emptyCharacterMetadata,
            $emptyCharacterMetadata,
            $emptyCharacterMetadata,
            $emptyCharacterMetadata,
            $emptyCharacterMetadata,
            $emptyCharacterMetadata,
            $emptyCharacterMetadata,
        ];

        $contentBlock = new ContentBlock('e0vbh', 'unstyled', 'Hello world!', $characterList, 0, []);

        $finalContentState = new ContentState();
        $finalContentState->setBlockMap([$contentBlock]);

        $this
            ->if($converter = new TestedClass())
            ->then
                ->object($contentState = $converter->convertFromRaw($rawState))
                ->isEqualTo($finalContentState)
        ;
    }
}
