<?php

namespace Domain;

/**
 * Class Currency
 *
 * @author Martin Krastev <martin.krastev@devision.bg>
 */
class Currency
{
    private string $code;

    private float $multiplier;

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
     * @param array $data
     *
     * @return $this
     */
    public function fromArray(array $data = []): Currency
    {
        if (key_exists('code', $data)) {
            $this->setCode($data['code']);
        }

        if (key_exists('multiplier', $data)) {
            $this->setMultiplier($data['multiplier']);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return Currency
     */
    public function setCode(string $code): Currency
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return float
     */
    public function getMultiplier(): float
    {
        return $this->multiplier;
    }

    /**
     * @param float $multiplier
     *
     * @return Currency
     */
    public function setMultiplier(float $multiplier): Currency
    {
        $this->multiplier = $multiplier;

        return $this;
    }
}
