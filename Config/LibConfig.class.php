<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/8/28
 */

namespace Config;


class LibConfig
{
    const LANGUAGE= 'ZH';
    const CACHE_DIR = ROOT . DIRECTORY_SEPARATOR . 'Cache' . DIRECTORY_SEPARATOR;
    const DATA_DIR = ROOT . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR;

    const CURL = array
    (

        'CA' => ROOT . DIRECTORY_SEPARATOR . 'Lib' . DIRECTORY_SEPARATOR . 'Curl' . DIRECTORY_SEPARATOR . 'ca.pem',

        'SERVER_EQUIPMENT' => array
        (
            'ADDRESS' => '192.168.1.250',
            'PORT' => 80,
            'TOKEN' => 1
        )
    );

    const LOG = array
    (

        'RECORD_LOCATION' => LibLanguage::LOG['RECORD_LOCATION'][self::LANGUAGE],

        'FILE' => LibLanguage::LOG['FILE'][self::LANGUAGE],

        'LINE' => LibLanguage::LOG['LINE'][self::LANGUAGE],

        'DIR' => self::CACHE_DIR . 'Log' . DIRECTORY_SEPARATOR,

        'SALT' => 'Hello!',

        'CURRENT' => 'SQLITE',

        'MEDIUM' => array
        (
            'SQLITE' => array(
                'FILE_PRE' => 'LOG.',  // 文件前缀
                'EXTENSION' => '.ldb',  // 扩展名
                'TABLE' => 'log',  // 表名
                'FREQUENCY' => 'Y_m',  // 库前缀
                'COLUMN_INDEX' => 'id',  // 索引名称
                'DATE_FORMAT' => 'Y-m-d' . U32 . 'H:i:s',  // 时间格式
                'COLUMN_DATE' => 'date',  // 时间
                'COLUMN_TITLE' => 'title',  // 标题
                'COLUMN_LEVEL' => 'level',  // 等级
                'COLUMN_MESSAGE' => 'message',  // 消息
                'COLUMN_JSON' => 'json',  // 消息
            ),
            'TEXT' => array(
                'FILE_PRE' => 'LOG.',  // 文件前缀
                'EXTENSION' => '.log',  // 扩展名
                'DATE_FORMAT' => 'Y-m-d' . U32 . 'H:i:s',  // 时间格式
                'FREQUENCY' => 'Y_m',  // 库前缀
            )
        )
    );

    const PDO = array
    (

        'MYSQL' => array
        (
            'DSN' => 'mysql:host=127.0.0.1',
            'DB' => 'elephant',
            'USERNAME' => 'root',
            'PASSWORD' => 'root',
            'OPTIONS' => array(\PDO::ATTR_PERSISTENT => true),
        )
    );

    const RESPONSE = array
    (
    );

    const VENDOR_LOADER = array
    (
        'VENDOR_DIR' => ROOT . DIRECTORY_SEPARATOR . 'Vendor' . DIRECTORY_SEPARATOR,
        'AUTOLOAD_FILE' => ROOT . DIRECTORY_SEPARATOR . 'Vendor' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php',
    );

    const PROCESS = array
    (
        'PDB_DIR' => ROOT . DIRECTORY_SEPARATOR . 'Lib' . DIRECTORY_SEPARATOR . 'Process' . DIRECTORY_SEPARATOR . 'PDB' . DIRECTORY_SEPARATOR,
        'PDB_FILE_PRE' => 'PDB_',
        'PDB_FILE_SALT' => 'PDB',
        'PDB_FILE_EXTENSION' => '.pdb',

        'PROCESS_CACHE_DIR' => ROOT . DIRECTORY_SEPARATOR . 'Lib' . DIRECTORY_SEPARATOR . 'Process' . DIRECTORY_SEPARATOR . 'CACHE' . DIRECTORY_SEPARATOR,
        'PROCESS_CACHE_EXTENSION' => '.TMP',

        'PROCESS_PYTHON' => 'python',
        'PROCESS_BIN_DIR' => ROOT . DIRECTORY_SEPARATOR . 'Lib' . DIRECTORY_SEPARATOR . 'Process' . DIRECTORY_SEPARATOR . 'BIN' . DIRECTORY_SEPARATOR,
        /**
         * 返回:
         * running | false
         */
        'PROCESS_PS' => 'ps.py',

        'PDB_TABLE' => 'process',
        'PDB_INDEX' => 'id',
        'PDB_PID' => 'pid',
        'PDB_STATE' => 'state',
        'PDB_SCRIPT' => 'script',
        'PDB_EXPLAIN' => 'process_explain',
        'PDB_DATE' => 'date',
    );
}