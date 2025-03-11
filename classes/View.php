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
 * Rendering of HTML and plain text output
 *
 * Generally, `View` encapsulates `$plugin_tx` of the plugin.
 * In simple cases, it can be used to compose a message (`XH_message()`).
 * In more complex cases, it it used to render a view template, to which
 * arbitrary values can be passed.
 *
 * @final
 */
class View
{
    /** @var string */
    private $templateFolder;

    /** @var array<string,string> */
    private $text;

    /** @param array<string,string> $text */
    public function __construct(string $templateFolder, array $text)
    {
        $this->templateFolder = $templateFolder;
        $this->text = $text;
    }

    /** @param scalar $args */
    public function message(string $type, string $key, ...$args): string
    {
        return XH_message($type, $this->text[$key], ...$args) . "\n";
    }

    /** @param scalar $args */
    public function pmessage(string $type, string $key, int $count, ...$args): string
    {
        $suffix = $count === 0 ? "_0" : XH_numberSuffix($count);
        return XH_message($type, $this->text[$key . $suffix], $count, ...$args) . "\n";
    }

    /** @param scalar $args */
    public function text(string $key, ...$args): string
    {
        return $this->esc(sprintf($this->text[$key], ...$args));
    }

    /** @param scalar $args */
    public function plural(string $key, int $count, ...$args): string
    {
        $suffix = $count === 0 ? "_0" : XH_numberSuffix($count);
        return $this->esc(sprintf($this->text[$key . $suffix], $count, ...$args));
    }

    /** @param scalar $args */
    public function plain(string $key, ...$args): string
    {
        return sprintf($this->text[$key], ...$args);
    }

    /** @param mixed $value */
    public function json($value): string
    {
        return (string) json_encode($value, JSON_HEX_APOS | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /** @param array<string,mixed> $_data */
    public function render(string $_template, array $_data): string
    {
        extract($_data);
        ob_start();
        include $this->templateFolder . $_template . ".php";
        return (string) ob_get_clean();
    }

    public function esc(string $string): string
    {
        return XH_hsc($string);
    }

    public function raw(string $string): string
    {
        return $string;
    }
}
