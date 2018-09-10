<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/8/19
 */

define('ROOT', __DIR__);

require_once ROOT . DIRECTORY_SEPARATOR . 'Kernel/Kernel.class.php';

Kernel\Kernel::run($argv?? null);
