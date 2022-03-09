<?php

declare (strict_types=1);

namespace cmh\service;

use think\facade\Cache;

/**
 * 缓存服务
 * Class RedisService
 *
 * @package cmh\service
 */
class RedisService
{
    private $expire;
    private $expire_at;

    /**
     * 获取redis句柄
     *
     * @return object|null
     */
    public function client(): ?object
    {
        return Cache::store('redis')->handler();
    }

    /**
     * 处理缓存key（添加前缀...）
     *
     * @param string $key  key
     *
     * @return string
     */
    private function cacheKey(string $key): string
    {
        return Cache::getCacheKey($key);
    }

    /**
     * 判断缓存是否存在
     *
     * @param string $key  键
     *
     * @return bool
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function has(string $key): bool
    {
        return Cache::has($key);
    }

    /**
     * 设置缓存
     *
     * @param string $key     键
     * @param mixed  $value   值
     * @param int    $expire  有效时间
     *
     * @return bool
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function setString(string $key, $value, $expire = 0): bool
    {
        return Cache::set($key, $value, $expire);
    }

    /**
     * 获取有效时长
     *
     * @param string $key  键
     *
     * @return int
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function getTtl(string $key): int
    {
        $key = Cache::getCacheKey($key); //获取表名

        return Cache::store('redis')->handler()->TTL($key); //获取缓存
    }

    /**
     * 缓存自增
     *
     * @param string $key   键
     * @param int    $step  步长
     *
     * @return bool
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function incString(string $key, $step = 1)
    {
        return Cache::inc($key, $step);
    }

    /**
     * 缓存自减
     *
     * @param string $key   键
     * @param int    $step  步长
     *
     * @return bool
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function decString(string $key, $step = 1)
    {
        return Cache::dec($key, $step);
    }

    /**
     * 获取缓存
     *
     * @param string $key  键
     *
     * @return string
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function getString(string $key)
    {
        if (! $this->has($key)) {
            return false;
        }

        return Cache::get($key);
    }

    /**
     * 追加集合缓存(redis)
     *
     * @param string $key    键
     * @param string $value  值
     * @param int    $expire
     *
     * @return string
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function addSet(string $key, string $value, $expire = 3600)
    {
        $key = Cache::getCacheKey($key); //获取缓存键
        $result = Cache::store('redis')->handler()->sAdd($key, $value);
        Cache::store('redis')->handler()->expire($key, $expire);

        return $result;
    }

    /**
     * 追加集合缓存(redis)
     *
     * @param string $key    键
     * @param string $value  值
     * @param int    $expire
     *
     * @return string
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function addSets(string $key, array $value, $expire = 3600)
    {
        $key = Cache::getCacheKey($key); //获取缓存键
        $result = Cache::store('redis')->handler()->sAdd($key, $value);
        Cache::store('redis')->handler()->expire($key, $expire);

        return $result;
    }

    /**
     * 获取集合缓存(redis)
     *
     * @param string $key  键
     *
     * @return string
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function getSet(string $key)
    {
        $key = Cache::getCacheKey($key); //获取缓存键
        $result = Cache::store('redis')->handler()->sMembers($key); //获取缓存

        return $result; //返回数据
    }

    /**
     * 获取集合缓存数据总数
     *
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function getSetCount(string $key)
    {
        $key = Cache::getCacheKey($key); //获取缓存键
        $result = Cache::store('redis')->handler()->scard($key); //获取缓存

        return $result; //返回数据
    }

    /**
     * 获取集合缓存(redis)
     *
     * @param string $key  键
     * @param        $value
     *
     * @return string
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function hasSetMember(string $key, $value)
    {
        $key = Cache::getCacheKey($key); //获取缓存键
        $result = Cache::store('redis')->handler()->sismember($key, $value); //获取缓存

        return $result; //返回数据
    }

    /**
     * 获取集合缓存(redis)
     *
     * @param string $key  键
     * @param null   $iterator
     * @param int    $count
     *
     * @return string
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function sscanSet(string $key, &$iterator = null, $count = 20)
    {
        $key = Cache::getCacheKey($key); //获取缓存键
        $result = Cache::store('redis')->handler()->sscan($key, $iterator, "*", $count); //获取缓存

        return $result; //返回数据
    }

    /**
     * 移除集合中的元素
     *
     * @param string $key  键
     * @param        $value
     *
     * @return string
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function delSet(string $key, $value)
    {
        $key = Cache::getCacheKey($key); //获取缓存键
        $result = Cache::store('redis')->handler()->sRem($key, $value); //获取缓存

        return $result; //返回数据
    }

    /**
     * 追加队列缓存
     *
     * @param string $key    键
     * @param string $value  值
     *
     * @return int  列表的长度
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function pushList(string $key, string $value): int
    {
        $key = Cache::getCacheKey($key); //获取缓存键

        return Cache::store('redis')->handler()->lPush($key, $value); //获取缓存
    }

    /**
     * 取出队列缓存
     *
     * @param string $key  键
     *
     * @return string|false
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function pullList(string $key)
    {
        $key = Cache::getCacheKey($key); //获取缓存键

        return Cache::store('redis')->handler()->rPop($key); //获取缓存
    }

    /**
     * 获取队列长度
     *
     * @param string $key  键
     *
     * @return int 列表的长度
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function getListCount(string $key): int
    {
        $key = Cache::getCacheKey($key); //获取缓存键

        return Cache::store('redis')->handler()->lLen($key); //获取缓存
    }

    /**
     *对哈希表key设置一个字段值
     *
     * @param string $table  哈希表名
     * @param string $key    键
     * @param mixed  $value  值
     * @param int    $expire
     *
     * @return bool
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function setHash(string $table, string $key, $value, $expire = 3600): bool
    {
        $table = Cache::getCacheKey($table); //获取表名
        $result = Cache::store('redis')->handler()->hSet($table, $key, $value); //获取缓存
        Cache::store('redis')->handler()->expire($table, $expire);

        return (bool)$result; //返回数据
    }

    /**
     * 获取表中key的值
     *
     * @param string $table  哈希表名
     * @param string $key    键
     *
     * @return string | bool
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function getHash(string $table, string $key)
    {
        $table = Cache::getCacheKey($table); //获取表名

        return Cache::store('redis')->handler()->hGet($table, $key); //获取缓存
    }

    /**
     *对哈希表批量设置数据
     *
     * @param string $table  哈希表名
     * @param array  $values
     * @param int    $expire
     *
     * @return string
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function setHashs(string $table, array $values, $expire = 3600): bool
    {
        $table = Cache::getCacheKey($table); //获取表名
        $result = Cache::store('redis')->handler()->hMSet($table, $values); //写入缓存
        Cache::store('redis')->handler()->expire($table, $expire);

        return $result; //返回数据
    }

    /**
     * 获取表中key的值
     *
     * @param string $table  哈希表名
     *
     * @return array
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function getHashs(string $table): array
    {
        $table = Cache::getCacheKey($table); //获取表名

        return Cache::store('redis')->handler()->hGetAll($table); //获取缓存
    }

    /**
     * 删除hash值
     *
     * @param string $table
     * @param string $key
     *
     * @return mixed
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function delHash(string $table, string $key)
    {
        $table = Cache::getCacheKey($table); //获取表名

        return Cache::store('redis')->handler()->hDel($table, $key); //获取缓存
    }

    /**
     * hash自增
     *
     * @param string $table
     * @param string $key
     * @param int    $num
     *
     * @return mixed
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function incHash(string $table, string $key, $num = 1)
    {
        $table = Cache::getCacheKey($table); //获取表名

        return Cache::store('redis')->handler()->hIncrby($table, $key, $num); //获取缓存
    }

    /**
     * hash自增(浮点型)
     *
     * @param string $table
     * @param string $key
     * @param int    $num
     *
     * @return mixed
     * @author  yangqi
     * @time    2021年03月05日
     */
    public function incFolatHash(string $table, string $key, $num = 1)
    {
        $table = Cache::getCacheKey($table); //获取表名

        return Cache::store('redis')->handler()->hIncrbyFloat($table, $key, $num); //获取缓存
    }

