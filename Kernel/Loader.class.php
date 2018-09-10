<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/8/19
 */

namespace Kernel {


    class Loader
    {
        static private $requireFile = null;

        static public function autoload():void
        {
            spl_autoload_register('self::autoloadFunction');
        }

        static private function getLoadFile(string $class)
        {
            /**
             * Example:
             * \test\myAutoload\test()
             *
             * Return:
             * [0] => test
             * [1] => myAutoload
             * [2] => test
             */
            $classArray = explode('\\', $class);

            /**
             * $classArray:
             * [0] => test
             * [1] => myAutoload
             *
             * Return:
             * test
             */
            $className = array_pop($classArray);

            /**
             * Return:
             * test\myAutoload
             */
            $namespace = implode(DIRECTORY_SEPARATOR, $classArray);

            /**
             * Return:
             * no namespace -> /
             * namespace -> /test/myAutoload/
             */
            $dir = strlen($namespace) === 0 ? DIRECTORY_SEPARATOR : DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR;

            $requireFile = ROOT . $dir . ucfirst($className) . '.class.php';

            self::$requireFile = $requireFile;
        }

        static private function autoloadFunction(string $class):void
        {
            self::getLoadFile($class);

            require_once self::$requireFile;
        }
    }
}

