<?php

/**
 * Copyright 2014-2017 Christoph M. Becker
 *
 * This file is part of Browserupdate_XH.
 *
 * Browserupdate_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Browserupdate_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Browserupdate_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * The plugin version.
 */
define('BROWSERUPDATE_VERSION', '@BROWSERUPDATE_VERSION@');

(new Browserupdate\Controller)->dispatch();
