<?php

namespace CommonUtils;

require_once __DIR__ . '/../vendor/autoload.php'; // 放在 namespace 之后

use Ulid\Ulid;
use UUID\UUID;

class TimeID
{
    private static int $lastTime = 0;

    private static function nanoTime(): int
    {
        // microtime(true) 返回从 1970-01-01 00:00:00 UTC 开始的秒数
        // 1748262656.5189 => 2025-05-26 12:30:56
        // 1 秒　 = 1000 毫秒 ( ms )
        // 1 毫秒 = 1000 微秒 ( µs )
        // 1 微秒 = 1000 纳秒 ( ns )
        // 1 纳秒 = 1000 皮秒
        return (int)(microtime(true) * 1000 * 1000);    // 微秒时间戳 ( 13 位毫秒 + 3 位微秒 )，后三位相当于自增随机数
    }

    /**
     * @return string
     */
    public static function UUID_V7(): string
    {
        return strtoupper(UUID::uuid7());
    }

    /**
     * @return string
     */
    public static function ULID(): string
    {
        return (string)Ulid::generate();
    }

    public static function idStr(): string
    {
        return (string)self::id();
    }

    public static function id(): int
    {
        $now = self::nanoTime();
        while ($now <= self::$lastTime) {
            time_nanosleep(0, 1000000);                 // 睡 1ms
            $now = self::nanoTime();
        }
        return self::$lastTime = $now;
    }
}

for ($i = 0; $i < 5; $i++) {
    echo TimeID::ULID() . PHP_EOL;                        // 1748 2699 5592 8389 前端不会溢出
}

/*
 * 01JW7QYV919QVK8W4S5VQNNVPJ
 * 01JW7QYV919QVK8W4S5VQNNVPK
 * 01JW7QYV919QVK8W4S5VQNNVPM
 * 01JW7QYV919QVK8W4S5VQNNVPN
 * 01JW7QYV919QVK8W4S5VQNNVPP
 */

for ($i = 0; $i < 5; $i++) {
    echo TimeID::UUID_V7() . PHP_EOL;                        //
}

/**
 * 01970F7F-6D22-7C70-9146-F20DBD34D6B4
 * 01970F7F-6D23-7CC5-AE60-65A3B42D3883
 * 01970F7F-6D24-7716-99C0-E3729BB0CF0D
 * 01970F7F-6D25-745C-AD87-C0CECA0D102A
 * 01970F7F-6D26-7539-A03C-5270E5C715E9
 */

for ($i = 0; $i < 5; $i++) {
    echo TimeID::idstr() . PHP_EOL;                        // 16 位数字前端不会溢出
}

/**
 * 1748311698723062
 * 1748311698723081
 * 1748311698723095
 * 1748311698723109
 * 1748311698723126
 */