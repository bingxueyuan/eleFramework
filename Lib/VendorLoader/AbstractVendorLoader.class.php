<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/8/29
 */

namespace Lib\VendorLoader;


class AbstractVendorLoader
{
    static protected $vendorDir = null;
    static protected $autoloadFile = null;

    static protected $requireList = null;

    public function __construct()
    {
        self::$vendorDir = \Config\LibConfig::VENDOR_LOADER['VENDOR_DIR'];
        self::$autoloadFile = \Config\LibConfig::VENDOR_LOADER['AUTOLOAD_FILE'];
    }
}