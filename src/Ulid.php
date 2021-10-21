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
 *   https://github.com/tuupola/uid
 *
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
