<?php

require __DIR__ . '/../vendor/autoload.php';

$documentService = new Application\DocumentService();
$currencyService = new Application\CurrencyService();
$customerService = new Application\CustomerService();
$renderer = new Infrastructure\Renderer();

$submit = boolval(filter_input(INPUT_POST, 'submit'));
$currenciesInput = filter_input(INPUT_POST, 'currencies');
$outputCurrencyCode = filter_input(INPUT_POST, 'outputCurrency');
$specificCustomerVatNumber = filter_input(INPUT_POST, 'specificCustomerVatNumber');
$fileData = $_FILES['data'];

$app = new \App\App($currencyService, $documentService, $customerService, $renderer);
$app->run($submit, $currenciesInput, $outputCurrencyCode, $specificCustomerVatNumber, $fileData);
