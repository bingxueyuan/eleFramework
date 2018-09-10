<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/8/19
 */

namespace Lib\Pdo;


class Pdo extends AbstractPdo
{
    /**
     * 开启事务
     */
    public function begin()
    {
        parent::$PDO -> beginTransaction();
    }

    /**
     * 检查是否在一个事务内
     */
    public function isBegin()
    {
        parent::$PDO -> inTransaction();
    }

    /**
     * 回滚一个事务
     */
    public function rollBack()
    {
        parent::$PDO -> rollBack();
    }

    /**
     * 提交一个事务
     */
    public function commit()
    {
        parent::$PDO -> commit();
    }

    /**
     * MySQL版本
     * @return string
     */
    public function version()
    {
        return parent::exec('SELECT' . U32 . 'version()' . U32 . 'AS' . U32 . 'VERSION', true)[0]['VERSION'];
    }

    /**
     * 返回所有数据库
     * @return array
     */
    public function databases()
    {
        return parent::exec('SHOW' . U32 . 'DATABASES', true);
    }

    /**
     * 查看MySQL安装路径
     * @return string
     */
    public function programPath()
    {
        return parent::exec('SELECT' . U32 . '@@basedir' . U32 . 'AS' . U32 . 'PROGRAM_PATH', true)[0]['PROGRAM_PATH'];
    }

    /**
     * 查看Mysql物理数据存放路径
     * @return string
     */
    public function dataPath()
    {
        return parent::exec('SELECT' . U32 . '@@datadir' . U32 . 'AS' . U32 . 'DATA_PATH', true)[0]['DATA_PATH'];
    }

    /**
     * 创建库
     * @param string $db
     * @return array|int
     */
    public function createDB(string $db)
    {
        return parent::exec('CREATE' . U32 . 'DATABASE' . U32 . $db, false);
    }

    /**
     * 新建库.表
     * @param string $db
     * @param string $tb
     * @param string $col
     * @param string $charset
     * @return array|int
     */
    public function createTB(string $db, string $tb, string $col, string $charset = '')
    {
        return parent::exec('CREATE' . U32 . 'TABLE' . U32 . 'IF' . U32 . 'NOT' . U32 .
            'EXISTS' . U32 . $db . $tb . '(' . $col . ')' . $charset, false);
    }

    /**
     * 清空库.表
     * @param string $db
     * @param string $tb
     * @return array|int
     */
    public function truncateTB(string $db, string $tb)
    {
        return parent::exec('TRUNCATE' . U32 . 'TABLE' . U32 . $db . $tb, false);
    }

    /**
     * 删除库.表
     * @param string $db
     * @param string $tb
     * @return array|int
     */
    public function dropTB(string $db, string $tb)
    {
        return parent::exec('DROP' . U32 . 'TABLE' . U32 . $db . $tb, false);
    }

    /**
     * 删除库
     * @param string $db
     * @return array|int
     */
    public function dropDB(string $db)
    {
        return parent::exec('DROP' . U32 . 'DATABASE' . U32 . $db, false);
    }

    /**
     * SELECT查询
     * @param string $db
     * @param string $tb
     * @param array $col
     * @param array $where
     * @param array $order
     * @param array $limit
     * @param bool $distinct
     * @return array|int
     */
    public function select(string $db, string $tb, array $col = array(), array $where = array(), array $order = array(), array $limit = array(), bool $distinct = false)
    {
        $sql = parent::column($col, $distinct) . U32 . 'FROM' . U32 . $db . $tb . U32 . parent::where($where, true) . U32 . parent::order($order) . U32 . parent::limit($limit);

        return parent::exec($sql, true, array(parent::where($where, false)));
    }

    /**
     * Join查询
     * @param string $db
     * @param string $tbA
     * @param string $tbB
     * @param array $col
     * @param string $type
     * @param array $relation
     * @param array $where
     * @param array $order
     * @param array $limit
     * @param bool $distinct
     * @return array|int
     */
    public function join(string $db, string $tbA, string $tbB, array $col = array(), string $type = 'INNER', array $relation = array(), array $where = array(), array $order = array(), array $limit = array(), bool $distinct = false)
    {
        $sql = parent::column($col, $distinct) . U32 . 'FROM' . U32 . parent::relation($db, $tbA, $tbB, $type, $relation) . U32 . parent::where($where, true) . U32 . parent::order($order) . U32 . parent::limit($limit);

        $relationBind = parent::where($relation, false);
        $whereBind = parent::where($where, false);

        return parent::exec($sql, true, array(empty($relationBind)? array() : $relationBind, empty($whereBind)? array() : $whereBind));
    }

    /**
     * 库.表插入数据
     * @param string $db
     * @param string $tb
     * @param array $data
     * @return array|int
     */
    public function insert(string $db, string $tb, array $data)
    {
        $sql = 'INSERT' . U32 . 'INTO' . U32 . $db . $tb . U32 . parent::preInsert($data, true);

        return parent::exec($sql, false, array(parent::preInsert($data, false)));
    }

    /**
     * 库.表删除数据
     * @param string $db
     * @param string $tb
     * @param array $where
     * @return array|int
     */
    public function delete(string $db, string $tb, array $where)
    {
        $sql = 'DELETE' . U32 . 'FROM' . U32 . $db . $tb . U32 . parent::where($where, true);

        return parent::exec($sql, false, array(parent::where($where, false)));
    }

    /**
     * 库.表更新数据
     * @param string $db
     * @param string $tb
     * @param array $data
     * @param array $where
     * @return array|int
     */
    public function update(string $db, string $tb, array $data, array $where)
    {
        $sql = 'UPDATE' . U32 . $db . $tb . U32 . 'SET' . U32 . parent::preUpdate($data, true) . U32 . parent::where($where);

        return parent::exec($sql, false, array(parent::preUpdate($data, false), parent::where($where, false)));
    }

    /**
     * 运行原生SQL
     * @param string $raw
     * @param bool $returnArray
     * @return array|int
     */
    public function raw(string $raw, bool $returnArray = true)
    {
        return parent::exec($raw, $returnArray);
    }
}