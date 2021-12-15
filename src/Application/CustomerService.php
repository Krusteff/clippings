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
    public function findByNameOrCreate(string $name): Customer
    {
        if (($foundCustomer = $this->findByName($name)) === null) {
            return $this->create(['name' => $name]);
        }

        return $foundCustomer;
    }

    /**
     * @param string $name
     *
     * @return Customer|null
     */
    public function findByName(string $name): ?Customer
    {
        foreach ($this->customers as $customer) {
            if ($customer->getName() === $name) {
                return $customer;
            }
        }

        return null;
    }
}
