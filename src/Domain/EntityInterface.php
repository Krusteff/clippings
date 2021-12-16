<?php

namespace Domain;

/**
 * Interface EntityInterface
 *
 * @author Martin Krastev <martin.krastev@devision.bg>
 */
interface EntityInterface
{
    /**
     * Populates entity from array
     *
     * @param array $data
     *
     * @return EntityInterface
     */
    public function fromArray(array $data = []): EntityInterface;
}
