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
    const EMBED = 'EMBED';

    const TYPES = [self::LINK, self::EMBED];

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
     * DraftEntity constructor.
     *
     * @param string $type
     * @param string $mutability
     * @param array  $data
     *
     * @throws DraftjsException
     */
    public function __construct($type, $mutability = self::MUTABLE, array $data = [])
    {
        if (!self::supportsType($type)) {
            throw new DraftjsException(sprintf('DraftEntity unsupported type "%s"', $type));
        }

        if (!self::supportsMutability($mutability)) {
            throw new DraftjsException(sprintf('DraftEntity Unsupported mutability "%s"', $mutability));
        }

        $this->type = strtoupper($type);
        $this->mutability = strtoupper($mutability);
        $this->data = $data;
    }

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
            throw new DraftjsException(sprintf('DraftEntity unsupported type "%s"', $type));
        }

        $this->type = strtoupper($type);

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
            throw new DraftjsException(sprintf('DraftEntity unsupported mutability "%s"', $mutability));
        }

        $this->mutability = strtoupper($mutability);

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
     *
     * @throws DraftjsException
     */
    public static function supportsType($type)
    {
        if (is_null($type) || empty($type)) {
            throw new DraftjsException('DraftEntity null or empty type not allowed');
        }

        return in_array(strtoupper($type), self::TYPES);
    }

    /**
     * @param string $mutability
     *
     * @return bool
     *
     * @throws DraftjsException
     */
    public static function supportsMutability($mutability)
    {
        if (is_null($mutability) || empty($mutability)) {
            throw new DraftjsException('DraftEntity null or empty mutability not allowed');
        }

        return in_array(strtoupper($mutability), self::MUTABILITY);
    }
}
