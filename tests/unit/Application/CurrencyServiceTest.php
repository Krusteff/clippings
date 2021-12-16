<?php

namespace Tests\Unit\Application;

use Application\CurrencyService;
use Domain\Currency;
use PHPUnit\Framework\TestCase;

/**
 * Class CurrencyServiceTest
 *
 * @author Martin Krastev <martin.krastev@devision.bg>
 */
final class CurrencyServiceTest extends TestCase
{
    public function testMethodCreate(): void
    {
        $currencyService = new CurrencyService();
        $data = ['code' => 'SOME_CODE', 'multiplier' => 1];
        $currency = $currencyService->create($data);

        $this->assertInstanceOf(Currency::class, $currency);
        $this->assertEquals($data['code'], $currency->getCode());
        $this->assertEquals($data['multiplier'], $currency->getMultiplier());
    }

    public function testMethodGetDefault(): void
    {
        $currencyService = new CurrencyService();
        $this->assertNull($currencyService->getDefault());

        $currencyService->create(['code' => 'DEFAULT', 'multiplier' => 0.5]);
        $this->assertNull($currencyService->getDefault());

        $default = $currencyService->create(['code' => 'DEFAULT', 'multiplier' => 1.0]);

        $this->assertEquals($default, $currencyService->getDefault());
    }

    public function testMethodFindByCode(): void
    {
        $currencyService = new CurrencyService();
        $this->assertNull($currencyService->findByCode('NOT_EXISTING'));

        $existing = $currencyService->create(['code' => 'EXISTING', 'multiplier' => 1]);
        $currencyService->create(['code' => 'SECOND', 'multiplier' => 0.2]);

        $this->assertNull($currencyService->findByCode('NOT_EXISTING'));
        $this->assertEquals($existing, $currencyService->findByCode('EXISTING'));
    }
}
