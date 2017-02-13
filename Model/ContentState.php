<?php

namespace M6Web\Bundle\DraftjsBundle\Model;

/**
 * Class ContentState
 *
 * @package M6Web\Bundle\DraftjsBundle\Model
 */
class ContentState
{
    /**
     * @var ContentBlock[]
     */
    private $blockMap = [];

    /**
     * @var DraftEntity[]
     */
    private $entityMap = [];

    /**
     * ContentState constructor.
     *
     * @param array $blockMap
     * @param array $entityMap
     */
    public function __construct(array $blockMap = [], array $entityMap = [])
    {
        $this->blockMap = $blockMap;
        $this->entityMap = $entityMap;
    }

    /**
     * @return array|ContentBlock[]
     */
    public function getBlockMap()
    {
        return $this->blockMap;
    }

    /**
     * @param array $blocks
     *
     * @return $this
     */
    public function setBlockMap(array $blocks = [])
    {
        $this->blockMap = $blocks;

        return $this;
    }

    /**
     * @return array|DraftEntity[]
     */
    public function getEntityMap()
    {
        return $this->entityMap;
    }

    /**
     * @param array $entities
     *
     * @return $this
     */
    public function setEntityMap(array $entities = [])
    {
        $this->entityMap = $entities;

        return $this;
    }

    /**
     * Returns the full plaintext value of the contents, joined with a delimiter.
     * If no delimiter is specified, the line feed character (\u000A) is used.
     *
     * @param string $delimiter
     *
     * @return mixed
     */
    public function getPlainText($delimiter = '\u000A')
    {
        return array_reduce($this->blockMap, function ($output, ContentBlock $contentBlock) use ($delimiter) {
            return sprintf('%s%s%s', $output, $contentBlock->getText(), $delimiter);
        }, '');
    }
}
