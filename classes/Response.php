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

class Response
{
    /** @var string */
    private $output = "";

    /** @var bool */
    private $forbidden = false;

    /** @var string|null */
    private $location = null;

    /** @var string|null */
    private $title = null;

    /** @var array{string,string,int}|null */
    private $cookie = null;

    /** @var string|null */
    private $contentType = null;

    /** @var string|null */
    private $attachment = null;

    /** @var int|null */
    private $length = null;

    public static function create(string $output = ""): self
    {
        $that = new self();
        $that->output = $output;
        return $that;
    }

    public static function forbid(string $output = ""): self
    {
        $that = new self();
        $that->forbidden = true;
        $that->output = $output;
        return $that;
    }

    public static function redirect(string $location): self
    {
        $that = new self();
        $that->location = $location;
        return $that;
    }

    private function __construct()
    {
    }

    /** @return string|never */
    public function __invoke()
    {
        global $sn, $title;

        if ($this->forbidden()) {
            $this->purgeOutputBuffers();
            http_response_code(403);
            echo $this->output();
            exit;
        }
        if ($this->cookie() !== null) {
            [$name, $value, $expires] = $this->cookie();
            setcookie($name, $value, $expires, $sn);
        }
        if ($this->location() !== null) {
            $this->purgeOutputBuffers();
            header("Location: " . $this->location(), true, 303);
            exit;
        }
        if ($this->attachment() !== null) {
            header("Content-Disposition: attachment; filename=\"" . $this->attachment() . "\"");
        }
        if ($this->length() !== null) {
            header("Content-Length: " . $this->length());
        }
        if ($this->contentType() !== null) {
            $this->purgeOutputBuffers();
            header("Content-Type: " . $this->contentType());
            echo $this->output();
            exit;
        }
        if ($this->title() !== null) {
            $title = $this->title();
        }
        return $this->output();
    }

    public function withTitle(string $title): self
    {
        $that = clone $this;
        $that->title = $title;
        return $that;
    }

    public function withCookie(string $name, string $value, int $expires): self
    {
        $that = clone $this;
        $that->cookie = [$name, $value, $expires];
        return $that;
    }

    public function withContentType(string $contentType): self
    {
        $that = clone $this;
        $that->contentType = $contentType;
        return $that;
    }

    public function withAttachment(string $attachment): self
    {
        $that = clone $this;
        $that->attachment = $attachment;
        return $that;
    }

    public function withLength(int $length): self
    {
        $that = clone $this;
        $that->length = $length;
        return $that;
    }

    public function output(): string
    {
        return $this->output;
    }

    public function location(): ?string
    {
        return $this->location;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function forbidden(): bool
    {
        return $this->forbidden;
    }

    /** @return array{string,string,int}|null */
    public function cookie(): ?array
    {
        return $this->cookie;
    }

    public function contentType(): ?string
    {
        return $this->contentType;
    }

    public function attachment(): ?string
    {
        return $this->attachment;
    }

    public function length(): ?int
    {
        return $this->length;
    }

    /** @return void */
    private function purgeOutputBuffers()
    {
        while (ob_get_level()) {
            ob_end_clean();
        }
    }
}
