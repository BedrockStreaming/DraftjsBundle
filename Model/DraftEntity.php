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
            throw new DraftjsException(sprintf('Unsupported mutability "%s"', $mutability));
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
     * @param string $mutability
     *
     * @return bool
     *
     * @throws DraftjsException
     */
    public static function supportsMutability($mutability)
    {
        if (is_null($mutability) || empty($mutability)) {
            throw new DraftjsException('Unsupported null or empty mutability value');
        }

        return in_array(strtoupper($mutability), self::MUTABILITY);
    }
}
