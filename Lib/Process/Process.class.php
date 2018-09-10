<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/9/1
 */

namespace Lib\Process;


class Process extends AbstractProcess
{
    /**
     * 获取进程列表
     * @param $script|null $script
     * @param int $start
     * @param int $num
     * @return mixed
     */
    public function getProcessList(string $script = null, int $start, int $num)
    {
        return parent::$sqlHandle -> select('', parent::$processTable, array(),
            $script === null? array() : array(parent::$processScript . '[=]' => $script),
            array(), array($start, $num));
    }

    /**
     * 删除进程
     * @param string $script
     * @return mixed
     */
    public function removeProcess(string $script)
    {
        return parent::$sqlHandle -> delete('', parent::$processTable, array(parent::$processScript . '[=]' => $script)) === 1? true : false;
    }

    /**
     * 添加进程
     * @param string $script
     * @param string $explain
     * @return mixed
     */
    public function addProcess(string $script, string $explain)
    {
        return parent::$sqlHandle -> insert('', parent::$processTable, array(
            parent::$processIndex => null,
            parent::$processPid => 'undefined',
            parent::$processState => 'undefined',
            parent::$processScript => $script,
            parent::$processExplain => $explain,
            parent::$processDate => date('Y-m-d H:i:s'),
        )) === 1? true : false;
    }

    /**
     * 询问进程是否存在
     * @param int $pid
     * @return bool|string
     */
    public function pingProcess(int $pid)
    {
        $ps = popen(parent::$process_python . U32 . parent::$processBinDir . parent::$processBin_PS . U32 . $pid, 'r');

        $char = fgets($ps, 1000);

        pclose($ps);

        return strstr($char, 'running')? true : false;
    }

    /**
     * 启动进程
     * @param string $script
     * @return bool
     */
    public function startProcess(string $script)
    {
        $iterator = new \FilesystemIterator(parent::$processCacheDir);

        while ($iterator -> valid())
        {
            if ('.' . $iterator -> getExtension() === parent::$processCacheExtension)
            {
                list($fileName, $fileExtension) = explode('.', $iterator -> getFilename());

                if ($fileName === $script)
                {
                    $pid = file_get_contents(parent::$processCacheDir . $fileName . '.' . $fileExtension);

                    if (!empty($pid))
                    {
                        $ps = self::pingProcess($pid);
                    }
                    else
                    {
                        $ps = false;
                    }

                    if ($ps === false)
                    {
                        exec(PHP . U32 . 'index.php' . U32 . $script);
                    }
                }
            }

            $iterator -> next();
        }

        /**
         * 一定启动了
         */
        return true;
    }

    /**
     * 更新进程信息
     */
    public function updateProcess()
    {
        $iterator = new \FilesystemIterator(parent::$processCacheDir);

        while ($iterator -> valid())
        {
            if ('.' . $iterator -> getExtension() === parent::$processCacheExtension)
            {
                list($fileName, $fileExtension) = explode('.', $iterator -> getFilename());

                $pid = file_get_contents(parent::$processCacheDir . $fileName . '.' . $fileExtension);

                if (!empty($pid))
                {
                    $processState = self::pingProcess($pid)? 1 : 0;
                }
                else
                {
                    $processState = 0;
                }

                parent::$sqlHandle -> update('', parent::$processTable, array(
                    parent::$processPid => $pid,
                    parent::$processState => $processState,
                    parent::$processScript => $fileName,
                ), array(
                    parent::$processScript . '[=]' => $fileName,
                ));
            }

            $iterator -> next();
        }
    }
}