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
 * Utility for including JavaScript
 *
 * @final
 * @since 1.12
 */
class JavaScript
{
    /** @var array<string,true> */
    private static $includedScripts = [];

    /**
     * Loads the given local script
     *
     * Uses the minified version (.min.js) if available and newer than the plain
     * version (.js).  The query parameter `ts` is added to the `src` URL,
     * so the browser will not retrieve out-dated JavaScript from its cache.
     *
     * Writes the respective `<script>` element to `$bjs` *once*,
     * even when called multiple times with the same argument.  The script is
     * loaded as classic script (i.e. default `type`), and with the `defer`
     * attribute set.
     */
    public function include(string $filenameWithoutExt): void
    {
        global $bjs;
        if (!isset(self::$includedScripts[$filenameWithoutExt])) {
            $filename = $filenameWithoutExt . ".min.js";
            $filenameAlt = $filenameWithoutExt . ".js";
            if (!is_file($filename) || filemtime($filenameAlt) > filemtime($filename)) {
                $filename = $filenameAlt;
            }
            $ts = filemtime($filename);
            $url = XH_hsc($filename . "?ts=$ts");
            $bjs .= "<script src=\"$url\" defer></script>\n";
            self::$includedScripts[$filenameWithoutExt] = true;
        }
    }
}
