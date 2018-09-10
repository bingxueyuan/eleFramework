<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/9/3
 */

namespace Model\Api\Equipment;


class EquipmentInfo
{
    static protected $equipmentHandle = null;

    static protected $dbDir = ROOT . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'Api' . DIRECTORY_SEPARATOR .
    'EquipmentData' . DIRECTORY_SEPARATOR;

    static protected $dbFile = 'equipment.edb';


    static public $var = null;

    public function __construct()
    {
        self::$var = \Kernel\Http::post('var');

        self::initSqliteDB();
    }

    static private function initSqliteDB()
    {
        $createPhTable = 'id' . U32 . 'integer' . U32 . 'PRIMARY' . U32 . 'KEY' . U32 . 'autoincrement,' .
            'sensor_id' . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            'sensor_name' . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            'line' . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            'min_gt' . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            'min_lt' . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            'normal_gt' . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            'normal_lt' . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            'max_gt' . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            'max_lt' . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL';

        if (!file_exists(self::file()))
        {
            self::$equipmentHandle = new \Lib\Pdo\Pdo(array
            (
                'DSN' => 'sqlite:' . self::file(),
            ), false);

            self::$equipmentHandle -> createTB('', 'equipment', $createPhTable);
        }
        else
        {
            self::$equipmentHandle = new \Lib\Pdo\Pdo(array
            (
                'DSN' => 'sqlite:' . self::file(),
                'OPTIONS' => array(\PDO::ATTR_PERSISTENT => true),
            ), false);
        }
    }

    static protected function file()
    {
        file_exists(self::$dbDir)? null : mkdir(self::$dbDir, 0777, true);

        return self::$dbDir . self::$dbFile;
    }

    public function updateEquipment(array $data)
    {
        var_dump(self::$equipmentHandle -> update('', 'equipment', array(
            'sensor_id' => $data['sensor_id'],
            'sensor_name' => $data['sensor_name'],
            'line' => $data['line'],
            'min_gt' => $data['min_gt'],
            'min_lt' => $data['min_lt'],
            'normal_gt' => $data['normal_gt'],
            'normal_lt' => $data['normal_lt'],
            'max_gt' => $data['max_gt'],
            'max_lt' => $data['max_lt'],
        ), array(
            'sensor_id[=]' => self::$var
        )));
    }

    public function getEquipment(string $var)
    {
        return self::$equipmentHandle -> select('', 'equipment', array(), array(
            'sensor_id[=]' => $var
        ), array(), array());
    }

    public function getEquipmentList()
    {
        return self::$equipmentHandle -> select('', 'equipment', array(), array(), array(), array());
    }

}