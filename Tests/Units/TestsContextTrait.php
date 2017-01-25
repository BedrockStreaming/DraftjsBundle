<?php

namespace M6Web\Bundle\DraftjsBundle\Tests\Units;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use M6Web\Bundle\DraftjsBundle\Model\CharacterMetadata;
use M6Web\Bundle\DraftjsBundle\Model\ContentBlock;
use M6Web\Bundle\DraftjsBundle\Model\ContentState;

/**
 * Trait TestsContextTrait
 */
trait TestsContextTrait
{
    /**
     * @return \mock\Symfony\Bundle\FrameworkBundle\Templating\EngineInterface
     */
    private function getMockTemplating()
    {
        $templating = new \mock\Symfony\Bundle\FrameworkBundle\Templating\EngineInterface();
        $templating->getMockController()->render = function ($filename, $params) {
            return $params['innerHTML'];
        };

        return $templating;
    }

    /**
     * @return \mock\M6Web\Bundle\DraftjsBundle\Converter\ContentStateConverter
     */
    private function getMockConverter()
    {
        return new \mock\M6Web\Bundle\DraftjsBundle\Converter\ContentStateConverter();
    }

    /**
     * @param EngineInterface $templating
     *
     * @return \mock\M6Web\Bundle\DraftjsBundle\Builder\HtmlBuilder
     */
    private function getMockBuilder(EngineInterface $templating)
    {
        return new \mock\M6Web\Bundle\DraftjsBundle\Builder\HtmlBuilder($templating);
    }

    /**
     * @return ContentState
     *
     * @throws \M6Web\Bundle\DraftjsBundle\Exception\DraftjsException
     */
    private function getContentState()
    {
        $emptyCharacterMetadata = new CharacterMetadata();
        $boldItalicCharacterMetadata = new CharacterMetadata(['BOLD', 'ITALIC']);
        $italicCharacterMetadata = new CharacterMetadata(['ITALIC']);

        $contentBlock = new ContentBlock();
        $contentBlock->setKey('e0vbh');
        $contentBlock->setText('Hello world!');
        $contentBlock->setDepth(0);
        $contentBlock->setType(ContentBlock::UNSTYLED);
        $contentBlock->setData([]);
        $contentBlock->setCharacterList([
            $emptyCharacterMetadata,
            $emptyCharacterMetadata,
            $boldItalicCharacterMetadata,
            $boldItalicCharacterMetadata,
            $italicCharacterMetadata,
            $italicCharacterMetadata,
            $italicCharacterMetadata,
            $italicCharacterMetadata,
            $emptyCharacterMetadata,
            $emptyCharacterMetadata,
            $emptyCharacterMetadata,
            $emptyCharacterMetadata,
        ]);

        return new ContentState([$contentBlock]);
    }

    /**
     * @param string $json
     *
     * @return mixed
     */
    private function getRawState($json)
    {
        return json_decode($json, true);
    }
}