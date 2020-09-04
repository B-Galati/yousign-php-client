<?php

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use YouSignClient\YouSignClient;

class FileTest extends TestCase {

    private YouSignClient $youSignClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->youSignClient = new YouSignClient( "", "d84447c122399e199c148fa57839888e");
    }

    public function testCreate(): void
    {
        $filename = "tests/file.pdf";
        $file = fread(fopen($filename, "r"), filesize($filename));

        $response = $this->youSignClient->file()->create(
            base64_encode($file),
            "Name",
        );
        $decoded = json_decode($response->getBody()->getContents(), true);

        Assert::assertArrayHasKey('id', $decoded);
        Assert::assertEquals('Name', $decoded['name']);
    }

    public function testGet(): void
    {
        $filename = "tests/file.pdf";
        $file = fread(fopen($filename, "r"), filesize($filename));

        $response = $this->youSignClient->file()->create(
            base64_encode($file),
            "Name",
        );
        $decoded = json_decode($response->getBody()->getContents(), true);

        $response = $this->youSignClient->file()->get(explode( '/', $decoded['id'])[2]);
        $decoded = json_decode($response->getBody()->getContents(), true);

        Assert::assertEquals('Name', $decoded['name']);
    }

    public function testDownload(): void
    {
        $filename = "tests/file.pdf";
        $file = fread(fopen($filename, "r"), filesize($filename));

        $response = $this->youSignClient->file()->create(
            base64_encode($file),
            "Name",
        );
        $decoded = json_decode($response->getBody()->getContents(), true);

        $response = $this->youSignClient->file()->download(explode( '/', $decoded['id'])[2]);
        $decoded = json_decode($response->getBody()->getContents(), true);

        Assert::assertEquals($file, base64_decode($decoded));
    }

    public function testUpdate(): void
    {
        $filename = "tests/file.pdf";
        $file1 = fread(fopen($filename, "r"), filesize($filename));

        $response = $this->youSignClient->file()->create(
            base64_encode($file1),
            "Name",
        );
        $decoded = json_decode($response->getBody()->getContents(), true);

        $filename = "tests/file2.pdf";
        $file2 = fread(fopen($filename, "r"), filesize($filename));

        $response = $this->youSignClient->file()->update(explode( '/', $decoded['id'])[2], base64_encode($file2));
        $decoded = json_decode($response->getBody()->getContents(), true);

        Assert::assertArrayHasKey('id', $decoded);
        Assert::assertEquals($response->getStatusCode(), 200);
    }

    public function testDelete(): void
    {
        $filename = "tests/file.pdf";
        $file1 = fread(fopen($filename, "r"), filesize($filename));

        $response = $this->youSignClient->file()->create(
            base64_encode($file1),
            "Name",
        );
        $decoded = json_decode($response->getBody()->getContents(), true);

        $response = $this->youSignClient->file()->delete(explode( '/', $decoded['id'])[2]);

        Assert::assertEquals($response->getStatusCode(), 204);
    }


}