<?php

namespace Plib;

use PHPUnit\Framework\TestCase;

class CodecTest extends TestCase
{
    /** @dataProvider dataForEncodeBase64url*/
    public function testEncodeBase64url(string $string, string $expected): void
    {
        $this->assertSame($expected, Codec::encodeBase64url($string));
    }

    public function dataForEncodeBase64url(): array
    {
        return [
            ["Many hands make light work.", "TWFueSBoYW5kcyBtYWtlIGxpZ2h0IHdvcmsu"],
            [hex2bin("fb315671cd6127f4fef46e86ba28ef"), "-zFWcc1hJ_T-9G6Guijv"],
            ["a", "YQ"],
            ["ab", "YWI"],
        ];
    }

    /** @dataProvider dataForDecodeBase64url */
    public function testDecodeBase64url(string $string, ?string $expected): void
    {
        $this->assertSame($expected, Codec::decodeBase64url($string));
    }

    public function dataForDecodeBase64url(): array
    {
        return [
            ["TWFueSBoYW5kcyBtYWtlIGxpZ2h0IHdvcmsu", "Many hands make light work."],
            ["-zFWcc1hJ_T-9G6Guijv", hex2bin("fb315671cd6127f4fef46e86ba28ef")],
            ["YQ", "a"],
            ["YWI", "ab"],
            ["\\", null],
        ];
    }
}
