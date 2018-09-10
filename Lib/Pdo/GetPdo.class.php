<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/8/19
 */

namespace Lib\Pdo;


class GetPdo
{
    static private $instance = null;

    static private $config = null;

    function __construct(){}

    function __clone(){}

    static public function getInstance(bool $singleCase = true)
    {
        if ($singleCase === true)
        {
            if (self::$instance === null)
            {
                self::$instance = self::setPDO();
            }
        }
        else
        {
            self::$instance = self::setPDO();
        }

        return self::$instance;
    }

    static public function setConfig(array $config)
    {
        self::$config = $config;
    }

    static private function setPDO()
    {
        $dsn = self::$config['DSN'] ?? null;
        $username = self::$config['USERNAME'] ?? null;
        $password = self::$config['PASSWORD'] ?? null;
        $attribute = self::$config['OPTIONS'] ?? null;

        return new \PDO($dsn, $username, $password, $attribute);
    }
}