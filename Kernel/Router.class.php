<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/8/21
 */

namespace Kernel{


    class Router
    {
        static private $apiDir = null;
        static private $apiTable = null;
        static private $terminalDir = null;
        static private $terminalTable = null;

        static private $languageTableEmpty = null;
        static private $languageTableModelLoadError = null;
        static private $languageTableModelLoadSuccess = null;
        static private $languageTableModelConfigError = null;
        static private $languageRewrite = null;

        static private $logHandle = null;

        static public function run($argv):void
        {
            self::$apiDir = \Config\KernelConfig::ROUTER['API_DIR'];
            self::$apiTable = \Config\KernelConfig::ROUTER['API_TABLE'];
            self::$terminalDir = \Config\KernelConfig::ROUTER['TERMINAL_DIR'];
            self::$terminalTable = \Config\KernelConfig::ROUTER['TERMINAL_TABLE'];

            self::$languageTableEmpty = \Config\KernelConfig::ROUTER['TABLE_EMPTY'];
            self::$languageTableModelLoadError = \Config\KernelConfig::ROUTER['TABLE_MODEL_LOAD_ERROR'];
            self::$languageTableModelLoadSuccess = \Config\KernelConfig::ROUTER['TABLE_MODEL_LOAD_SUCCESS'];
            self::$languageTableModelConfigError = \Config\KernelConfig::ROUTER['TABLE_MODEL_CONFIG_ERROR'];
            self::$languageRewrite = \Config\KernelConfig::ROUTER['REWRITE'];

            if (IS_CLI)
            {
                self::LoadTerminalModel($argv);
            }
            else
            {
                self::loadApiModel();
            }
        }

        /**
         * 加载终端模块
         * @return null|string
         */
        static private function LoadTerminalModel($argv)
        {
            count($argv) < 2? exit() : null;

            $modelName = $argv[1];

            /**
             * Model名为空, 结束
             */
            $terminalName = empty($modelName)? exit() : $modelName;

            /**
             * 终端表为空的时候, 结束
             */
            $terminalFile = count(self::$terminalTable) === 0? exit() : null;

            foreach (self::$terminalTable as $routerName => $routerPath)
            {
                if ($terminalName === $routerName)
                {
                    if (empty($routerPath))
                    {
                        self::log(__CLASS__, __FILE__, __LINE__, '',
                            self::$languageTableModelConfigError . U32 . $terminalName,
                            array(), false, true, true);
                    }

                    $terminalFile = self::$terminalDir . $routerPath;

                    break;
                }
            }

            /**
             * Model名不为空, 终端表不为空, 没有匹配到终端表, 结束
             */
            $terminalFile = $terminalFile === null? exit() : $terminalFile;

            file_exists($terminalFile)? null : self::log(__CLASS__, __FILE__, __LINE__, '',
                self::$languageTableModelLoadError . $terminalFile, array(), false, true, true);

            self::log(__CLASS__, __FILE__, __LINE__, self::$languageTableModelLoadSuccess .
                $terminalFile, '', array('依赖文件' => get_included_files()), true, false, false);

            require_once $terminalFile;

            return $terminalFile;
        }

        /**
         * 加载API模块
         * @return string
         */
        static private function loadApiModel()
        {
            $apiName = null;
            $apiFile = null;

            /**
             * 未开启伪静态环境
             */
            if (Http::requestCatalog() === false)
            {
                $apiName = Http::get('model') ?? Http::post('model');
            }
            /**
             * 已开启伪静态
             */
            else
            {
                /**
                 * 获取伪静态路径
                 * 如果 $_SERVER['PATH_INFO'] 路径为空, 结束
                 */
                $apiName = empty(Http::requestCatalog())? exit() : implode(DIRECTORY_SEPARATOR, Http::requestCatalog());
            }

            /**
             * 路由表为空, 结束
             */
            $apiFile = count(self::$apiTable) === 0? exit() : null;

            foreach (self::$apiTable as $routerName => $routerPath)
            {
                if ($apiName === $routerName)
                {
                    if (empty($routerPath))
                    {
                        self::log(__CLASS__, __FILE__, __LINE__, '',
                            self::$languageTableModelConfigError . U32 . $apiName,
                            array(), false, true, true);
                    }

                    $apiFile = self::$apiDir . $routerPath;

                    break;
                }
            }

            /**
             * 没有匹配到路由表, 结束
             */
            $apiFile = $apiFile === null? exit(): $apiFile;

            file_exists($apiFile)? null : self::log(__CLASS__, __FILE__, __LINE__, '',
                self::$languageTableModelLoadError . $apiFile, array(), false, true, true);

            self::log(__CLASS__, __FILE__, __LINE__, self::$languageTableModelLoadSuccess .
                $apiFile, '', array('依赖文件' => get_included_files()), true, false, false);

            require_once $apiFile;

            return $apiFile;
        }

        /**
         * 记日志
         * @param string $class
         * @param string $file
         * @param string $line
         * @param string $success
         * @param string $error
         * @param array $json
         * @param bool $result
         * @param bool $response
         * @param bool $exit
         */
        static private function log(string $class, string $file, string $line, string $success, string $error, array $json, bool $result = true, bool $response = false, bool $exit = false)
        {
            self::$logHandle = self::$logHandle === null? new \Lib\Log\Log() : self::$logHandle;

            self::$logHandle -> boolLog($class . '.class', $file, $line, $success, $error, $json, $result, $response);

            $exit? exit() : null;
        }
    }
}
