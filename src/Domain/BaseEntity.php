<?php

namespace Domain;

/**
 * Class BaseEntity
 *
 * @author Martin Krastev <martin.krastev@devision.bg>
 */
abstract class BaseEntity implements EntityInterface
{
    /**
     * Currency constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->fromArray($data);
    }

    /**
     * Could be made "magical" with ReflectionClass to populate itself the data
     * @inheritDoc
     */
    public abstract function fromArray(array $data = []): EntityInterface;
}
