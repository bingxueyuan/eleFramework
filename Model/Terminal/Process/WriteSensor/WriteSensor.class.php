<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/9/2
 */

namespace Model\Terminal\Process\WriteSensor;


class WriteSensor extends AbstractWriteSensor
{
    public $curlHandle = null;

    public function __construct()
    {
        parent::__construct();

        $this -> curlHandle = new \Lib\Curl\Curl();
    }

    public function run()
    {
        $dataArray = $this -> curlHandle ->getEquipment();

        parent::addPhData($dataArray['ph']);
        parent::addTemperatureData($dataArray['temperature']);
        parent::addPressureData($dataArray['pressure']);
        parent::addSpeedsData($dataArray['speeds']);
    }
}