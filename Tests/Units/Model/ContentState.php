<?php

namespace M6Web\Bundle\DraftjsBundle\Tests\Units\Model;

use M6Web\Bundle\DraftjsBundle\Model\ContentState as TestedClass;
use mageekguy\atoum;
use M6Web\Bundle\DraftjsBundle\Model\ContentBlock;
use M6Web\Bundle\DraftjsBundle\Model\CharacterMetadata;
use M6Web\Bundle\DraftjsBundle\Exception\DraftjsException;

/**
 * ContentState
 */
class ContentState extends atoum
{
    /**
     * Test convert with simple state
     *
     * @throws DraftjsException
     */
    public function testGetPlainText()
    {
        $contentBlock = new ContentBlock();
        $contentBlock->setKey('e0vbh');
        $contentBlock->setText('Hello world!');
        $contentBlock->setDepth(0);
        $contentBlock->setType(ContentBlock::UNSTYLED);
        $contentBlock->setData([]);
        $contentBlock->setCharacterList([
            new CharacterMetadata(),
            new CharacterMetadata(),
            new CharacterMetadata(['BOLD']),
            new CharacterMetadata(['BOLD']),
            new CharacterMetadata(),
            new CharacterMetadata(),
            new CharacterMetadata(),
            new CharacterMetadata(),
            new CharacterMetadata(),
            new CharacterMetadata(),
            new CharacterMetadata(),
            new CharacterMetadata(),
        ]);


        $this
            ->if($contentState = new TestedClass([$contentBlock]))
            ->then
                ->string($contentState->getPlainText())
                ->isEqualTo('Hello world!\u000A')
            ->then
                ->string($contentState->getPlainText('custom separator'))
                ->isEqualTo('Hello world!custom separator')
        ;
    }
}
