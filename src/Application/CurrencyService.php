<?php

namespace Application;

use Domain\Currency;

/**
 * Class CurrencyServiceTest
 *
 * @author Martin Krastev <martin.krastev@devision.bg>
 */
class CurrencyService
{
    /**
     * @var Currency[]
     */
    private array $currencies = [];

    /**
     * Returns default currency
     * @return Currency|null
     */
    public function getDefault(): ?Currency
    {
        foreach ($this->currencies as $currency) {
            if ($currency->getMultiplier() === (float) 1) {
                return $currency;
            }
        }

        return null;
    }

    /**
     * Creates currency and adds it to the "internal storage"
     *
     * @param array $data
     *
     * @return Currency
     */
    public function create(array $data): Currency
    {
        $currency = new Currency($data);
        $this->currencies[] = $currency;

        return $currency;
    }

    /**
     * @param string $code
     *
     * @return Currency|null
     */
    public function findByCode(string $code): ?Currency
    {
        foreach ($this->currencies as $currency) {
            if ($currency->getCode() === $code) {
                return $currency;
            }
        }

        return null;
    }
}
