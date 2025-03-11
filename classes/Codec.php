<?php

/**
 * Copyright (c) Christoph M. Becker
 *
 * This file is part of Plib_XH.
 *
 * Plib_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Plib_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Plib_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Plib;

/**
 * Text encoding and decoding helpers
 *
 * Not to be confused with character encodings.
 *
 * While PHP already offers several such functions, e.g. `base64_encode()`,
 * `urlencode()`, a couple of useful ones are not available.
 * These may be implemented as static methods in this class.
 */
class Codec
{
    public static function encodeBase64url(string $string): string
    {
        return str_replace(["+", "/"], ["-", "_"], rtrim(base64_encode($string), "="));
    }

    public static function decodeBase64url(string $string): ?string
    {
        $res = base64_decode(str_replace(["-", "_"], ["+", "/"], $string), true);
        if ($res === false) {
            return null;
        }
        return $res;
    }
}
