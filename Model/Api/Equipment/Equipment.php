<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/9/3
 */
namespace Model\Api\Equipment;


header("Access-Control-Allow-Origin: *");

$return = null;

if (\Kernel\Http::post('default') === '1')
{
    $equipment = new EquipmentInfo();

    $getEquipment = $equipment ->getEquipment(EquipmentInfo::$var)[0];

    $data = new Equipment();
    $sensorRows = $data -> allSensorRows();
    $sensorData = $data ->getSensorData(Equipment::$var);

    $countData = count($sensorData);

    $current_key = $current_value = null;


    for ($i = 0; $i < $countData; ++$i)
    {
        $current_value[] = $sensorData[$i][Equipment::$var];
        $current_key[] = $sensorData[$i]['date'];
    }

    $return = array(
        'sensor_id' => $getEquipment['sensor_id'],
        'line' => $getEquipment['line'],
        'sensor_name' => $getEquipment['sensor_name'],
        'all_rows' => $sensorRows,
        'all_pages' => ceil($sensorRows / Equipment::$sensorNum),
        'current_pages' => (int)\Kernel\Http::post('current_pages'),
        'limit' => Equipment::$sensorNum,
        'pieces' => array(
            'min' => array(
                'gt' => $getEquipment['min_gt'],
                'lt' => $getEquipment['min_lt'],
            ),
            'normal' => array(
                'gt' => $getEquipment['normal_gt'],
                'lt' => $getEquipment['normal_lt'],
            ),
            'max' => array(
                'gt' => $getEquipment['max_gt'],
                'lt' => $getEquipment['max_lt'],
            ),
        ),
        'current_key' => $current_key,
        'current_value' => $current_value,
    );
}

if (\Kernel\Http::post('updateEquipment') === '1')
{
    $equipment = new EquipmentInfo();

    $equipment ->updateEquipment(array(
        'sensor_id' => \Kernel\Http::post('sensor_id'),
        'sensor_name' => \Kernel\Http::post('sensor_name'),
        'line' => \Kernel\Http::post('line'),
        'min_gt' => \Kernel\Http::post('min_gt'),
        'min_lt' => \Kernel\Http::post('min_lt'),
        'normal_gt' => \Kernel\Http::post('normal_gt'),
        'normal_lt' => \Kernel\Http::post('normal_lt'),
        'max_gt' => \Kernel\Http::post('max_gt'),
        'max_lt' => \Kernel\Http::post('max_lt')
    ));
}

if (\Kernel\Http::post('equipmentList') === '1')
{
    $equipment = new EquipmentInfo();

    $return = $equipment ->getEquipmentList();
}

if (\Kernel\Http::post('AllEquipmentList') === '1')
{
    $equipment = new EquipmentInfo();

    $return = $equipment ->getEquipmentList();
}

$response = new \Lib\Response\Response();
$response ->quicklyShow(200, 'ok', array($return));