<?php

/**
 * Copyright 2017-2021 Christoph M. Becker
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
use org\bovigo\vfs\vfsStream;

class HtmlViewTest extends TestCase
{
    public function testText(): void
    {
        $subject = new HtmlView("", ["text" => "%s %s"]);
        $actual = $subject->text("text", "this & that", new HtmlString("<p>world</p>"));
        $this->assertSame("this &amp; that <p>world</p>", $actual);
    }

    /**
     * @dataProvider pluralData
     */
    public function testPlural(int $count, string $expected): void
    {
        $subject = new HtmlView("", [
            "ape_0" => "no apes",
            "ape_1" => "one ape",
            "ape_2_4" => "%d apes",
            "ape_5" => "many apes",
        ]);
        $actual = $subject->plural("ape", $count);
        $this->assertSame($expected, $actual);
    }

    /**
     * @return array{int,string}[]
     */
    public function pluralData(): array
    {
        return [
            [0, "no apes"],
            [1, "one ape"],
            [3, "3 apes"],
            [17, "many apes"],
        ];
    }

    public function testMessage(): void
    {
        $subject = new HtmlView("", ["message" => "hello %s"]);
        $actual = $subject->message("fail", "message", "world");
        $this->assertSame("<p class=\"xh_fail\">hello world</p>", $actual);
    }

    public function testRender(): void
    {
        vfsStream::setup("templates");
        file_put_contents(vfsStream::url("templates/test.php"), '<p><?=$foo?></p>');
        $subject = new HtmlView(vfsStream::url("templates"), []);
        $actual = $subject->render("test", ["foo" => "bar"]);
        $this->assertSame("<p>bar</p>", $actual);
    }
}
