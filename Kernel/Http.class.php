<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/8/21
 */

namespace Kernel {


    class Http
    {
        const TYPE_BOOL = 0;
        const TYPE_INT = 1;
        const TYPE_DOUBLE = 2;
        const TYPE_FLOAT = 3;
        const TYPE_STRING = 4;

        const METHOD_POST = 5;
        const METHOD_GET = 6;
        /**
         * 根据键名获取get值
         * @param string $key : 键名
         * @param int $type : 类型
         * @return bool|float|int|null|string
         */
        static public function get(string $key, int $type = self::TYPE_STRING)
        {
            return self::pick(self::httpReceive(self::METHOD_GET, $key), $type);
        }

        /**
         * 根据键名获取post值
         * @param string $key
         * @param int $type
         * @return bool|float|int|null|string
         */
        static public function post(string $key, int $type = self::TYPE_STRING)
        {
            return self::pick(self::httpReceive(self::METHOD_POST, $key), $type);
        }

        /**
         * 获取伪静态路径
         * 伪静态下返回路径, 非伪静态下返回false
         * @return array|false
         */
        static public function requestCatalog()
        {
            if (REWRITE)
            {
                $rawPath = explode('/', isset($_SERVER['PATH_INFO'])? $_SERVER['PATH_INFO'] : '/');

                $countRawPath = count($rawPath);

                $path = null;

                for ($i = 0; $i < $countRawPath; ++$i)
                {
                    if (!empty($rawPath[$i]))
                    {
                        $path[] = $rawPath[$i];
                    }
                }

                return empty($path)? array() : $path;
            }
            else
            {
                $log = new \Lib\Log\Log();

                $log -> debug(\Config\KernelConfig::ROUTER['REWRITE'], '', array());

                return false;
            }
        }

        /**
         * 强制转换类型
         * @param $rawValue : 原始键值
         * @param int $type : 类型
         * @return bool|float|int|null|string
         */
        static private function pick($rawValue, int $type = self::TYPE_STRING)
        {
            switch ($type)
            {
                case $type === self::TYPE_BOOL:
                    return (bool)$rawValue;
                    break;

                case $type === self::TYPE_INT:
                    return (int)$rawValue;
                    break;

                case $type === self::TYPE_DOUBLE:
                    return (double)$rawValue;
                    break;

                case $type === self::TYPE_FLOAT:
                    return (float)$rawValue;
                    break;

                case $type === self::TYPE_STRING:
                    return (string)$rawValue;
                    break;
            }

            return null;
        }

        /**
         * Http原始值接收
         * @param int $method
         * @param string $key
         * @return null
         */
        static private function httpReceive(int $method, string $key)
        {
            if ($method === self::METHOD_POST)
            {
                return isset($_POST[$key])? $_POST[$key] : null;
            }
            elseif ($method === self::METHOD_GET)
            {
                return isset($_GET[$key])? $_GET[$key] : null;
            }

            return null;
        }
    }
}