<?php

namespace Application;

use Domain\Customer;

/**
 * Class CustomerService
 *
 * @author Martin Krastev <martin.krastev@devision.bg>
 */
class CustomerService
{
    /**
     * @var Customer[]
     */
    private array $customers = [];

    /**
     * @param array $data
     *
     * @return Customer
     */
    public function create(array $data): Customer
    {
        $customer = new Customer($data);

        $this->customers[] = $customer;

        return $customer;
    }

    /**
     * @param string $name
     *
     * @return Customer
     */
    public function findOrCreate(string $name, string $vatNumber): Customer
    {
        if (($foundCustomer = $this->findByVatNumber($vatNumber)) === null) {
            return $this->create(['name' => $name, 'vatNumber' => $vatNumber]);
        }

        return $foundCustomer;
    }

    /**
     * @param string $vatNumber
     *
     * @return Customer|null
     */
    public function findByVatNumber(string $vatNumber): ?Customer
    {
        foreach ($this->customers as $customer) {
            if ($customer->getVatNumber() === $vatNumber) {
                return $customer;
            }
        }

        return null;
    }
}
