<?php

use Application\CurrencyService;
use Application\CustomerService;
use Application\DocumentService;
use Domain\DocumentType;

require __DIR__ . '/../vendor/autoload.php';

$documentService = new DocumentService();
$currencyService = new CurrencyService();
$customerService = new CustomerService();
$app = new \App\App($currencyService);

$submit = filter_input(INPUT_POST, 'submit');

function validateBuildAndPopulateDocuments(
    $fileData,
    DocumentService $documentService,
    CurrencyService $currencyService,
    CustomerService $customerService
) {
    if ($fileData['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Failed to upload data');
    }

    $csvStream = fopen($fileData['tmp_name'], 'r+');
    $csvData = [];

    while (($line = fgetcsv($csvStream))) {
        $csvData[] = $line;
    }

    fclose($csvStream);

    array_shift($csvData); // remove headings

    // APP @TODO: there is case when parent could come after the child... think if u want to validate this
    foreach ($csvData as $customerData) {
        if (($currency = $currencyService->findByCode($customerData[5])) === null) {
            continue;
        }

        $documentService->create([
            'customer' => $customerService->findByNameOrCreate($customerData[0]),
            'vatNumber' => $customerData[1],
            'number' => $customerData[2],
            'total' => $customerData[6],
            'parent' => $documentService->findByNumber($customerData[4]),
            'type' => DocumentType::from($customerData[3]),
            'currency' => $currency,
        ]);
    }
}

function validateBuildAndStoreCurrencies(CurrencyService $currencyService)
{
    $currencies = explode(',', filter_input(INPUT_POST, 'currencies'));

    array_walk($currencies, 'trim');

    foreach ($currencies as $currency) {
        $currencyData = explode(':', $currency);
        $currencyService->create(['code' => $currencyData[0], 'multiplier' => (float)$currencyData[1]]);
    }
}

if ($submit) {
    validateBuildAndStoreCurrencies($currencyService);
    validateBuildAndPopulateDocuments($_FILES['data'], $documentService, $currencyService, $customerService);

    $outputCurrencyCode = filter_input(INPUT_POST, 'outputCurrency');
    $outputCurrency = $currencyService->findByCode($outputCurrencyCode);

    if ($outputCurrency === null) {
        $outputCurrency = $currencyService->getDefault();
    }

    // validate the output currency exists; if not mbe default?

    $specificCustomerName = filter_input(INPUT_POST, 'specificCustomer');
    $specificCustomer = $customerService->findByName($specificCustomerName);
    // validate if customer exists; otherwise all?

    $documents = $documentService->getAll();
    $data = $app->doTheJob($documents, $outputCurrency, $specificCustomer);
    echo <<<HTML
  <!doctype html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
  </head>
  <body>
  HTML;
    foreach ($data as $customerName => $total) {
        echo "Customer $customerName - $total $outputCurrencyCode <br/>";
    }
    echo <<<HTML
  </body>
  </html>
HTML;
} else {
    echo <<<HTML
  <!doctype html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
  </head>
  <body>
  <form method="post" enctype="multipart/form-data">
    Currencies*: <input type="text" name="currencies"> (example: BGN:1,USD:0.58,EUR:0.51,GBP:0.43)<br/>
    Output currency*: <input type="text" name="outputCurrency"> (example: BGN)<br/>
    Specific customer: <input type="text" name="specificCustomer"> (example: John Doe)<br/>
    File: <input type="file" name="data"><br/>
    <input type="submit" value="Upload data" name="submit">
  </form>
  <p>
    Notes:<br/>
    <ul>
      <li>If customer is provided and found the result will show only his invoices</li>
      <li>If the file contains any currency that is not provided with multiplier it will be skipped</li>
      <li>The file template and format MUST be followed <a href="example.csv">here</a></li>
    </ul>
  </p>
  </body>
  </html>
HTML;
}