<?php

namespace Tests\Unit\Application;

use App\App;
use Application\CurrencyService;
use Application\CustomerService;
use Application\DocumentService;
use Infrastructure\Renderer;
use PHPUnit\Framework\TestCase;

/**
 * Class AppTest
 *
 * @author Martin Krastev <martin.krastev@devision.bg>
 */
final class AppTest extends TestCase
{
    public function testWhetherTheAppWorksAsExpected(): void
    {
        $currencyServiceMock = new CurrencyService();
        $documentServiceMock = new DocumentService();
        $customerServiceMock = new CustomerService();
        $rendererMock = $this->getMockBuilder(Renderer::class)->getMock();
        $rendererMock->expects($this->once())->method('render')->with('result.html');

        $app = new App($currencyServiceMock, $documentServiceMock, $customerServiceMock, $rendererMock);

        $validCurrenciesInput = 'BGN:1,USD:0.58,EUR:0.51,GBP:0.43';
        $validOutputCurrencyCode = 'USD';
        $validSpecificCustomerVatNumber = null;

        $tmpFile = tmpfile();
        $path = stream_get_meta_data($tmpFile)['uri'];
        $dataContent = file_get_contents(__DIR__ . '/../data/data.csv');

        file_put_contents($path, $dataContent);

        $fileData = [
            'name' => 'data.csv',
            'full_path' => 'data.csv',
            'type' => 'text/csv',
            'tmp_name' => $path,
            'error' => 0,
        ];

        $app->run(true, $validCurrenciesInput, $validOutputCurrencyCode, $validSpecificCustomerVatNumber, $fileData);
    }
}
