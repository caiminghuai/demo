<?php

namespace cmh\facade;

use think\Facade;

/**
 * Class Tree
 */
class Tree extends Facade
{
    protected static function getFacadeClass()
    {
        return \cmh\service\TreeService::class;
    }
}
