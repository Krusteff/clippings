<?php

namespace Tests\Unit\Application;

use Application\DocumentService;
use Domain\Currency;
use Domain\Customer;
use Domain\Document;
use Domain\DocumentType;
use PHPUnit\Framework\TestCase;

/**
 * Class DocumentServiceTest
 *
 * @author Martin Krastev <martin.krastev@devision.bg>
 */
final class DocumentServiceTest extends TestCase
{
    public function testMethodCreate(): void
    {
        $documentService = new DocumentService();
        $document = $this->createDocument($documentService);

        $this->assertInstanceOf(Document::class, $document);
    }

    public function testMethodGetAll(): void
    {
        /**
         * 1. Init DocumentService
         * 2. Test `getAll` method will return empty array as expected
         * 3. Create document
         * 4. Test `getAll` method will return not empty array
         */
        $documentService = new DocumentService();
        $all = $documentService->getAll();

        $this->assertIsArray($all);
        $this->assertEmpty($all);

        $this->createDocument($documentService, ['total' => 200]);

        $allAfterAddedDocument = $documentService->getAll();

        $this->assertNotEmpty($allAfterAddedDocument);
        $this->assertTrue(count($allAfterAddedDocument) === 1);
    }

    public function testMethodFindByNumber(): void
    {
        $documentService = new DocumentService();
        $notFound = $documentService->findByNumber('NOT_EXISTING');
        $this->assertNull($notFound);

        $this->createDocument($documentService, ['number' => '000001']);
        $notFound = $documentService->findByNumber('NOT_EXISTING');
        $this->createDocument($documentService, ['number' => '000002']);
        $found = $documentService->findByNumber('000001');

        $this->assertNull($notFound);
        $this->assertEquals('000001', $found->getNumber());
    }

    /**
     * @param DocumentService $documentService
     * @param array $customData
     *
     * @return Document
     */
    private function createDocument(DocumentService $documentService, array $customData = []): Document
    {
        $customerMock = $this->getMockBuilder(Customer::class)->getMock();
        $currencyMock = $this->getMockBuilder(Currency::class)->getMock();
        $parentMock = $this->getMockBuilder(Document::class)->getMock();

        $data = array_merge([
            'number' => 'some number',
            'total' => 100,
            'customer' => $customerMock,
            'parent' => $parentMock,
            'type' => DocumentType::DEBIT,
            'currency' => $currencyMock,
        ], $customData);

        return $documentService->create($data);
    }
}
