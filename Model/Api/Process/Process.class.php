<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/9/1
 */

namespace Model\Api\Process;


class Process extends AbstractProcess
{
    static private $getToken = null;
    static private $getPid = null;
    static private $getCurrentPage = null;
    static private $getScript = null;
    static private $getExplain = null;

    static public $getProcess = null;
    static public $getAddProcess = null;
    static public $getPing = null;
    static public $getStartProcess = null;
    static public $getRemoveProcess = null;
    static public $getDefaults = null;

    static private $num = null;
    static private $token = null;


    static private $responseHandle = null;

    public function __construct()
    {
        parent::__construct();

        self::$getToken = \Kernel\Http::post('token');
        self::$getCurrentPage = \Kernel\Http::post('currentPage');
        self::$getScript = \Kernel\Http::post('script');
        self::$getExplain = \Kernel\Http::post('explain');

        self::$getProcess = \Kernel\Http::post('getProcess');
        self::$getAddProcess = \Kernel\Http::post('addProcess');
        self::$getPing = \Kernel\Http::post('ping');
        self::$getStartProcess = \Kernel\Http::post('startProcess');
        self::$getRemoveProcess = \Kernel\Http::post('removeProcess');
        self::$getDefaults = \Kernel\Http::post('defaults');

        self::$num = \Model\Api\AppConfig\AppConfig::PAGE_LIMIT;
        self::$token = \Model\Api\AppConfig\AppConfig::TOKEN;

        self::$responseHandle = new \Lib\Response\Response();

        if ((string)self::$getToken !== (string)self::$token)
        {
            self::$responseHandle -> quicklyShow(400, 'token错误', array(), true);
        }
    }


    /**
     * 分页, 搜索
     */
    public function getProcess()
    {
        parent::$processHandle -> updateProcess();

        $processRows = parent::$processHandle -> getProcessList(self::$getScript === ''? null : self::$getScript, (self::$getCurrentPage - 1) * self::$num, self::$num);

        $count = self::$processHandle -> allProcessRows();

        self::$responseHandle -> quicklyShow(200, 'ok', array(
            'process' => $processRows,
            'all_rows' => $count,
            'all_pages' => ceil($count / self::$num),
            'current_pages' => self::$getCurrentPage,
            'limit' => 5
        ), false);
    }


    public function addProcess()
    {
        $code = self::$processHandle -> addProcess(self::$getScript, self::$getExplain) === 1? 200 : 500;

        self::$responseHandle -> quicklyShow($code, $code === 200? '添加进程成功' : '添加进程失败', array(), false);
    }


    public function ping()
    {
        if (empty(self::$getPid))
        {
            $code = 500;
        }
        else
        {
            $code = self::$processHandle -> pingProcess((int)self::$getPid) === true? 200 : 500;
        }

        self::$responseHandle -> quicklyShow($code, $code === 200? '进程存活' : '进程挂掉', array(), false);
    }

    public function startProcess()
    {
        $code = self::$processHandle -> startProcess(self::$getScript) === true? 200 : 500;

        self::$responseHandle -> quicklyShow($code, $code === 200? '开始进程' : '进程激活失败', array(), false);
    }

    public function removeProcess()
    {
        $code = self::$processHandle -> removeProcess(self::$getScript) === true? 200 : 500;

        self::$responseHandle -> quicklyShow($code, $code === 200? '进程已经移除' : '进程移除失败', array(), false);
    }

}