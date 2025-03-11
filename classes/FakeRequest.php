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
 * A Request fake for automated testing
 *
 * @package Fakes
 */
final class FakeRequest extends Request // @phpstan-ignore class.extendsFinalByPhpDoc
{
    /** @var array{url:Url,get:array<string,mixed>,cookie?:array<string,mixed>,post?:array<string,mixed>,time?:int,admin?:bool,language?:string} */
    private $opts;

    /** @param array{url?:string,cookie?:array<string,mixed>,post?:array<string,mixed>,time?:int,admin?:bool,language?:string} $opts */
    public function __construct(array $opts = [])
    {
        $url = $opts["url"] ?? "http://example.com/";
        $parts = explode("?", $url, 2);
        if (count($parts) === 1) {
            $opts["url"] = new Url($parts[0], "", []);
            $opts["get"] = [];
        } else {
            $query = explode("&", $parts[1], 2);
            $su = $query[0];
            if (count($query) === 2) {
                $rest = $this->parseQuery($query[1]);
                $opts["url"] = new Url($parts[0], $su, $rest);
                $opts["get"] = $rest;
            } else {
                $opts["url"] = new Url($parts[0], $su, []);
                $opts["get"] = [];
            }
        }
        $this->opts = $opts;
    }

    /** @return array<string,string|array<string>> */
    private function parseQuery(string $query): array
    {
        parse_str($query, $result);
        $this->assertStringKeys($result);
        return $result;
    }

    /**
     * @param array<int|string,array<mixed>|string> $array
     * @phpstan-assert array<string,string|array<string>> $array
     */
    private function assertStringKeys(array $array): void
    {
        foreach ($array as $key => $value) {
            assert(is_string($key));
        }
    }

    public function url(): Url
    {
        return $this->opts["url"];
    }

    public function get(string $key): ?string
    {
        if (!isset($this->opts["get"][$key]) || !is_string($this->opts["get"][$key])) {
            return null;
        }
        return trim($this->opts["get"][$key]);
    }

    public function cookie(string $key): ?string
    {
        if (!isset($this->opts["cookie"][$key]) || !is_string($this->opts["cookie"][$key])) {
            return null;
        }
        return trim($this->opts["cookie"][$key]);
    }

    public function post(string $key): ?string
    {
        if (!isset($this->opts["post"][$key]) || !is_string($this->opts["post"][$key])) {
            return null;
        }
        return trim($this->opts["post"][$key]);
    }

    public function time(): int
    {
        return $this->opts["time"] ?? 1741617587;
    }

    public function admin(): bool
    {
        return $this->opts["admin"] ?? false;
    }

    public function language(): string
    {
        return $this->opts["language"] ?? "en";
    }
}
