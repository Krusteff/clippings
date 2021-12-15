<?php

namespace Domain;

/**
 * Class Customer
 *
 * @author Martin Krastev <martin.krastev@devision.bg>
 */
class Customer
{
    private string $name;

    /**
     * @var Document[]
     */
    private array $documents = [];

    /**
     * Customer constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        return $this->fromArray($data);
    }

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
