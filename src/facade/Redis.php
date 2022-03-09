<?php

namespace cmh\facade;

use think\Facade;

/**
 * Class Redis
 */

class Redis extends Facade
{
    protected static function getFacadeClass()
    {
        return \cmh\service\RedisService::class;
    }
}
