<?php
/**
 * @author YuanZhiHeng
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @license MIT
 * @date: 2018/8/19
 */

namespace Lib\Pdo;


abstract class AbstractPdo
{
    static protected $PDO = null;
    static private $handle = null;
    static private $sql = null;
    static private $bind = null;

    public function __construct(array $config = \Config\LibConfig::PDO['MYSQL'], bool $singleCase = true)
    {
        GetPdo::setConfig($config);

        self::$PDO = GetPdo::getInstance($singleCase);
    }

    /**
     * 调试Params
     * @return mixed
     */
    public function debugDumpParams():void
    {
        self::$handle -> debugDumpParams();
    }

    /**
     * 调试SQL
     */
    public function debugDumpSql():void
    {
        print self::$sql;
    }

    /**
     * 调试Bind
     */
    public function debugDumpBind():void
    {
        foreach (self::$bind as $bindKey => $bindValue)
        {
            print $bindKey . '||' . $bindValue;
        }
    }

    /**
     * 绑定参数
     * @param $handle
     * @param array $bind
     */
    static protected function BindParam(&$handle, array $bind)
    {
        if (isset($bind['bindKey']) && isset($bind['bindValue']))
        {
            $count = count($bind['bindKey']);

            for ($i = 0; $i < $count; ++$i)
            {
                if ($bind['bindKey'][$i] !== null)
                {
                    $handle -> bindParam($bind['bindKey'][$i], $bind['bindValue'][$i]);
                }
            }
        }
    }

    /**
     * 执行PDO
     * @param string $sql
     * @param bool $returnArray
     * @param array $bind
     * @return array|int
     */
    static protected function exec(string $sql, bool $returnArray = true, array $bind = array())
    {
        self::$sql = $sql;

        self::$bind = $bind;

        self::$handle = self::$PDO -> prepare(self::$sql);

        $countBind =  count($bind);

        if ($countBind > 0 && !is_null(self::$bind))
        {
            for ($i = 0; $i < $countBind; ++$i)
            {
                self::BindParam(self::$handle, $bind[$i]);
            }
        }

        self::$handle -> execute();

        return $returnArray? self::$handle -> fetchAll(\PDO::FETCH_ASSOC) : self::$handle -> rowCount();
    }

    /**
     * 数组降维
     * @param $mix
     * @param int $k
     * @return null
     */
    static private function downDimension($mix, $k = 0)
    {
        $downDimension = null;
        $countArray = count($mix);
        for ($i = 0; $i < $countArray; ++$i)
        {
            if (is_array(($mix[$i])))
            {
                $countItem = count($mix[$i]);
                for ($j = 0; $j < $countItem; ++$j)
                {
                    $downDimension[$k++] = $mix[$i][$j];
                }
            }
            else
            {
                $downDimension[$k++] = $mix[$i];
            }
        }
        return $downDimension;
    }

    /**
     * WHERE语句
     * @param array $where
     * @param bool $parameter
     * @param bool $relation
     * @return array|null|string
     * TODO: 2018/8/20
     */
    static protected function where(array $where, bool $parameter = true, bool $relation = false)
    {
        $boolAnd = $boolOr = false;
        $and = $or = null;
        $role = $relation? 'ON' : 'WHERE';

        $count = count($where);

        if ($count === 0)
        {
            if ($parameter === true)
            {
                return null;
            }
            else
            {
                return array();
            }
        }

        if (isset($where['AND']) && !isset($where['OR']))
        {
            $boolAnd = true;
            $and = self::condition($where['AND'], 'AND');
        }

        if (!isset($where['AND']) && isset($where['OR']))
        {
            $boolOr = true;
            $or = self::condition($where['OR'], 'OR');
        }

        if (isset($where['AND']) && isset($where['OR']))
        {
            $boolAnd = true;
            $boolOr = true;
            $and = self::condition($where['AND'], 'AND');
            $or = self::condition($where['OR'], 'OR');
        }

        if (!isset($where['AND']) && !isset($where['OR']))
        {
            $boolOr = true;
            $or = self::condition($where, 'OR');
        }

        if ($boolAnd && $boolOr)
        {
            if ($parameter === true)
            {
                return $role . U32 . implode(U32 . 'OR' . U32, array($and['parameter'], $or['parameter']));
            }
            else
            {
                return array(
                    'bindKey' => self::downDimension(array_merge($and['bindKey'], $or['bindKey'])),
                    'bindValue' => self::downDimension(array_merge($and['bindValue'], $or['bindValue'])),
                );
            }
        }

        if ($boolAnd && !$boolOr)
        {
            if ($parameter === true)
            {
                return $role . U32 . $and['parameter'];
            }
            else
            {
                return array(
                    'bindKey' => self::downDimension($and['bindKey']),
                    'bindValue' => self::downDimension($and['bindValue'])
                );
            }
        }

        if (!$boolAnd && $boolOr)
        {
            if ($parameter === true)
            {
                return $role . U32 . $or['parameter'];
            }
            else
            {
                return array(
                    'bindKey' => self::downDimension($or['bindKey']),
                    'bindValue' => self::downDimension($or['bindValue'])
                );
            }
        }

        return null;
    }

