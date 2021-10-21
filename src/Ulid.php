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

namespace Tuupola;

class Ulid
{
    const PAYLOAD_SIZE = 10;
    const PAYLOAD_ENCODED_SIZE = 16;
    const TIMESTAMP_SIZE = 6;
    const TIMESTAMP_ENCODED_SIZE = 10;

    /**
     * @var string
     */
    private $payload;

    /**
     * @var int
     */
    private $timestamp;

    public function __construct(int $timestamp_ms = null, string $payload = null)
    {
        if (empty($payload)) {
            $this->payload = random_bytes(self::PAYLOAD_SIZE);
        } else {
            $this->payload = $payload;
        }

        if (empty($timestamp_ms)) {
            $this->timestamp = (int) (microtime(true) * 1000);
        } else {
            $this->timestamp = $timestamp_ms;
        }
    }

    public static function generate(): self
    {
        return new self;
    }

    public function string(): string
    {
        return $this->encodeTimeStamp() . $this->encodePayload();
    }

    private function encodePayload(): string
    {
        $base32 = new Base32([
            "characters" => Base32::CROCKFORD,
            "padding" => false
        ]);

        $encoded = $base32->encode($this->payload);
        return \str_pad($encoded, self::PAYLOAD_ENCODED_SIZE, "0", STR_PAD_LEFT);
    }

    private function encodeTimeStamp(): string
    {
        $base32 = new Base32([
            "characters" => Base32::CROCKFORD,
            "padding" => false
        ]);

        $encoded = $base32->encodeInteger($this->timestamp);
        return \str_pad($encoded, self::TIMESTAMP_ENCODED_SIZE, "0", STR_PAD_LEFT);
    }

    public function payload(): string
    {
        return $this->payload;
    }

    public function timestamp(): int
    {
        return $this->timestamp;
    }

    public function unixtime(): int
    {
        return ($this->timestamp / 1000);
    }

    public function __toString()
    {
        return $this->string();
    }
}
