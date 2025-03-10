<?php

/**
 * Copyright (c) Christoph M. Becker
 *
 * This file is part of Twocents_XH.
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

class FakeSystemChecker extends SystemChecker // @phpstan-ignore class.extendsFinalByPhpDoc
{
    /** @var array{version?:bool,extension?:bool,gd_freetype?:bool,gd_png?:bool,writability?:bool} */
    private $opts;

    /** @param array{version?:bool,extension?:bool,gd_freetype?:bool,gd_png?:bool,writability?:bool} $opts */
    public function __construct(array $opts = [])
    {
        $this->opts = $opts;
    }

    public function checkVersion(string $actual, string $minimum): bool
    {
        return $this->opts["version"] ?? false;
    }

    public function checkExtension(string $extension): bool
    {
        return $this->opts["extension"] ?? false;
    }

    public function checkGdFreetype(): bool
    {
        return $this->opts["gd_freetype"] ?? false;
    }

    public function checkGdPng(): bool
    {
        return $this->opts["gd_png"] ?? false;
    }

    public function checkWritability(string $path): bool
    {
        return $this->opts["writability"] ?? false;
    }
}
