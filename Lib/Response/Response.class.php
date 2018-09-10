<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/8/26
 */

namespace Lib\Response;


class Response extends AbstractResponse
{
    public function quicklyShow(int $code, string $message, array $data, bool $exit = false)
    {
        parent::$code = (int)$code;

        parent::$message = (string)$message;

        parent::$data[] = $data;

        parent::printJson();

        $exit? exit() : null;
    }
}