    /**
     * 处理WHERE语句
     * 分离数组, 传递给self::strip()
     * @param array $array
     * @param string $handle
     * @return array|null
     */
    static protected function condition(array $array, string $handle = 'AND')
    {
        $count = count($array);

        if ($count === 0 || empty($array))
        {
            return array(
                'parameter' => null,
                'bindKey' => array(),
                'bindValue' => array(),
            );
        }

        $key = array_keys($array);
        $value = array_values($array);

        $rawParameter = $rawKey = $rawValue = [];

        for ($i = 0; $i < $count; ++$i)
        {
            $strip = self::strip($key[$i], $value[$i]);

            list($rawParameter[], $rawKey[], $rawValue[]) = array($strip['rawParameter'],$strip['rawKey'],$strip['rawValue']);
        }

        return array(
            'parameter' => implode(U32 . $handle . U32, $rawParameter),
            'bindKey' => $rawKey,
            'bindValue' => $rawValue,
        );
    }

    /**
     * 处理WHERE语句
     * 对键名操作符分类
     * @param string $key
     * @param string $value
     * @return array
     */
    static protected function strip(string $key, string $value)
    {
        // 解析键名
        preg_match('/[A-Za-z0-9_.]+/', $key, $column);
        preg_match('/(?<=\[)[^}]+(?=\])/', $key, $pregOp);

        // 解析操作符类型
        $rawParameter = $rawKey = $rawValue = null;

        if (preg_match('/[>=<]+/', $pregOp[0], $op))
        {
            self::commonOperator($key, $value, $column[0], $op[0], $rawKey, $rawValue, $rawParameter);
        }
        elseif (preg_match('/LIKE/i', strtoupper($pregOp[0]), $op))
        {
            self::likeOperator($key, $value, $column[0], $op[0], $rawKey, $rawValue, $rawParameter);
        }
        elseif (preg_match('/IN/i', strtoupper($pregOp[0]), $op))
        {
            self::inOperator($key, $value, $column[0], $op[0], $rawKey, $rawValue, $rawParameter);
        }
        elseif (preg_match('/BETWEEN/i', strtoupper($pregOp[0]), $op))
        {
            self::betweenOperator($key, $value, $column[0], $op[0], $rawKey, $rawValue, $rawParameter);
        }

        return array(
            'rawKey' => $rawKey,
            'rawValue' => $rawValue,
            'rawParameter' => $rawParameter
        );
    }

    /**
     * 处理WHERE语句
     * 普通运算符
     * @param string $key
     * @param string $value
     * @param string $column
     * @param string $op
     * @param string $rawKey
     * @param string $rawValue
     * @param string $rawParameter
     */
    static private function commonOperator(string $key, string $value, string $column, string $op, &$rawKey, &$rawValue, &$rawParameter)
    {
        preg_match('/[A-Za-z0-9_.]{0,}/', $value, $table);

        $pregValue = preg_match('/(?<=\{)[^}]+(?=\})/', $value, $curly);

        if (empty($pregValue))
        {
            // 值无花括号
            $rawKey = ':MD5' . md5($key . $value);
            $rawValue = $value;
            $rawParameter = $column . $op . $rawKey;
        }
        else
        {
            // 值有花括号
            $rawKey = null;
            $rawValue = null;
            $rawParameter = $column . $op . $table[0] . $curly[0];
        }
    }

    /**
     * 处理WHERE语句
     * LIKE运算符
     * @param string $key
     * @param string $value
     * @param string $column
     * @param string $op
     * @param string $rawKey
     * @param string $rawValue
     * @param string $rawParameter
     * TODO: 2018/8/20
     */
    static private function likeOperator(string $key, string $value, string $column, string $op, &$rawKey, &$rawValue, &$rawParameter)
    {
        exit("此功能以后添加");
    }

    /**
     * 处理WHERE语句
     * IN运算符
     * @param string $key
     * @param string $value
     * @param string $column
     * @param string $op
     * @param string $rawKey
     * @param string $rawValue
     * @param string $rawParameter
     */
    static private function inOperator(string $key, string $value, string $column, string $op, &$rawKey, &$rawValue, &$rawParameter)
    {
        $arrayValue = explode(',', $value);

        $countArrayValue = count($arrayValue);

        for ($i = 0; $i < $countArrayValue; ++$i)
        {
            $rawKey[] = ':MD5' . md5($key . $arrayValue[$i]);
            $rawValue[] = $arrayValue[$i];
        }
        $rawParameter = $column . U32 . $op . U32 . '(' . implode(',', $rawKey) . ')';
    }

