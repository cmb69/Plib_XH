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

use PHPUnit\Framework\TestCase;

final class UrlTest extends TestCase
{
    public function testAbsolute(): void
    {
        $url = new Url("http://example.com/", "", ["a" => "b"]);
        $url = $url->page("foo")->without("a")->with("bar", "baz");
        $this->assertSame("http://example.com/?foo&bar=baz", $url->absolute());
    }

    public function testRelative(): void
    {
        $url = new Url("http://example.com/", "", []);
        $url = $url->page("foo")->with("bar", "baz");
        $this->assertSame("/?foo&bar=baz", $url->relative());
    }

    public function testWithoutPage(): void
    {
        $url = new Url("http://example.com/", "", []);
        $this->assertSame("/", $url->relative());
    }
}
