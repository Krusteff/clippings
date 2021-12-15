<?php

namespace App;

use Application\CurrencyService;
use Domain\Currency;
use Domain\Customer;
use Domain\Document;
use Domain\DocumentType;

/**
 * Class App
 *
 * @author Martin Krastev <martin.krastev@devision.bg>
 */
final class App
{
    /**
     * App constructor.
     *
     * @param CurrencyService $currencyService
     */
    public function __construct(private CurrencyService $currencyService)
    {
    }

    /**
     * @param Document[] $documents
     * @param Currency $outputCurrency
     * @param Customer|null $specifiedCustomer
     *
     * @return array
     */
    public function doTheJob(array $documents, Currency $outputCurrency, Customer $specifiedCustomer = null): array
    {
        $defaultCurrency = $this->currencyService->getDefault();

        if ($specifiedCustomer) {
            $documents = $specifiedCustomer->getDocuments();
        }

        $data = [];

        foreach ($documents as $document) {
            $type = $document->getType();
            $total = $document->getTotal();
            $currency = $document->getCurrency();

            if ($currency !== $defaultCurrency) {
                $total /= $currency->getMultiplier();
            }

            $customerName = $document->getCustomer()->getName();

            if (!key_exists($customerName, $data)) {
                $data[$customerName] = 0;
            }

            if ($type === DocumentType::INVOICE || $type === DocumentType::DEBIT) {
                $data[$customerName] += $total;
            } else {
                $data[$customerName] -= $total;
            }
        }

        if ($outputCurrency !== $defaultCurrency) {
            foreach ($data as &$total) {
                $total *= $outputCurrency->getMultiplier();
            }
        }

        return $data;
    }
}
