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

use function array_map;
use function extract;
use function ob_get_clean;
use function ob_start;
use function sprintf;
use function XH_hsc;
use function XH_numberSuffix;

final class HtmlView
{
    /** @var string */
    private $templateDir;

    /** @var array<string,string> */
    private $lang;

    /**
     * @param array<string,string> $lang
     */
    public function __construct(string $templateDir, array $lang)
    {
        $this->templateDir = $templateDir;
        $this->lang = $lang;
    }

    /**
     * @param string|HtmlString $args
     */
    public function text(string $key, ...$args): string
    {
        $args = array_map([$this, "esc"], $args);
        return sprintf($this->esc($this->lang[$key]), ...$args);
    }

    /**
     * @param string|HtmlString $args
     */
    public function plural(string $key, int $count, ...$args): string
    {
        if ($count == 0) {
            $key .= '_0';
        } else {
            $key .= XH_numberSuffix($count);
        }
        $args = array_map([$this, "esc"], $args);
        return sprintf($this->esc($this->lang[$key]), $count, ...$args);
    }

    /**
     * @param string|HtmlString $args
     */
    public function message(string $type, string $key, ...$args): string
    {
        return sprintf('<p class="xh_%s">%s</p>', $type, $this->text($key, ...$args));
    }

    /**
     * @param array<string,mixed> $_data
     */
    public function render(string $_template, array $_data): string
    {
        extract($_data);
        ob_start();
        include "{$this->templateDir}/{$_template}.php";
        $result = ob_get_clean();
        assert($result !== false);
        return $result;
    }

    /**
     * @param string|HtmlString $value
     */
    public function esc($value): string
    {
        if ($value instanceof HtmlString) {
            return $value->asString();
        } else {
            return XH_hsc($value);
        }
    }
}