    /**
     * 获取表中多个key的值
     *
     * @param string $table  哈希表名
     * @param array  $keys
     *
     * @return array
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function getHashValues(string $table, array $keys): array
    {
        $table = Cache::getCacheKey($table); //获取表名

        return Cache::store('redis')->handler()->hMGet($table, $keys); //获取缓存
    }

    /**
     * 获取表中key的值是否存在
     *
     * @param string $table  哈希表名
     * @param string $key    键
     *
     * @return bool
     * @author  yangqi
     * @time    2021年3月7日
     */
    public function hasHash(string $table, string $key): bool
    {
        $table = Cache::getCacheKey($table); //获取表名

        return (bool)Cache::store('redis')->handler()->hExists($table, $key); //获取缓存
    }

    /**
     * 获取缓存锁
     *
     * @param string $key
     * @param int    $expire
     *
     * @return bool
     * @author yangqi
     * @time   2021年3月7日
     */
    public function getLock(string $key, $expire = 10)
    {
        //设置缓存锁key
        $key = 'cache:lock:' . $key;
        //判断缓存是否存在
        if ($this->has($key)) {
            return false;
        }
        //设置缓存锁
        $this->setString($key, 1, $expire);

        //返回数据
        return true;
    }

    /**
     * 删除缓存锁
     *
     * @param string $key
     *
     * @return bool
     * @author yangqi
     * @time   2021年3月7日
     */
    public function delLock(string $key): bool
    {
        //设置缓存锁key
        $key = 'cache:lock:' . $key;

        //删除缓存锁
        return $this->del($key);
    }

