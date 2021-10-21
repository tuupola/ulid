<?php

declare(strict_types = 1);

/*

Copyright (c) 2018-2021 Mika Tuupola

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

*/

/**
 * @see       https://github.com/tuupola/ulid
 * @license   https://www.opensource.org/licenses/mit-license.php
 */

namespace Tuupola\Ulid;

use PHPUnit\Framework\TestCase;
use Nyholm\NSA;
use Tuupola\Base32;
use Tuupola\Ulid;
use Tuupola\UlidProxy;

class UlidTest extends TestCase
{

    public function testShouldBeTrue()
    {
        $this->assertTrue(true);
    }

    public function testShouldDecodeAndEncodePayload()
    {
        $base32 = new Base32([
            "characters" => Base32::CROCKFORD,
            "padding" => false
        ]);

        $bytes = random_bytes(Ulid::PAYLOAD_SIZE);
        $ulid = new Ulid(null, $bytes);
        $payload  = NSA::invokeMethod($ulid, "encodePayload");
        $this->assertEquals(Ulid::PAYLOAD_ENCODED_SIZE, strlen($payload));
        $this->assertEquals($bytes, $base32->decode($payload));

        $bytes = hex2bin("00000000000000000000");
        $ulid = new Ulid(null, $bytes);
        $payload = NSA::invokeMethod($ulid, "encodePayload");
        $this->assertEquals(Ulid::PAYLOAD_ENCODED_SIZE, strlen($payload));
        $this->assertEquals($bytes, $base32->decode($payload));

        $bytes = hex2bin("FFFFFFFFFFFFFFFFFFFF");
        $ulid = new Ulid(null, $bytes);
        $payload = NSA::invokeMethod($ulid, "encodePayload");
        $this->assertEquals(Ulid::PAYLOAD_ENCODED_SIZE, strlen($payload));
        $this->assertEquals($bytes, $base32->decode($payload));
    }

    public function testShouldDecodeAndEncodeTimestamp()
    {
        $base32 = new Base32([
            "characters" => Base32::CROCKFORD,
            "padding" => false
        ]);

        $ulid = new Ulid(1);
        $timestamp  = NSA::invokeMethod($ulid, "encodeTimeStamp");
        $this->assertEquals(Ulid::TIMESTAMP_ENCODED_SIZE, strlen($timestamp));
        $this->assertEquals(1, $base32->decodeInteger($timestamp));

        $ulid = new Ulid(1469918176385);
        $timestamp  = NSA::invokeMethod($ulid, "encodeTimeStamp");
        $this->assertEquals(Ulid::TIMESTAMP_ENCODED_SIZE, strlen($timestamp));
        $this->assertEquals(1469918176385, $base32->decodeInteger($timestamp));

        /* Largest valid ULID encoded in Base32 is 7ZZZZZZZZZZZZZZZZZZZZZZZZZ, */
        /* which corresponds to an epoch time of 281474976710655 or 2 ^ 48 - 1 */
        $ulid = new Ulid((2 ** 48) - 1);
        $timestamp  = NSA::invokeMethod($ulid, "encodeTimeStamp");
        $this->assertEquals(Ulid::TIMESTAMP_ENCODED_SIZE, strlen($timestamp));
        $this->assertEquals((2 ** 48) - 1, $base32->decodeInteger($timestamp));
    }
}
