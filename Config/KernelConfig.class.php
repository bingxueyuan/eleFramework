<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/8/28
 */

namespace Config;


class KernelConfig
{
    const LANGUAGE= 'ZH';
    const CACHE_DIR = ROOT . DIRECTORY_SEPARATOR . 'Cache' . DIRECTORY_SEPARATOR;
    const DATA_DIR = ROOT . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR;
    const MODEL_DIR = ROOT . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR;

    const ROUTER = array
    (
        'TABLE_EMPTY' => KernelLanguage::ROUTER['TABLE_EMPTY'][self::LANGUAGE],

        'TABLE_MODEL_LOAD_ERROR' => KernelLanguage::ROUTER['TABLE_MODEL_LOAD_ERROR'][self::LANGUAGE],

        'TABLE_MODEL_LOAD_SUCCESS' => KernelLanguage::ROUTER['TABLE_MODEL_LOAD_SUCCESS'][self::LANGUAGE],

        'TABLE_MODEL_CONFIG_ERROR' => KernelLanguage::ROUTER['TABLE_MODEL_CONFIG_ERROR'][self::LANGUAGE],

        'REWRITE' => KernelLanguage::ROUTER['REWRITE'][self::LANGUAGE],

        'API_DIR' => self::MODEL_DIR . 'Api' . DIRECTORY_SEPARATOR,

        'TERMINAL_DIR' => self::MODEL_DIR . 'Terminal' . DIRECTORY_SEPARATOR,

        'API_TABLE' => array
        (
            'process' => 'Process' . DIRECTORY_SEPARATOR . 'Process.php',
            'equipment' => 'Equipment' . DIRECTORY_SEPARATOR . 'Equipment.php',
            'index' => 'index.php',
        ),

        'TERMINAL_TABLE' => array
        (
            'WriteSensor' => 'Process' . DIRECTORY_SEPARATOR . 'WriteSensor' . DIRECTORY_SEPARATOR . 'WriteSensor.php',
            'test' => 'test.php'
        ),
    );
}