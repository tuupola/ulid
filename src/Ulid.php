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

    private $payload;
    private $timestamp;

    public function __construct($timestamp = null, $payload = null)
    {
        $this->payload = $payload;
        $this->timestamp = $timestamp;

        if (empty($payload)) {
            $this->payload = random_bytes(self::PAYLOAD_SIZE);
        }
        if (empty($timestamp)) {
            $this->timestamp = time();
        }
    }

    public static function generate()
    {
        return new self;
    }

    // public function generateRandom($length = 16, $characters = Base32::CROCKFORD)
    // {
    //     $string = "";
    //     $size = strlen($characters) - 1;
    //     for ($i = 0; $i < $length; ++$i) {
    //         $string .= $characters[random_int(0, $size)];
    //     }
    //     return $string;
    // }

    public function string()
    {
        return $this->encodeTimeStamp() . $this->encodePayload();
    }

    private function encodePayload()
    {
        $base32 = new Base32([
            "characters" => Base32::CROCKFORD,
            "padding" => false
        ]);

        $encoded = $base32->encode($this->payload);
        return \str_pad($encoded, self::PAYLOAD_ENCODED_SIZE, "0", STR_PAD_LEFT);
    }

    private function encodeTimeStamp()
    {
        $base32 = new Base32([
            "characters" => Base32::CROCKFORD,
            "padding" => false
        ]);

        $encoded = $base32->encode($this->timestamp);
        return \str_pad($encoded, self::TIMESTAMP_ENCODED_SIZE, "0", STR_PAD_LEFT);
    }
}
