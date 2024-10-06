<?php

namespace App\Utils;

class ShortLinkGenerator
{

    private const CHARACTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    private const LENGTH = 6;

    private function __construct()
    {

    }

    public static function generateShortLink(): string
    {
        $charactersLength = strlen(self::CHARACTERS);
        $randomString = '';

        for ($i = 0; $i < self::LENGTH; $i++) {
            $randomIndex  = random_int(0, $charactersLength - 1);
            $randomString .= substr(self::CHARACTERS, $randomIndex, 1);
        }
        return $randomString;
    }
}
