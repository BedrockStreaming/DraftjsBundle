<?php

namespace M6Web\Bundle\DraftjsBundle\Model;

use M6Web\Bundle\DraftjsBundle\Exception\DraftjsException;

/**
 * Class DraftEntity
 *
 * @package M6Web\Bundle\DraftjsBundle\Model
 */
class DraftEntity
{
    const MUTABLE = 'MUTABLE';
    const IMMUTABLE = 'IMMUTABLE';
    const SEGMENTED = 'SEGMENTED';

    const MUTABILITY = [self::MUTABLE, self::IMMUTABLE, self::SEGMENTED];

    const LINK = 'LINK';

    const TYPES = [self::LINK];

    /**
     * @var string $type
     */
    private $type;

    /**
     * @var string $mutability
     */
    private $mutability;

    /**
     * @var array $data
     */
    private $data = [];

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     *
     * @throws DraftjsException
     */
    public function setType($type)
    {
        if (!self::supportsType($type)) {
            throw new DraftjsException(sprintf('Unsupported type %s', $type));
        }

        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getMutability()
    {
        return $this->mutability;
    }

    /**
     * @param string $mutability
     *
     * @return $this
     *
     * @throws DraftjsException
     */
    public function setMutability($mutability)
    {
        if (!self::supportsMutability($mutability)) {
            throw new DraftjsException(sprintf('Unsupported mutability %s', $mutability));
        }

        $this->mutability = $mutability;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data = [])
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public static function supportsType($type)
    {
        return in_array($type, self::TYPES);
    }

    /**
     * @param string $mutability
     *
     * @return bool
     */
    public static function supportsMutability($mutability)
    {
        return in_array($mutability, self::MUTABILITY);
    }
}
