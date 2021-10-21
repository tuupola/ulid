<?php

/*
 * This file is part of the ULID package
 *
 * Copyright (c) 2018 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   https://github.com/tuupola/ulid
 *
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
        //$this->assertEquals(1, $base32->decode($timestamp, true));

        $ulid = new Ulid(1469918176385);
        $timestamp  = NSA::invokeMethod($ulid, "encodeTimeStamp");
        $this->assertEquals(Ulid::TIMESTAMP_ENCODED_SIZE, strlen($timestamp));
        $this->assertEquals(1469918176385, $base32->decode($timestamp, true));

        /* Largest valid ULID encoded in Base32 is 7ZZZZZZZZZZZZZZZZZZZZZZZZZ, */
        /* which corresponds to an epoch time of 281474976710655 or 2 ^ 48 - 1 */
        $ulid = new Ulid((2 ** 48) - 1);
        $timestamp  = NSA::invokeMethod($ulid, "encodeTimeStamp");
        $this->assertEquals(Ulid::TIMESTAMP_ENCODED_SIZE, strlen($timestamp));
        $this->assertEquals((2 ** 48) - 1, $base32->decode($timestamp, true));
    }
}
