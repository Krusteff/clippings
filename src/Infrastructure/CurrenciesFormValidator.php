<?php

namespace Infrastructure;

use RuntimeException;

/**
 * Class CurrenciesFormValidator
 *
 * @author Martin Krastev <martin.krastev@devision.bg>
 */
class CurrenciesFormValidator
{
    /**
     * @param array $currenciesData
     *
     * @return array
     */
    public static function validate(array $currenciesData)
    {
        if (!count($currenciesData)) {
            throw new RuntimeException('Currencies are required');
        }

        $currencies = array_map('trim', $currenciesData);
        $data = [];
        $defaultCurrencyProvided = false;

        foreach ($currencies as $currency) {
            $currencyData = explode(':', $currency);

            if (count($currencyData) !== 2) {
                throw new RuntimeException('Currencies must be in format CODE:MULTIPLIER. Example: BGN:1.5');
            }

            if (strlen($currencyData[0]) !== 3) {
                throw new RuntimeException('Currency code MUST follow the ISO_4217 standard');
            }

            if (!is_numeric($currencyData[1])) {
                throw new RuntimeException('Multiplier value of the currency must be a number');
            }

            $multiplier = floatval($currencyData[1]);

            if ($multiplier === floatval(1)) {
                $defaultCurrencyProvided = true;
            }

            $data[] = ['code' => $currencyData[0], 'multiplier' => $multiplier];
        }

        if (!$defaultCurrencyProvided) {
            throw new RuntimeException('No default currency provided');
        }

        return $data;
    }
}
