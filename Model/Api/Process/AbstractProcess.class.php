<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/9/1
 */

namespace Model\Api\Process;


abstract class AbstractProcess
{
    static protected $processHandle = null;

    public function __construct()
    {
        self::$processHandle = new \Lib\Process\Process();
    }
}