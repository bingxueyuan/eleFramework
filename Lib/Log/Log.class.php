<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/8/20
 */

namespace Lib\Log;


class Log extends AbstractLog
{
    /**
     * 查看日志
     * @param bool $all
     * @return array
     */
    static public function getFile(bool $all = true)
    {
        $currentLogFile = self::file();

        $filesystemIterator = new \FilesystemIterator(self::$dir);

        $AllLogFile = null;

        foreach ($filesystemIterator as $file)
        {
            if ($file -> isFile() && explode('.', $file -> getFilename())[0] . '.' === self::$medium[self::$current]['FILE_PRE'] && '.' . $file -> getExtension() === self::$medium[self::$current]['EXTENSION'])
            {
                $AllLogFile[] = $file -> getFilename();
            }
        }

        if ($all)
        {
            return array(
                'log' => $AllLogFile,
                'dir' => self::$dir
            );
        }
        else
        {
            return array(
                'log' => $currentLogFile,
                'dir' => self::$dir
            );
        }
    }

    /**
     * 删除日志
     * @param string $file
     * @return bool
     */
    static public function unlinkFile(string $file)
    {
        return unlink(self::$dir . $file);
    }

    /**
     * 事件结果日志
     * @param string $class
     * @param string $file
     * @param string $line
     * @param string $success
     * @param string $error
     * @param array $json
     * @param bool $result
     * @param bool $response
     * @return bool
     */
    public function boolLog(string $class, string $file, string $line, string $success, string $error, array $json, bool $result = true, bool $response = false)
    {
        if ($result === true)
        {
            LOG_RECORD_DEBUG? Log::debug($class . '.class', $success, array_merge(array(
                parent::$languageRecordLocation => parent::$languageFile . $file . U32 . parent::$languageLine . U32 . $line,
            ), $json)) : null;

            if ($response)
            {
                parent::$responseHandle = parent::$responseHandle === null? new \Lib\Response\Response() : parent::$responseHandle;

                parent::$responseHandle -> quicklyShow(RESPONSE_INFO, $success, array(), true);
            }

            return true;
        }
        else
        {
            LOG_RECORD_EMERGENCY? Log::emergency($class . '.class', $error, array_merge(array(
                parent::$languageRecordLocation => parent::$languageFile . $file . U32 . parent::$languageLine . U32 . $line,
            ), $json)) : null;

            if ($response)
            {
                parent::$responseHandle = parent::$responseHandle === null? new \Lib\Response\Response() : parent::$responseHandle;

                parent::$responseHandle -> quicklyShow(RESPONSE_ERROR, $error, array(), true);
            }

            return false;
        }
    }

    public function debug(string $title, string $message, array $json = array())
    {
        parent::write($title, parent::DEBUG, $message, json_encode($json, JSON_UNESCAPED_UNICODE ));
    }

    public function info(string $title, string $message, array $json = array())
    {
        parent::write($title, parent::INFO, $message, json_encode($json, JSON_UNESCAPED_UNICODE ));
    }

    public function notice(string $title, string $message, array $json = array())
    {
        parent::write($title, parent::NOTICE, $message, json_encode($json, JSON_UNESCAPED_UNICODE ));
    }

    public function warning(string $title, string $message, array $json = array())
    {
        parent::write($title, parent::WARNING, $message, json_encode($json, JSON_UNESCAPED_UNICODE ));
    }

    public function error(string $title, string $message, array $json = array())
    {
        parent::write($title, parent::ERROR, $message, json_encode($json, JSON_UNESCAPED_UNICODE ));
    }

    public function critical(string $title, string $message, array $json = array())
    {
        parent::write($title, parent::CRITICAL, $message, json_encode($json, JSON_UNESCAPED_UNICODE ));
    }

    public function alert(string $title, string $message, array $json = array())
    {
        parent::write($title, parent::ALERT, $message, json_encode($json, JSON_UNESCAPED_UNICODE ));
    }

    public function emergency(string $title, string $message, array $json = array())
    {
        parent::write($title, parent::EMERGENCY, $message, json_encode($json, JSON_UNESCAPED_UNICODE ));
    }

    public function log(string $level, string $title, string $message, array $json = array())
    {
        parent::write($title, $level, $message, json_encode($json, JSON_UNESCAPED_UNICODE ));
    }
}