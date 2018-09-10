<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/8/26
 */

namespace Lib\Response;


class AbstractResponse
{
    static protected $message = null;
    static protected $code = null;
    static protected $data = null;

    public function __construct()
    {
    }

    public function printJson()
    {
        print_r(json_encode(array('code' => self::$code, 'message' => self::$message, 'data' => self::$data), JSON_UNESCAPED_UNICODE));
    }

    public function addMessage(string $message)
    {
        self::$message = (string)$message;
    }

    public function addCode(int $code)
    {
        self::$code = (int)$code;
    }

    public function addData(string $key, string $value)
    {
        self::$data[] = array(
            (string)$key => (string)$value
        );
    }

    public function send(bool $exit = false)
    {
        self::printJson();

        $exit? exit() : null;
    }
}