    /**
     * 处理WHERE语句
     * BETWEEN运算符
     * @param string $key
     * @param string $value
     * @param string $column
     * @param string $op
     * @param string $rawKey
     * @param string $rawValue
     * @param string $rawParameter
     */
    static private function betweenOperator(string $key, string $value, string $column, string $op, &$rawKey, &$rawValue, &$rawParameter)
    {
        $arrayValue = explode(',', $value);

        $countArrayValue = count($arrayValue);

        $countArrayValue !== 2? exit("Between 键值不符规定") : null;

        for ($i = 0; $i < $countArrayValue; ++$i)
        {
            $rawKey[] = ':MD5' . md5($key . $arrayValue[$i]);
            $rawValue[] = $arrayValue[$i];
        }

        $rawParameter = $column . U32 . $op . U32 . implode(U32 . 'AND' . U32, $rawKey);
    }

    /**
     * Join
     * @param string $db
     * @param string $tbA
     * @param string $tbB
     * @param string $type
     * @param array $relation
     * @return string
     */
    static protected function relation(string $db, string $tbA, string $tbB, string $type, array $relation)
    {
        return $db . $tbA . U32 . 'a' . U32 . $type . U32 . 'JOIN' . U32 . $db . '.' . $tbB. U32 . 'b' . U32 . self::where($relation, true, true);
    }

    /**
     * 插入数据预处理
     * @param array $data
     * @param bool $parameter
     * @return array|string
     */
    static protected function preInsert(array $data, bool $parameter = true)
    {
        $count = count($data);

        $key = array_keys($data);

        $value = array_values($data);

        $bindKey = $bindValue = null;

        for ($i = 0; $i < $count; ++$i)
        {
            $bindKey[] = ':md5' . md5($key[$i] . $value[$i]);
            $bindValue[] = $value[$i];
        }

        if ($parameter)
        {
            return '(' . implode(',', $key) . ')' . U32 . 'VALUES' . U32 . '(' . implode(',', $bindKey) . ')';
        }
        else {
            return array(
                'bindKey' => $bindKey,
                'bindValue' => $bindValue,
            );
        }
    }

    /**
     * 更新数据预处理
     * @param array $data
     * @param bool $parameter
     * @return array|string
     */
    static protected function preUpdate(array $data, bool $parameter = true)
    {
        $count = count($data);

        $key = array_keys($data);

        $value = array_values($data);

        $bindKey = $bindValue = $preDate = null;

        for ($i = 0; $i < $count; ++$i)
        {
            $bindKey[] = ':MD5' . md5($key[$i] . $value[$i]);

            $preDate[] = $key[$i] . '=' . $bindKey[$i];

            $bindValue[] = $value[$i];
        }

        if ($parameter)
        {
            return implode(',', $preDate);
        }
        else
        {
            return array(
                'bindKey' => $bindKey,
                'bindValue' => $bindValue,
            );
        }
    }

    /**
     * 列的解析
     * @param array $col
     * @param bool $distinct
     * @return string
     */
    static protected function column(array $col, bool $distinct)
    {
        $column = null;

        if (count($col) === 0)
        {
            $column = '*';
        }
        elseif (in_array('*', $col, true))
        {
            $column = '*';
        }
        else
        {
            $column = implode(',', $col);
        }

        if ($distinct)
        {
            return 'SELECT' . U32 . 'DISTINCT' . U32 . $column;
        }
        else
        {
            return 'SELECT' . U32 . $column;
        }
    }

    /**
     * 排序的解析
     * @param array $order
     * @return null|string
     * TODO: 2018/8/20
     */
    static protected function order(array $order)
    {
        $count = count($order);

        if ($count === 0)
        {
            return null;
        }
        else
        {
            $desc = null;

            for ($i = 0; $i < $count; $i++)
            {
                $desc[] = $order[$i] . U32 . 'DESC';
            }

            return 'ORDER' . U32 . 'BY' . U32 . implode(U32 . 'AND' . U32, $desc);
        }
    }

    /**
     * 限定的解析
     * @param array $limit
     * @return null|string
     */
    static protected function limit(array $limit)
    {
        if (count($limit) === 0)
        {
            return null;
        }
        elseif (count($limit) > 2)
        {
            exit("Limit 写法有误");
        }
        else
        {
            return 'LIMIT' . U32 . implode(',', $limit);
        }
    }
}