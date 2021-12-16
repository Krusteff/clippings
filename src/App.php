<?php

namespace App;

use Application\CurrencyService;
use Application\CustomerService;
use Application\DocumentService;
use Domain\Currency;
use Domain\Document;
use Domain\DocumentType;
use Exception;
use Infrastructure\CsvFileValidator;
use Infrastructure\CurrenciesFormValidator;
use Infrastructure\Renderer;
use RuntimeException;

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
     * @param DocumentService $documentService
     * @param CustomerService $customerService
     * @param Renderer $renderer
     */
    public function __construct(
        private CurrencyService $currencyService,
        private DocumentService $documentService,
        private CustomerService $customerService,
        private Renderer        $renderer
    ) {
    }

    /**
     * Stores Documents
     *
     * @param array $data
     */
    public function storeDocuments(array $data): void
    {
        // APP @TODO: there is case when parent could come after the child... think if u want to validate this
        foreach ($data as $line) {
            if (($currency = $this->currencyService->findByCode($line[5])) === null) {
                throw new RuntimeException('Currency not found');
            }

            if (!empty($line[4]) && ($parent = $this->documentService->findByNumber($line[4]) === null)) {
                throw new RuntimeException('Parent not found');
            }

            $this->documentService->create([
                'customer' => $this->customerService->findOrCreate($line[0], $line[1]),
                'number' => $line[2],
                'total' => $line[6],
                'parent' => $parent ?? null,
                'type' => DocumentType::from($line[3]),
                'currency' => $currency,
            ]);
        }
    }

    /**
     * Stores currencies
     *
     * @param array $currenciesData
     */
    public function storeCurrencies(array $currenciesData): void
    {
        foreach ($currenciesData as $currencyData) {
            $this->currencyService->create($currencyData);
        }
    }

    /**
     * Entry point of the app.
     *
     * @param bool $isSubmitted
     * @param string|null $currenciesInput
     * @param string|null $outputCurrencyCode
     * @param string|null $specificCustomerVatNumber
     * @param array|null $fileData
     */
    public function run(
        bool    $isSubmitted,
        ?string $currenciesInput,
        ?string $outputCurrencyCode,
        ?string $specificCustomerVatNumber,
        ?array  $fileData
    ): void {
        if ($isSubmitted) {
            try {
                $currencies = strlen($currenciesInput) ? explode(',', $currenciesInput) : [];
                $validatedCurrenciesData = CurrenciesFormValidator::validate($currencies);
                $validatedCsvFileData = CsvFileValidator::validate($fileData);

                $this->storeCurrencies($validatedCurrenciesData);
                $this->storeDocuments($validatedCsvFileData);

                $outputCurrency = $this->currencyService->findByCode($outputCurrencyCode);

                if (!empty($outputCurrencyCode) && $outputCurrency === null) {
                    throw new RuntimeException('Output currency not valid');
                }

                if ($specificCustomerVatNumber) {
                    $specificCustomer = $this->customerService->findByVatNumber($specificCustomerVatNumber);
                }

                $documents = isset($specificCustomer) ? $specificCustomer->getDocuments() : $this->documentService->getAll();

                $data['result'] = $this->getResult($documents, $outputCurrency);
                $data['outputCurrencyCode'] = $outputCurrencyCode;
            } catch (RuntimeException $e) {
                $data['error'] = $e->getMessage();
            } catch (Exception $e) {
                $data['error'] = 'Oops! Something went completely wrong...';
            }

            echo $this->renderer->render('result.html', $data);
        } else {
            echo $this->renderer->render('form.html');
        }
    }

    /**
     * @param Document[] $documents
     * @param Currency $outputCurrency
     *
     * @return array
     */
    public function getResult(array $documents, Currency $outputCurrency): array
    {
        $defaultCurrency = $this->currencyService->getDefault();
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
            $this->convertFromDefaultTo($data, $outputCurrency);
        }

        return $data;
    }

    /**
     * Converts from default currency to given one
     *
     * @param array $data
     * @param Currency $currency
     */
    private function convertFromDefaultTo(array &$data, Currency $currency): void
    {
        foreach ($data as &$total) {
            $total *= $currency->getMultiplier();
        }
    }
}
