<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/8/29
 */

namespace Config;


class LibLanguage
{
    const LOG = array
    (
        'RECORD_LOCATION' => array
        (
            'ZH' => '记录位置：' . U32,
            'EN' => 'Record location:' . U32,
        ),

        'FILE' => array
        (
            'ZH' => '文件：' . U32,
            'EN' => 'File:' . U32,
        ),

        'LINE' => array
        (
            'ZH' => '所在行：' . U32,
            'EN' => 'In Line:' . U32,
        ),
    );
}