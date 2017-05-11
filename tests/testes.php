<?php


use PHPUnit\Framework\TestCase;

final class Testes extends TestCase
{
    public function testEnderecoValido()
    {
        $this->assertInstanceOf(
            ResizePhotos::class,
            ResizePhotos::set_endereco('http://54.152.221.29/images.json')
        );
    }

    public function testEnderecoNaoPodeSerNULL()
    {
        $this->expectException(InvalidArgumentException::class);

        ResizePhotos::set_endereco('');
    }

    public function testEnderecoJSONInvalido()
    {
        $this->expectException(InvalidArgumentException::class);

        ResizePhotos::set_endereco('http://www.google.com');
    }
}