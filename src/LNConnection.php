<?php

namespace sccp\base;

use yii\db\Connection;
use yii\db\Command;

class LNConnection extends Connection
{
    /**
     * 在执行 SQL 执行替换
     * @param $sql
     * @param $params
     * @return Command
     */
    public function createCommand($sql = null, $params = [])
    {
        if ($sql !== null && $sql && stripos($sql, 'UUID') !== false) {
            $sql = str_ireplace('UUID_SHORT()', 'TIME_ID()', $sql);
            $sql = str_ireplace('UUID()', 'TIME_ID()', $sql);
        }

        return parent::createCommand($sql, $params);
    }
}