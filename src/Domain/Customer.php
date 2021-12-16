<?php

namespace Domain;

/**
 * Class Customer
 *
 * @author Martin Krastev <martin.krastev@devision.bg>
 */
class Customer extends BaseEntity
{
    private string $name;

    private string $vatNumber;

    /**
     * @var Document[]
     */
    private array $documents = [];

    /**
     * @param array $data
     *
     * @return $this
     */
    public function fromArray(array $data = []): Customer
    {
        if (key_exists('name', $data)) {
            $this->setName($data['name']);
        }

        if (key_exists('vatNumber', $data)) {
            $this->setVatNumber($data['vatNumber']);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Customer
     */
    public function setName(string $name): Customer
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getVatNumber(): string
    {
        return $this->vatNumber;
    }

    /**
     * @param string $vatNumber
     *
     * @return Customer
     */
    public function setVatNumber(string $vatNumber): Customer
    {
        $this->vatNumber = $vatNumber;

        return $this;
    }

    /**
     * @param Document $document
     *
     * @return $this
     */
    public function addDocument(Document $document): Customer
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * @return Document[]
     */
    public function getDocuments(): array
    {
        return $this->documents;
    }
}
