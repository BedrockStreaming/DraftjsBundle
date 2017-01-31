<?php

namespace M6Web\Bundle\DraftjsBundle\Converter;

use M6Web\Bundle\DraftjsBundle\Model\CharacterMetadata;
use M6Web\Bundle\DraftjsBundle\Model\ContentState;
use M6Web\Bundle\DraftjsBundle\Model\ContentBlock;
use M6Web\Bundle\DraftjsBundle\Model\DraftEntity;
use M6Web\Bundle\DraftjsBundle\Exception\DraftjsException;

/**
 * Class ContentStateConverter
 *
 * @package M6Web\Bundle\DraftjsBundle\Model
 */
class ContentStateConverter implements ConverterInterface
{
    /**
     * Convert a raw state into a ContentState
     *
     * @param array $raw
     *
     * @throws DraftjsException
     *
     * @return ContentState
     */
    public function convertFromRaw(array $raw = [])
    {
        if (!isset($raw['entityMap'])) {
            throw new DraftjsException('ContentStateConverter undefined entityMap key not allowed');
        }

        if (!isset($raw['blocks'])) {
            throw new DraftjsException('ContentStateConverter undefined blocks key not allowed');
        }

        $contentState = new ContentState();

        $entities = $this->decodeEntitiesFromRaw($raw['entityMap']);
        $blocks = $this->decodeBlocksFromRaw($raw['blocks'], $entities);

        $contentState->setEntityMap($entities);
        $contentState->setBlockMap($blocks);

        return $contentState;
    }

    /**
     * @param array $entityMap
     *
     * @return array
     */
    private function decodeEntitiesFromRaw(array $entityMap = [])
    {
        $createEntityFromRaw = function ($entities, $rawEntity) {
            if (!DraftEntity::supportsType($rawEntity['type'])) {
                throw new DraftjsException(sprintf('Unsupported entity type "%s"', $rawEntity['type']));
            }

            if (!DraftEntity::supportsMutability($rawEntity['mutability'])) {
                throw new DraftjsException(sprintf('Unsupported entity mutability "%s"', $rawEntity['type']));
            }

            $type = $rawEntity['type'];
            $mutability = $rawEntity['mutability'];
            $data = $rawEntity['data'];

            $entity = new DraftEntity($type, $mutability, $data);

            $entities[] = $entity;

            return $entities;
        };

        return array_reduce($entityMap, $createEntityFromRaw, []);
    }

    /**
     * @param array $blocks
     * @param array $entities
     *
     * @return array
     */
    private function decodeBlocksFromRaw(array $blocks = [], array $entities = [])
    {
        $createBlockFromRaw = function ($contentBlocks, $block) use ($entities) {
            if (!ContentBlock::supportsType($block['type'])) {
                throw new DraftjsException(sprintf('Unsupported block type "%s"', $block['type']));
            }

            $entities = $this->decodeEntityRanges($block);
            $styles = $this->decodeInlineStyleRanges($block);

            $key = $block['key'];
            $type = $block['type'];
            $text = $block['text'];
            $depth = $block['depth'];
            $data = $block['data'];

            $characterList = $this->createCharacterList($text, $styles, $entities);

            $contentBlock = new ContentBlock($key, $type, $text, $characterList, $depth, $data);

            $contentBlocks[] = $contentBlock;

            return $contentBlocks;
        };

        return array_reduce($blocks, $createBlockFromRaw, []);
    }

    /**
     * @param array $block
     *
     * @return array
     */
    private function decodeEntityRanges(array $block = [])
    {
        $text = $block['text'];
        $entities = array_fill(0, strlen($text), null);

        if ($block['entityRanges']) {
            foreach ($block['entityRanges'] as $entityRange) {
                $offset = $entityRange['offset'];
                $length = $entityRange['length'];
                $entityKey = $entityRange['key'];

                $start = mb_strlen(mb_substr($text, 0, $offset));
                $end = $start + mb_strlen(mb_substr($text, $offset, $length));

                for ($i = $start; $i < $end; $i++) {
                    $entities[$i] = $entityKey;
                }
            }
        }

        return $entities;
    }

    /**
     * @param array $block
     *
     * @return array
     */
    private function decodeInlineStyleRanges(array $block = [])
    {
        $text = $block['text'];
        $styles = array_fill(0, strlen($text), []);

        if ($block['inlineStyleRanges']) {
            foreach ($block['inlineStyleRanges'] as $inlineStyleRange) {
                $offset = $inlineStyleRange['offset'];
                $length = $inlineStyleRange['length'];
                $style = $inlineStyleRange['style'];

                $cursor = mb_strlen(mb_substr($text, 0, $offset));
                $end = $cursor + mb_strlen(mb_substr($text, $offset, $length));

                while ($cursor < $end) {
                    $styles[$cursor][] = $style;
                    $cursor++;
                }
            }
        }

        return $styles;
    }

    /**
     * Generate a hash from styles and entity
     *
     * @return null|string
     */
    private function generateCharacterMetadataHash(array $styles = [], $entity = null)
    {
        $hash = null;

        if (count($styles) > 0) {
            $hash .= implode('-', $styles);
        }

        if (!is_null($entity)) {
            $hash .= strlen($hash) > 0 ? '-'.$entity : $entity;
        }

        return $hash;
    }

    /**
     * @param string $text
     * @param array  $styles
     * @param array  $entities
     *
     * @return array
     */
    private function createCharacterList($text = '', array $styles = [], array $entities = [])
    {
        $listCharacterMetadata = [];
        $handledCharacterMetadata = [];

        $chars = str_split($text);

        foreach ($chars as $index => $char) {
            $charEntity = $entities[$index];
            $charStyles = $styles[$index];

            $hash = $this->generateCharacterMetadataHash($charStyles, $charEntity);

            if (array_key_exists($hash, $handledCharacterMetadata)) {
                $characterMetadata = $handledCharacterMetadata[$hash];
            } else {
                $characterMetadata = new CharacterMetadata($charStyles, $charEntity);
            }

            $listCharacterMetadata[] = $characterMetadata;
            $handledCharacterMetadata[$hash] = $characterMetadata;
        }

        return $listCharacterMetadata;
    }
}
