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
 * Abstract base class of model objects stored in a `DocumentStore`
 *
 * @see DocumentStore
 * @since 1.6
 */
abstract class Document
{
    /**
     * Creates a model object from its storage representation
     *
     * This method is called from {@see DocumentStore::retrieve()}
     * and {@see DocumentStore::update()} with the contents of the
     * file or an empty string if the file cannot be read.
     *
     * If the storage representation is valid, create and return the
     * model object.  Otherwise return `null`.
     *
     * @return ?static
     */
    abstract public static function fromString(string $contents);

    /**
     * Returns the storage representation of the model object
     *
     * This is method is called from {@see Document::save()},
     * and should return a string which could be passed to
     * {@see Document::fromString()} yielding the same object.
     */
    abstract public function toString(): string;
}
