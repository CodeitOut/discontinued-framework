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

/**
 * loader Container
 *
 * Contains auto loader for lazy loading.
 * Allows class fetching for loading classes from particular modules
 * Can be accessed by the string 'loader'.
 *
 * <code>
 * class test implements \sambhuti\core\IContainer {
 *     static $dependencies = array('loader');
 *     public $loader = null;
 *
 *     function __construct(\sambhuti\loader\ILoader $loader) {
 *         $this->loader = $loader;
 *     }
 * }
 * </code>
 *
 * @package    Sambhuti
 * @subpackage loader
 * @author     Piyush <piyush@cio.bz>
 * @license    http://www.gnu.org/licenses/gpl.html
 * @copyright  2012 Piyush
 */
class Container implements IContainer
{

    /**
     * App Paths
     *
     * Array of [namespace => Path] for lazy loading
     *
     * @var array $appPaths
     */
    protected $appPaths = [];

    /**
     * Root Paths
     *
     * Array of root paths for config
     *
     * @var array $rootPaths
     */
    protected $rootPaths = [];

    /**
     * Constructor
     *
     * Registers \sambhuti\loader\Container::get() as the autoload method
     *
     */
    public function __construct()
    {
        spl_autoload_register(array($this, 'get'));
    }

    /**
     * Get - Autoloader
     *
     * Autoloader to load the classes under all app paths
     *
     * @param string $class name of the class to be loaded
     *
     * @return bool true if found, false otherwise
     */
    public function get($class = null)
    {
        $array = explode('\\', $class);
        if (array_key_exists($array[0], $this->appPaths)) {
            $array[0] = $this->appPaths[$array[0]];
            $class = array_pop($array);

            array_push($array, $this->className($class));

            return $this->checkRequire(implode($array, DIRECTORY_SEPARATOR));
        }

        return false;
    }

    /**
     * Check Require
     *
     * Looks for a file and require_once it if it exists
     *
     * @param string $name partial path or class name to look for.
     *
     * @return bool true if found, false otherwise
     */
    public function checkRequire($name)
    {
        $fullPath = str_replace('\\', DIRECTORY_SEPARATOR, $name) . '.php';
        if (file_exists($fullPath)) {
            require_once($fullPath);

            return true;
        }
        return false;
    }

    /**
     * Fetch
     *
     * Looks for a class in all registered app paths and
     * returns the class name if matches else gives null
     *
     * @param string $class name of class to look for
     *
     * @return string|null string full class name if class exists else null
     */
    public function fetch($class)
    {
        if (class_exists($class)) {
            return $class;
        }
        $paths = array_reverse($this->appPaths);
        $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        foreach ($paths as $ns => $path) {
            if ($this->checkRequire($path . DIRECTORY_SEPARATOR . $classPath)) {
                return $ns . '\\' . $class;
            }
        }

        return null;
    }

    /**
     * Add App Path
     *
     * Add single app Path for loader
     *
     * @param string $namespace namespace of the path
     * @param string $path      the full path to the directory to be added
     *
     * @return \sambhuti\loader\IContainer instance
     */
    public function addApp($namespace, $path)
    {
        $path = rtrim($path, '/');
        $this->appPaths[$namespace] = $path . "/app";
        $this->rootPaths[$namespace] = $path;
        return $this;
    }

    /**
     * Get App Path
     *
     * Get single app path from loader
     *
     * @param string $key namespace of the app path
     *
     * @return string|bool string path if $key exists else boolean false
     */
    public function getApp($key)
    {
        if (!empty($this->appPaths[$key])) {
            return $this->appPaths[$key];
        }

        return false;
    }

    /**
     * Get Root Path
     *
     * Get single root path from loader
     *
     * @param string $key namespace of the root path
     *
     * @return string|bool string path if $key exists else boolean false
     */
    public function getRoot($key)
    {
        if (!empty($this->rootPaths[$key])) {
            return $this->rootPaths[$key];
        }

        return false;
    }

    /**
     * Get Root Paths
     *
     * Get all root paths from loader
     *
     * @return array all root paths.
     */
    public function getRoots()
    {
        return $this->rootPaths;
    }

    /**
     * Get App Paths
     *
     * Get all app paths from loader
     *
     * @return array all app paths.
     */
    public function getApps()
    {
        return $this->appPaths;
    }

    /**
     * Make Class name
     *
     * @param string $class
     *
     * @return string
     */
    public function className($class)
    {
        return str_replace("_", DIRECTORY_SEPARATOR, $class);
    }

    /**
     * Sleep
     *
     * @return array app path to be cached
     */
    public function __sleep()
    {
        return array('appPaths', 'rootPaths');
    }

}
