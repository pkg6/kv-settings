<?php

/*
 * This file is part of the pkg6/kv-settings
 *
 * (c) pkg6 <https://github.com/pkg6>
 *
 * (L) Licensed <https://opensource.org/license/MIT>
 *
 * (A) zhiqiang <https://www.zhiqiang.wang>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Pkg6\KvSettings\Drivers;

use Pkg6\KvSettings\Contracts\DriverInterface;

class PDODriver implements DriverInterface
{
    /**
     * @var \PDO
     */
    protected $pdo;
    /**
     * @var string
     */
    protected $table;

    /**
     * PDODriver constructor.
     *
     * @param \PDO $pdo
     * @param string $table
     */
    public function __construct(\PDO $pdo, $table = 'settings')
    {
        $this->pdo = $pdo;
        $this->table = $table;
    }

    public function forget($key)
    {
        $sql = sprintf("DELETE FROM `%s` WHERE `key` = :key", $this->table);
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':key', $key);

        return $stmt->execute();
    }

    public function get($key, $default = null)
    {
        // 准备查询语句
        $sql = sprintf("SELECT `value` FROM `%s` WHERE `key` = :key LIMIT 1", $this->table);
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':key', $key);
        $stmt->execute();
        // 尝试获取结果
        $value = $stmt->fetchColumn();
        // 返回结果或默认值
        return $value !== false ? $value : $default;
    }

    public function has($key)
    {
        // 准备查询语句
        $sql = sprintf("SELECT 1 FROM `%s` WHERE `key` = :key LIMIT 1", $this->table);
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':key', $key);
        $stmt->execute();
        // 检查是否有结果
        return $stmt->fetchColumn() !== false;
    }

    public function set(string $key, $value = null)
    {
        $sql = sprintf("INSERT INTO `%s` (`key`, `value`) VALUES (:key, :value)", $this->table);
        if ($this->has($key)) {
            $sql = sprintf("UPDATE `%s` SET `value` = :value WHERE `key` = :key", $this->table);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':key', $key);
        $stmt->bindParam(':value', $value);

        return $stmt->execute();
    }
}
