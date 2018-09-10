<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/9/2
 */

namespace Model\Terminal\Process\WriteSensor;


class AbstractWriteSensor
{
    static protected $sqlHandle;

    static protected $dbFile = 'sensor.sdb';

    static protected $dbDir = ROOT . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'Terminal' . DIRECTORY_SEPARATOR . 'SensorData' . DIRECTORY_SEPARATOR;

    public function __construct()
    {

        self::initSqliteDB();
    }

    static private function initSqliteDB()
    {
        $createPhTable = 'id' . U32 . 'integer' . U32 . 'PRIMARY' . U32 . 'KEY' . U32 . 'autoincrement,' .
            'ph' . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            'mission' . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            'date' . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL';

        $createTemperatureTable = 'id' . U32 . 'integer' . U32 . 'PRIMARY' . U32 . 'KEY' . U32 . 'autoincrement,' .
            'temperature' . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            'mission' . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            'date' . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL';

        $createPressureTable = 'id' . U32 . 'integer' . U32 . 'PRIMARY' . U32 . 'KEY' . U32 . 'autoincrement,' .
            'pressure' . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            'mission' . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            'date' . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL';

        $createSpeedsTable = 'id' . U32 . 'integer' . U32 . 'PRIMARY' . U32 . 'KEY' . U32 . 'autoincrement,' .
            'speeds' . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            'mission' . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            'date' . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL';

        if (!file_exists(self::file()))
        {
            self::$sqlHandle = new \Lib\Pdo\Pdo(array
            (
                'DSN' => 'sqlite:' . self::file(),
                'OPTIONS' => array(\PDO::ATTR_PERSISTENT => true),
            ), false);

            self::$sqlHandle -> createTB('', 'ph', $createPhTable);
            self::$sqlHandle -> createTB('', 'temperature', $createTemperatureTable);
            self::$sqlHandle -> createTB('', 'pressure', $createPressureTable);
            self::$sqlHandle -> createTB('', 'speeds', $createSpeedsTable);
        }
        else
        {
            self::$sqlHandle = new \Lib\Pdo\Pdo(array
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

    public function addPhData(string $data)
    {
        self::$sqlHandle -> insert('', 'ph', array(
            'id' => null,
            'ph' => $data,
            'mission' => 'default_mission',
            'date' => date('Y-m-d H:i:s')
        ));
    }

    public function addTemperatureData($data)
    {
        self::$sqlHandle -> insert('', 'temperature', array(
            'id' => null,
            'temperature' => $data,
            'mission' => 'default_mission',
            'date' => date('Y-m-d H:i:s')
        ));
    }

    public function addPressureData($data)
    {
        self::$sqlHandle -> insert('', 'pressure', array(
            'id' => null,
            'pressure' => $data,
            'mission' => 'default_mission',
            'date' => date('Y-m-d H:i:s')
        ));
    }

    public function addSpeedsData($data)
    {
        self::$sqlHandle -> insert('', 'speeds', array(
            'id' => null,
            'speeds' => $data,
            'mission' => 'default_mission',
            'date' => date('Y-m-d H:i:s')
        ));
    }
}