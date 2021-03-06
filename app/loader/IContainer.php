<?php
/**
 * Sambhuti
 * Copyright (C) 2012-2013 Piyush
 *
 * License:
 * This file is part of Sambhuti (http://sambhuti.org)
 *
 * Sambhuti is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Sambhuti is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Sambhuti.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace sambhuti\loader;

use sambhuti\core;

/**
 * loader Container Interface
 *
 * Should allow class fetching for loading classes from particular modules
 * Should be used wherever using the dependency identifier 'loader'.
 *
 * @package    Sambhuti
 * @subpackage loader
 * @author     Piyush <piyush@cio.bz>
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2012 Piyush
 */
interface IContainer extends core\IContainer
{

    /**
     * Constructor
     */
    public function __construct();

    /**
     * Check Require
     *
     * Should look for a file and require_once it if it exists
     *
     * @param string $name partial path or class name to look for.
     *
     * @return bool true if found, false otherwise
     */
    public function checkRequire($name);

    /**
     * Fetch
     *
     * Should look for a class in all registered paths and
     * return the class name if matches else should give null
     *
     * @param string $class name of class to look for
     *
     * @return string|null string full class name if class exists else null
     */
    public function fetch($class);

    /**
     * Add App Path
     *
     * Add single app for loader
     *
     * @param string $namespace namespace for replacement
     * @param string $path      the full path to the app directory to be added
     *
     * @return \sambhuti\loader\IContainer instance
     */
    public function addApp($namespace, $path);

    /**
     * Get App
     *
     * Should return single path to app
     *
     * @param string $key namespace of the app path
     *
     * @return string|bool string path if $key exists else boolean false
     */
    public function getApp($key);

    /**
     * Get Root Path
     *
     * Get single root path from loader
     *
     * @param string $key namespace of the root path
     *
     * @return string|bool string path if $key exists else boolean false
     */
    public function getRoot($key);

    /**
     * Get Root Paths
     *
     * Get all root paths from loader
     *
     * @return array all root paths.
     */
    public function getRoots();

    /**
     * Get App Paths
     *
     * Get all app paths from loader
     *
     * @return array all app paths.
     */
    public function getApps();

}
