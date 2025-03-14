<?php

/**
 * Copyright (C) Christoph M. Becker
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
 * A request to a CMSimple_XH page
 *
 * This encapsulates the request super-globals `$_GET`, `$_POST` and `$_COOKIE`
 * as well as some CMSimple_XH specifics, such as `XH_ADM` and `$sl`.
 *
 * @final
 */
class Request
{
    /** @var Url */
    private $url;

    public static function current(): self
    {
        global $su;

        $that = new self();
        $that->url = new Url(
            (string) preg_replace('/index\.php$/', "", CMSIMPLE_URL),
            $su,
            $su ? array_slice($_GET, 1) : $_GET
        );
        return $that;
    }

    private function __construct()
    {
    }

    public function url(): Url
    {
        return $this->url;
    }

    /**
     * Retrieves an HTTP request header
     *
     * @since 1.1
     */
    public function header(string $key): ?string
    {
        $name = "HTTP_" . str_replace("-", "_", strtoupper($key));
        if (!isset($_SERVER[$name])) {
            return null;
        }
        return trim($_SERVER[$name]);
    }

    public function get(string $key): ?string
    {
        if (!isset($_GET[$key]) || !is_string($_GET[$key])) {
            return null;
        }
        return trim($_GET[$key]);
    }

    public function cookie(string $key): ?string
    {
        if (!isset($_COOKIE[$key]) || !is_string($_COOKIE[$key])) {
            return null;
        }
        return trim($_COOKIE[$key]);
    }

    public function post(string $key): ?string
    {
        if (!isset($_POST[$key]) || !is_string($_POST[$key])) {
            return null;
        }
        return trim($_POST[$key]);
    }

    public function time(): int
    {
        return (int) $_SERVER["REQUEST_TIME"];
    }

    public function admin(): bool
    {
        return defined("XH_ADM") && XH_ADM;
    }

    /**
     * The selected URL ($su)
     *
     * @since 1.2
     */
    public function selected(): string
    {
        global $su;

        return $su;
    }

    public function language(): string
    {
        global $sl;

        return $sl;
    }
}
