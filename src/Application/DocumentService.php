<?php

namespace Application;

use Domain\Document;

/**
 * Class DocumentService
 *
 * @author Martin Krastev <martin.krastev@devision.bg>
 */
class DocumentService
{
    /**
     * @var Document[]
     */
    private array $documents = [];

    /**
     * Creates Document and adds it to the "internal storage"
     *
     * @param array $data
     *
     * @return Document
     */
    public function create(array $data): Document
    {
        $document = new Document($data);

        $this->documents[] = $document;

        return $document;
    }

    /**
     * @return Document[]
     */
    public function getAll(): array
    {
        return $this->documents;
    }

    /**
     * @param string $number
     *
     * @return Document|null
     */
    public function findByNumber(string $number): ?Document
    {
        foreach ($this->documents as $document) {
            if ($document->getNumber() === $number) {
                return $document;
            }
        }

        return null;
    }
}
