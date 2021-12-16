<?php

namespace Domain;

/**
 * Class Document
 *
 * @author Martin Krastev <martin.krastev@devision.bg>
 */
class Document extends BaseEntity
{
    private string $number;

    private float $total;

    private Customer $customer;

    private ?Document $parent = null;

    private DocumentType $type;

    private Currency $currency;

    /**
     * @param array $data
     *
     * @return $this
     */
    public function fromArray(array $data = []): Document
    {
        if (key_exists('number', $data)) {
            $this->setNumber($data['number']);
        }

        if (key_exists('total', $data)) {
            $this->setTotal($data['total']);
        }

        if (key_exists('customer', $data) && $data['customer'] instanceof Customer) {
            $this->setCustomer($data['customer']);
        }

        if (key_exists('parent', $data) && $data['parent'] instanceof Document) {
            $this->setParent($data['parent']);
        }

        if (key_exists('type', $data) && $data['type'] instanceof DocumentType) {
            $this->setType($data['type']);
        }

        if (key_exists('currency', $data) && $data['currency'] instanceof Currency) {
            $this->setCurrency($data['currency']);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param string $number
     *
     * @return Document
     */
    public function setNumber(string $number): Document
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * @param float $total
     *
     * @return Document
     */
    public function setTotal(float $total): Document
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     *
     * @return Document
     */
    public function setCustomer(Customer $customer): Document
    {
        $this->customer = $customer;
        $this->customer->addDocument($this);

        return $this;
    }

    /**
     * @return Document|null
     */
    public function getParent(): ?Document
    {
        return $this->parent;
    }

    /**
     * @param Document|null $parent
     *
     * @return Document
     */
    public function setParent(?Document $parent): Document
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return DocumentType
     */
    public function getType(): DocumentType
    {
        return $this->type;
    }

    /**
     * @param DocumentType $type
     *
     * @return Document
     */
    public function setType(DocumentType $type): Document
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @param Currency $currency
     *
     * @return Document
     */
    public function setCurrency(Currency $currency): Document
    {
        $this->currency = $currency;

        return $this;
    }
}
