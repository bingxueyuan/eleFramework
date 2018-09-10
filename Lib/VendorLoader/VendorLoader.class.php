<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/8/29
 */

namespace Lib\VendorLoader;


class VendorLoader extends AbstractVendorLoader
{
    public function loading()
    {
        require_once parent::$autoloadFile;
    }

    public function requireList()
    {
        parent::$requireList = json_decode(file_get_contents(parent::$vendorDir . 'composer.json'), true)['require'];

        return parent::$requireList;
    }
}