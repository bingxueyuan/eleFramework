<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/9/2
 */


echo ROOT . 'Model/Terminal/SensorData/sensor.sdb';

$sqlHandle = new \Lib\Pdo\Pdo(array
(
    'DSN' => 'sqlite:' . ROOT . DIRECTORY_SEPARATOR . 'Model/Terminal/SensorData/sensor.sdb',
    'OPTIONS' => array(\PDO::ATTR_PERSISTENT => true),
), false);

$r = $sqlHandle -> raw('SELECT COUNT(*) AS COUNT FROM ph')[0]['COUNT'];

var_dump($r);