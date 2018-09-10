<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/8/28
 */

namespace Config;


class KernelLanguage
{
    const ROUTER = array
    (
        'TABLE_EMPTY' => array
        (
            'ZH' => '@Config/KernelConfig::ROUTER' . U32 . '未记录路由信息',
            'EN' => '@Config/KernelConfig::ROUTER' . U32 . 'No routing information was recorded',
        ),

        'TABLE_MODEL_LOAD_ERROR' => array
        (
            'ZH' => '@Config/KernelConfig::ROUTER' . U32 . '路由信息中存在模块名' . U32 . '但是模块文件不存在' . U32,
            'EN' => '@Config/KernelConfig::ROUTER' . U32 . 'Module name exists in routing information' . U32 . 'But module files do not exist' . U32,
        ),

        'TABLE_MODEL_LOAD_SUCCESS' => array
        (
            'ZH' => '已成功加载模块：' . U32,
            'EN' => 'The module has been successfully loaded:' . U32,
        ),

        'TABLE_MODEL_CONFIG_ERROR' => array
        (
            'ZH' => '路由信息配置错误：' . U32,
            'EN' => 'Routing information configuration error:' . U32,
        ),

        'REWRITE' => array
        (
            'ZH' => '建议开启伪静态，配置伪静态规则' . U32,
            'EN' => 'It is recommended to open pseudo static and configure pseudo static rules' . U32,
        )
    );
}