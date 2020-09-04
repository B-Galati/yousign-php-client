<?php

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use YouSignClient\YouSignClient;

class ProcedureTest extends TestCase {

    private YouSignClient $youSignClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->youSignClient = new YouSignClient( "", "d84447c122399e199c148fa57839888e");
    }

    public function testCreate(): void
    {
        $response = $this->youSignClient->procedure()->create(
            "Procedure Test",
            "Description",
            [],
        );
        $decoded = json_decode($response->getBody()->getContents(), true);

        Assert::assertArrayHasKey('id', $decoded);
        Assert::assertEquals('Procedure Test', $decoded['name']);
        Assert::assertEquals('Description', $decoded['description']);
    }

    public function testCreateAdvanced(): void
    {
        $response = $this->youSignClient->procedure()->create(
            "Procedure Test",
            "Description",
            [],
            false
        );
        $decoded = json_decode($response->getBody()->getContents(), true);

        Assert::assertArrayHasKey('id', $decoded);
        Assert::assertEquals('Procedure Test', $decoded['name']);
        Assert::assertEquals('Description', $decoded['description']);
        Assert::assertEquals('draft', $decoded['status']);
    }

    public function testGet(): void
    {
        $response = $this->youSignClient->procedure()->create(
            "Procedure Test",
            "Description",
            []
        );
        $decoded = json_decode($response->getBody()->getContents(), true);

        $response = $this->youSignClient->procedure()->get(explode( '/', $decoded['id'])[2]);
        $decoded = json_decode($response->getBody()->getContents(), true);

        Assert::assertEquals('Procedure Test', $decoded['name']);
        Assert::assertEquals('Description', $decoded['description']);
    }

    public function testAddMember(): void
    {
        $response = $this->youSignClient->procedure()->create(
            "Procedure Test",
            "Description",
            [],
            false
        );
        $decoded = json_decode($response->getBody()->getContents(), true);

        $response = $this->youSignClient->member()->addMember(
            'Jean',
            'Dupont',
            'jean.dupont@test.com',
            '0655500741',
            explode( '/', $decoded['id'])[2]
        );
        $decoded = json_decode($response->getBody()->getContents(), true);

        Assert::assertArrayHasKey('id', $decoded);
        Assert::assertEquals('jean.dupont@test.com', $decoded['email']);
        Assert::assertEquals('+33655500741', $decoded['phone']);
    }

}