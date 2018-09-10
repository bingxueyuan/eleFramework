<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/9/1
 */
namespace Model\Api\Process;


header("Access-Control-Allow-Origin: *");

$process = new Process();

if (Process::$getProcess === '1')
{
    $process -> getProcess();
}

if (Process::$getAddProcess === '1')
{
    $process -> addProcess();
}

if (Process::$getRemoveProcess === '1')
{
    $process -> removeProcess();
}

if (Process::$getStartProcess === '1')
{
    $process -> startProcess();
}

if (Process::$getPing === '1')
{
    $process -> ping();
}