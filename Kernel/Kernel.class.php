<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/8/19
 */

namespace Kernel {


    class Kernel
    {
        static public function run($argv):void
        {
            require_once ROOT . DIRECTORY_SEPARATOR . 'Kernel/Loader.class.php';

            Loader::autoload();

            self::ready();

            /**
             * 启动路由
             */
            Router::run($argv);
        }

        /**
         *
         */
        static private function ready():void
        {
            /**
             * 空格
             */
            define('U32', "\u{0020}");

            /**
             * 伪静态
             */
            define('REWRITE', true);

            /**
             * php位置
             */
            define('PHP', 'C:\\Apache24\\php7\\php.exe');

            /**
             * 定义域名
             */
            define('DOMAIN', 'http://php.io');

            /**
             * 时区
             */
            date_default_timezone_set('Asia/Shanghai');

            /**
             * 内存限制
             */
            ini_set('memory_limit', '10M');

            /**
             * 是否在CLI环境下运行
             */
            define('IS_CLI', strtolower(PHP_SAPI) === 'cli'? true : false);

            /**
             * 是否windows系统
             */
            define('IS_WIN', strstr(PHP_OS, 'WIN')? true : false);

            /**
             * 是否记录INFO级别日志
             */
            define('LOG_RECORD_INFO', true);

            /**
             * 是否记录DEBUG级别日志
             */
            define('LOG_RECORD_DEBUG', true);

            /**
             * 是否记录NOTICE级别日志
             */
            define('LOG_RECORD_NOTICE', true);

            /**
             * 是否记录WARNING级别日志
             */
            define('LOG_RECORD_WARNING', true);

            /**
             * 是否记录ERROR级别日志
             */
            define('LOG_RECORD_ERROR', true);

            /**
             * 是否记录CRITICAL级别日志
             */
            define('LOG_RECORD_CRITICAL', true);

            /**
             * 是否记录EMERGENCY级别日志
             */
            define('LOG_RECORD_EMERGENCY', true);

            /**
             * 响应代码
             */
            define('RESPONSE_INFO', 100);

            define('RESPONSE_SUCCESS', 200);

            define('RESPONSE_ERROR', 400);

            define('RESPONSE_FORBIDDEN', 500);
        }
    }
}
