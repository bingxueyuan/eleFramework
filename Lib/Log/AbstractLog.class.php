<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/8/20
 */

namespace Lib\Log;


abstract class AbstractLog
{
    const EMERGENCY = 'EMERGENCY';
    const ALERT     = 'ALERT';
    const CRITICAL  = 'CRITICAL';
    const ERROR     = 'ERROR';
    const WARNING   = 'WARNING';
    const NOTICE    = 'NOTICE';
    const INFO      = 'INFO';
    const DEBUG     = 'DEBUG';

    static protected $dir= null;
    static protected $salt= null;
    static protected $medium = null;
    static protected $current = null;
    static protected $sqliteHandle = null;

    static protected $responseHandle = null;

    static protected $languageRecordLocation = null;
    static protected $languageFile = null;
    static protected $languageLine = null;

    public function __construct()
    {
        self::$dir = \Config\LibConfig::LOG['DIR'];
        self::$salt = \Config\LibConfig::LOG['SALT'];
        self::$current = \Config\LibConfig::LOG['CURRENT'];
        self::$medium = \Config\LibConfig::LOG['MEDIUM'];

        self::$languageRecordLocation = \Config\LibConfig::LOG['RECORD_LOCATION'];
        self::$languageFile = \Config\LibConfig::LOG['FILE'];
        self::$languageLine = \Config\LibConfig::LOG['LINE'];
    }


    /**
     * 返回日志文件路径
     * @return string
     */
    static protected function file()
    {
        file_exists(self::$dir)? null : mkdir(self::$dir, 0777, true);

        return self::$dir . self::$medium[self::$current]['FILE_PRE'] .
            date(self::$medium[self::$current]['FREQUENCY']) . '_' .
            base64_encode(md5(self::$medium[self::$current]['FREQUENCY'] . md5(self::$salt))) .
            self::$medium[self::$current]['EXTENSION'];
    }


    /**
     * 记录日志
     * @param string $title
     * @param string $level
     * @param string $message
     * @param string $json
     */
    static protected function write(string $title, string $level, string $message, string $json)
    {
        switch (self::$current)
        {
            case 'SQLITE':
                self::sqlite(date(self::$medium[self::$current]['DATE_FORMAT']), $title, $level, $message, $json);
                break;

            case 'TEXT':
                self::text(date(self::$medium[self::$current]['DATE_FORMAT']), $title, $level, $message, $json);
                break;

        }
    }

    /**
     * 文本日志
     * @param string $date
     * @param string $title
     * @param string $level
     * @param string $message
     * @param string $json
     */
    static private function text(string $date, string $title, string $level, string $message, string $json = '{}')
    {
        $data = '[' . $date . ']' . U32 . $title . '.' . $level . ':' . U32 . $message . U32 . $json . PHP_EOL;

        file_put_contents(self::file(), $data, FILE_APPEND|LOCK_EX);
    }

    /**
     * 初始化SQL数据库
     * @return null
     */
    static private function initSqliteDB()
    {
        $createTable = self::$medium[self::$current]['COLUMN_INDEX'] . U32 . 'integer' . U32 . 'PRIMARY' . U32 . 'KEY' . U32 . 'autoincrement,' .
            self::$medium[self::$current]['COLUMN_DATE'] . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            self::$medium[self::$current]['COLUMN_TITLE'] . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            self::$medium[self::$current]['COLUMN_LEVEL'] . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            self::$medium[self::$current]['COLUMN_MESSAGE'] . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL,' .
            self::$medium[self::$current]['COLUMN_JSON'] . U32 . 'VARCHAR' . U32 . 'NOT' . U32 . 'NULL';

        if (!file_exists(self::file()))
        {
            self::$sqliteHandle = new \Lib\Pdo\Pdo(array
            (
                'DSN' => 'sqlite:' . self::file(),
                'OPTIONS' => array(\PDO::ATTR_PERSISTENT => true),
            ), false);

            self::$sqliteHandle -> createTB('', self::$medium[self::$current]['TABLE'], $createTable);
        }
        else
        {
            self::$sqliteHandle = new \Lib\Pdo\Pdo(array
            (
                'DSN' => 'sqlite:' . self::file(),
                'OPTIONS' => array(\PDO::ATTR_PERSISTENT => true),
            ), false);
        }

        return null;
    }

    /**
     * SQL日志
     * @param string $date
     * @param string $title
     * @param string $level
     * @param string $message
     * @param string $json
     * @return null
     */
    static private function sqlite(string $date, string $title, string $level, string $message, string $json = '{}')
    {
        self::initSqliteDB();

        self::$sqliteHandle -> insert('', self::$medium[self::$current]['TABLE'], array(
            self::$medium[self::$current]['COLUMN_INDEX'] => null,
            self::$medium[self::$current]['COLUMN_DATE'] => $date,
            self::$medium[self::$current]['COLUMN_TITLE'] => $title,
            self::$medium[self::$current]['COLUMN_LEVEL'] => $level,
            self::$medium[self::$current]['COLUMN_MESSAGE'] => $message,
            self::$medium[self::$current]['COLUMN_JSON'] => $json,
        ));

        return null;
    }
}