    /**
     * 缓存程序运行结果
     *
     * @param          $key
     * @param callable $callback
     * @param int      $expire
     *
     * @return mixed
     */
    public function cache($key, callable $callback, int $expire = 60)
    {
        $cache = $this->client()->get($key);
        if (! $cache || ! unserialize($cache)) {
            $data = $callback();
            $this->client()->set($key, $cache = serialize($data), $expire);
        }

        return unserialize($cache);
    }

    /**
     * 程序运行锁
     * @param          $key
     * @param callable $callback
     * @param int      $timeout
     *
     * @return array
     */
    public function lock($key, callable $callback, int $timeout = 10): array
    {
        $lock = $this->client()->get($key);
        if ($lock) return ['code' => 0, 'data' => null];
        $this->client()->setex($key, $timeout, 1);
        $data = $callback();
        $this->client()->del($key);

        return ['code' => 1, 'data' => $data];
    }

    /**
     * 设置有效时间
     *
     * @param $ttl
     *
     * @return $this|false
     */
    public function setExpire($ttl)
    {
        if ($this->expire_at) throw new \Exception('setExpire and setExpireAt can not set both');
        $this->expire = $ttl;

        return $this;
    }

    /**
     * 设置到期时间
     *
     * @param $timestamp
     *
     * @return $this|false
     */
    public function setExpireAt($timestamp)
    {
        if ($this->expire > 0) throw new \Exception('setExpire and setExpireAt can not set both');
        $this->expire_at = $timestamp;

        return $this;
    }

    /**
     * 调用原生redis方法
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $cache_key = $this->cacheKey($arguments[0]);

        $result = $this->client()->{$name}(...$arguments);

        // 设置过期时间
        $this->expire && $this->client()->expire($cache_key, $this->expire);
        $this->expire_at && $this->client()->expireAt($cache_key, $this->expire_at);

        return $result;
    }
}
