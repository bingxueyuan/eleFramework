<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/8/21
 */

namespace Lib\Curl;


abstract class AbstractCurl
{
    static private $ch = array();
    static private $url = null;
    static private $post = true;
    static private $ca = null;
    static private $userAgent = null;
    static private $data = null;
    static protected $serverEquipment = null;

    public function __construct()
    {
        self::$ca = \Config\LibConfig::CURL['CA'];

        self::$serverEquipment = \Config\LibConfig::CURL['SERVER_EQUIPMENT'];
    }

    public function userAgent(string $userAgent)
    {
        self::$userAgent = $userAgent;
    }

    public function isPost(bool $post)
    {
        self::$post = $post;
    }

    public function send(string $handle, string $url, array $data = null)
    {
        self::$url = $url;
        self::$data = $data;
        self::$ch[$handle] = curl_init();

        curl_setopt(self::$ch[$handle], CURLOPT_URL, self::$url);

        if (!is_null(self::$userAgent))
        {
            curl_setopt(self::$ch[$handle], CURLOPT_USERAGENT, self::$userAgent);
        }

        if (strstr(strtolower(self::$url), 'https'))
        {
            curl_setopt(self::$ch[$handle], CURLOPT_CAINFO, self::$ca);
            curl_setopt(self::$ch[$handle], CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt(self::$ch[$handle], CURLOPT_SSL_VERIFYHOST, 2);
        }

        if (self::$post)
        {
            curl_setopt(self::$ch[$handle], CURLOPT_POST, true);
            curl_setopt(self::$ch[$handle], CURLOPT_POSTFIELDS, self::$data);
        }

        curl_setopt(self::$ch[$handle], CURLOPT_CONNECTTIMEOUT_MS, 0);
        curl_setopt(self::$ch[$handle], CURLOPT_RETURNTRANSFER, true);

        $exec = curl_exec(self::$ch[$handle]);

        curl_close(self::$ch[$handle]);

        return $exec;
    }
}