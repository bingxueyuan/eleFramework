<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/8/21
 */

namespace Lib\Curl;


class Curl extends AbstractCurl
{
    public function getEquipment()
    {
        $rawData = $this->send('equipment', parent::$serverEquipment['ADDRESS'] . '?token=' . self::$serverEquipment['TOKEN']);

        return json_decode($rawData, true);
    }
}