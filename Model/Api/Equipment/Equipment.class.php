<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/9/3
 */

namespace Model\Api\Equipment;


class Equipment
{
    static private $dataFile = ROOT . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'Terminal' . DIRECTORY_SEPARATOR .
    'SensorData' . DIRECTORY_SEPARATOR . 'sensor.sdb';

    static private $sensorHandle = null;

    static public $sensorNum = 800;


    static public $var = null;

    public function __construct()
    {
        self::$var = \Kernel\Http::post('var');

        self::initSqliteDB();
    }

    static private function initSqliteDB()
    {
        self::$sensorHandle = new \Lib\Pdo\Pdo(array
        (
            'DSN' => 'sqlite:' . self::$dataFile,
        ), false);
    }

    public function allSensorRows()
    {
        return (int)self::$sensorHandle -> raw('SELECT' . U32 . 'COUNT(*)' . U32 . 'AS' . U32 . 'COUNT' . U32 . 'FROM' . U32 . self::$var, true)[0]['COUNT'];
    }

    public function getSensorData(string $var)
    {
        if (strtolower($var) === 'ph')
        {
            return self::$sensorHandle -> select('', 'ph', array(), array(), array(), array(
                ((int)\Kernel\Http::post('current_pages') - 1) * self::$sensorNum, self::$sensorNum
            ));
        }

        if (strtolower($var) === 'temperature')
        {
            return self::$sensorHandle -> select('', 'temperature', array(), array(), array(), array(
                ((int)\Kernel\Http::post('current_pages') - 1) * self::$sensorNum, self::$sensorNum
            ));
        }

        if (strtolower($var) === 'pressure')
        {
            return self::$sensorHandle -> select('', 'pressure', array(), array(), array(), array(
                ((int)\Kernel\Http::post('current_pages') - 1) * self::$sensorNum, self::$sensorNum
            ));
        }

        if (strtolower($var) === 'speeds')
        {
            return self::$sensorHandle -> select('', 'speeds', array(), array(), array(), array(
                ((int)\Kernel\Http::post('current_pages') - 1) * self::$sensorNum, self::$sensorNum
            ));
        }

        return array();
    }


}