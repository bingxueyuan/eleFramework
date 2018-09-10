<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/9/2
 */
namespace Model\Terminal\Process\WriteSensor;

file_put_contents(\Config\LibConfig::PROCESS['PROCESS_CACHE_DIR'] . 'WriteSensor' .
    \Config\LibConfig::PROCESS['PROCESS_CACHE_EXTENSION'], getmypid());


while (true)
{
    $WriteSensor = new WriteSensor();
    $WriteSensor -> run();
    sleep(1);
}