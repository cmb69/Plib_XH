<?php

/**
 * Copyright 2021 Christoph M. Becker
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

use function count;
use function http_build_query;
use function is_string;
use function parse_url;
use function preg_match;
use function preg_replace;

use const PHP_QUERY_RFC3986;
use const PHP_URL_PATH;

final class Url
{
    /** @var string */
    private $base;

    /** @var string */
    private $page;

    /** @var array<string,mixed> */
    private $params;

    /** @param array<string,mixed> $params */
    public function __construct(string $base, string $page, array $params)
    {
        assert((bool) preg_match('/^http[s]?:\/\/.*\/$/', $base));
        $this->base = $base;
        $this->page = $page;
        $this->params = $params;
    }

    public function page(string $page): self
    {
        $url = clone $this;
        $url->page = $page;
        return $url;
    }

    public function with(string $name, string $value = ""): self
    {
        $url = clone $this;
        $url->params[$name] = $value;
        return $url;
    }

    public function absolute(): string
    {
        return $this->base . $this->suffix();
    }

    public function relative(): string
    {
        $relative = parse_url($this->base, PHP_URL_PATH);
        assert(is_string($relative));
        return $relative . $this->suffix();
    }

    private function suffix(): string
    {
        $query = $this->query();
        if ($query !== "") {
            return "?" . $query;
        }
        return "";
    }

    private function query(): string
    {
        $query = $this->page;
        if (count($this->params) > 0) {
            $rest = http_build_query($this->params, "", "&", PHP_QUERY_RFC3986);
            $query .= "&" . preg_replace('/=(?=&|$)/', "", $rest);
        }
        return $query;
    }
}
