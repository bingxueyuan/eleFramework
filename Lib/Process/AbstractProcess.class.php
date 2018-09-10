<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/9/1
 */

namespace Lib\Process;


class AbstractProcess
{
    static protected $sqlHandle = null;

    static protected $pdbDir= null;
    static protected $pdbFilePre= null;
    static protected $pdbFileSalt= null;
    static protected $pdbFileExtension= null;

    static protected $process_python= null;
    static protected $processBinDir= null;
    static protected $processBin_PS= null;


    static protected $processCacheExtension= null;
    static protected $processCacheDir= null;


    static protected $processTable= null;
    static protected $processIndex= null;
    static protected $processPid= null;
    static protected $processState= null;
    static protected $processScript= null;
    static protected $processExplain= null;
    static protected $processDate= null;

    public function __construct()
    {
        self::$pdbDir = \Config\LibConfig::PROCESS['PDB_DIR'];
        self::$pdbFilePre = \Config\LibConfig::PROCESS['PDB_FILE_PRE'];
        self::$pdbFileSalt = \Config\LibConfig::PROCESS['PDB_FILE_SALT'];
        self::$pdbFileExtension = \Config\LibConfig::PROCESS['PDB_FILE_EXTENSION'];

        self::$process_python = \Config\LibConfig::PROCESS['PROCESS_PYTHON'];
        self::$processBinDir = \Config\LibConfig::PROCESS['PROCESS_BIN_DIR'];
        self::$processBin_PS = \Config\LibConfig::PROCESS['PROCESS_PS'];

        self::$processCacheExtension = \Config\LibConfig::PROCESS['PROCESS_CACHE_EXTENSION'];
        self::$processCacheDir = \Config\LibConfig::PROCESS['PROCESS_CACHE_DIR'];

        self::$processTable = \Config\LibConfig::PROCESS['PDB_TABLE'];
        self::$processIndex = \Config\LibConfig::PROCESS['PDB_INDEX'];
        self::$processPid = \Config\LibConfig::PROCESS['PDB_PID'];
        self::$processState = \Config\LibConfig::PROCESS['PDB_STATE'];
        self::$processScript = \Config\LibConfig::PROCESS['PDB_SCRIPT'];
        self::$processExplain = \Config\LibConfig::PROCESS['PDB_EXPLAIN'];
        self::$processDate = \Config\LibConfig::PROCESS['PDB_DATE'];

        self::initSqliteDB();
    }

    static private function initSqliteDB()
    {
        $createTable = self::$processIndex . U32 . 'integer' . U32 . 'PRIMARY' . U32 . 'KEY' . U32 . 'autoincrement,' .
            self::$processPid . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            self::$processState . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            self::$processScript . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            self::$processExplain . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            self::$processDate . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL';

        if (!file_exists(self::file()))
        {
            self::$sqlHandle = new \Lib\Pdo\Pdo(array
            (
                'DSN' => 'sqlite:' . self::file(),
                'OPTIONS' => array(\PDO::ATTR_PERSISTENT => true),
            ), false);

            self::$sqlHandle -> createTB('', self::$processTable, $createTable);
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
        file_exists(self::$pdbDir)? null : mkdir(self::$pdbDir, 0777, true);

        return self::$pdbDir . self::$pdbFilePre . base64_encode(md5(self::$pdbFileSalt)) . self::$pdbFileExtension;
    }

    public function allProcessRows()
    {
        return self::$sqlHandle -> raw('SELECT' . U32 . 'COUNT(*)' . U32 . 'AS' . U32 . 'COUNT' . U32 . 'FROM' . U32 . self::$processTable, true)[0]['COUNT'];

    }
}