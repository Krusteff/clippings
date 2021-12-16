<?php

namespace Tests\Unit\Application;

use Application\CustomerService;
use Domain\Customer;
use PHPUnit\Framework\TestCase;

/**
 * Class CustomerServiceTest
 *
 * @author Martin Krastev <martin.krastev@devision.bg>
 */
final class CustomerServiceTest extends TestCase
{
    public function testMethodCreate(): void
    {
        $customerService = new CustomerService();
        $customer = $customerService->create(['name' => 'John Doe', 'vatNumber' => '1234567']);

        $this->assertInstanceOf(Customer::class, $customer);
    }

    public function testMethodFindOrCreate(): void
    {
        $customerService = new CustomerService();
        $customerService->create(['name' => 'John Doe', 'vatNumber' => '1234567']);
        $found = $customerService->findOrCreate('John Doe', '1234567');
        $notFound = $customerService->findOrCreate('Max Mustermann', '7654321');

        $this->assertInstanceOf(Customer::class, $found);
        $this->assertInstanceOf(Customer::class, $notFound);
    }
}
