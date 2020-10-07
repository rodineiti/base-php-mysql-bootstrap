<?php

namespace Src\Core;

/**
 * Class Model
 * @package Src\Core
 */
class Model
{
    /**
     * @var \PDO
     */
    protected $db;
    /**
     * @var string
     */
    protected $table;
    /**
     * @var string
     */
    protected $query;
    /**
     * @var string
     */
    protected $params;
    /**
     * @var int
     */
    protected $limit;
    /**
     * @var int
     */
    protected $offset;
    /**
     * @var string
     */
    protected $order;
    /**
     * @var array
     */
    protected $where;
    /**
     * @var array
     */
    protected $join;
    /**
     * @var array
     */
    protected $leftJoin;
    /**
     * @var array
     */
    protected $rightJoin;
    /**
     * @var \PDOException|null
     */
    protected $error;

    /**
     * Model constructor.
     * @param string $table
     */
    public function __construct(string $table)
    {
        $this->db = Connection::getInstance();
        $this->table = $table;
    }

    /**
     * @param array $columns
     * @return Model
     */
    public function select(array $columns = ["*"]): Model
    {
        $this->query = "SELECT " . implode(",", $columns) . " FROM " . $this->table;
        return $this;
    }

    /**
     * @param string $select
     * @return Model
     */
    public function selectRaw(string $select): Model
    {
        $this->query = "SELECT " . $select . " FROM " . $this->table;
        return $this;
    }

    /**
     * @param $id
     * @param array $columns
     * @return array|mixed|null
     */
    public function findById($id, $columns = ["*"])
    {
        return $this->select($columns)->where("id", "=", $id)->first();
    }

    /**
     * @return mixed|null
     */
    public function first()
    {
        try {

            $this->clauseJoins();
            $this->clauseWhere();

            $stmt = $this->db->prepare($this->query . $this->order . $this->limit . $this->offset);
            $stmt->execute($this->params);

            if (!$stmt->rowCount()) {
                return null;
            }

            return $stmt->fetchObject();
        } catch (\PDOException $exception) {
            $this->error = $exception;
            return null;
        }
    }

    /**
     * @return array|null
     */
    public function all()
    {
        try {

            $this->clauseJoins();
            $this->clauseWhere();

            $stmt = $this->db->prepare($this->query . $this->order . $this->limit . $this->offset);
            $stmt->execute($this->params);

            if (!$stmt->rowCount()) {
                return null;
            }

            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $exception) {
            $this->error = $exception;
            return null;
        }
    }

    /**
     * @param int $limit
     * @return Model
     */
    public function limit(int $limit): Model
    {
        $this->limit = " LIMIT {$limit} ";
        return $this;
    }

    /**
     * @param int $offset
     * @return Model
     */
    public function offset(int $offset): Model
    {
        $this->offset = " OFFSET {$offset} ";
        return $this;
    }

    /**
     * @param string $column
     * @return Model
     */
    public function order(string $column): Model
    {
        $this->order = " ORDER BY {$column} ";
        return $this;
    }

    /**
     * @param string $table
     * @param mixed ...$args
     * @return Model
     */
    public function join(string $table, ...$args): Model
    {
        if (!$args[0] instanceof \Closure && $args[0] && $args[1]) {
            $this->join[] = " INNER JOIN {$table} ON ({$table}.{$args[0]} = {$this->table}.{$args[1]}) ";
        }
        return $this;
    }

    /**
     * @param string $table
     * @param mixed ...$args
     * @return Model
     */
    public function leftJoin(string $table, ...$args): Model
    {
        if (!$args[0] instanceof \Closure && $args[0] && $args[1]) {
            $this->leftJoin[] = " LEFT OUTER JOIN {$table} ON ({$table}.{$args[0]} = {$this->table}.{$args[1]}) ";
        }
        return $this;
    }

    /**
     * @param string $table
     * @param mixed ...$args
     * @return Model
     */
    public function rightJoin(string $table, ...$args): Model
    {
        if (!$args[0] instanceof \Closure && $args[0] && $args[1]) {
            $this->rightJoin[] = " RIGHT OUTER JOIN {$table} ON ({$table}.{$args[0]} = {$this->table}.{$args[1]}) ";
        }
        return $this;
    }

    /**
     * @param string $field
     * @param string $operator
     * @param $value
     * @return Model
     */
    public function where(string $field, string $operator, $value): Model
    {
        $this->where[] = " {$field} {$operator} :" . str_replace(".", "_", $field);
        if (strtolower($operator) === "like") {
            $params = "{$field}=%{$value}%";            
        } else {
            $params = "{$field}={$value}";
        }
        $this->concatParams($params);
        parse_str($params, $this->params);
        return $this;
    }

    /**
     * @param string $whereRaw
     * @return Model
     */
    public function whereRaw(string $whereRaw): Model
    {
        $this->where[] = " {$whereRaw} ";
        return $this;
    }

    /**
     * @param string $field
     * @param array $values
     * @return Model
     */
    public function whereIn(string $field, array $values = []): Model
    {
        $this->where[] = " {$field} IN (" . implode(",", $values) . ")";
        return $this;
    }

    /**
     * @param string $field
     * @param array $values
     * @return Model
     */
    public function whereNotIn(string $field, array $values = []): Model
    {
        $this->where[] = " {$field} NOT IN (" . implode(",", $values) . ")";
        return $this;
    }

    /**
     * @param string|null $params
     */
    private function concatParams(?string &$params): void
    {
        if ($this->params) {
            foreach ($this->params as $key => $value) {
                $params .= "&{$key}={$value}";
            }
        }
    }

    /**
     * clauseJoins
     * @return void
     */
    private function clauseJoins(): void
    {
        if ($this->join) {
            foreach ($this->join as $key => $value) {
                $this->query .= $value;
            }
        }

        if ($this->leftJoin) {
            foreach ($this->leftJoin as $key => $value) {
                $this->query .= $value;
            }
        }

        if ($this->rightJoin) {
            foreach ($this->rightJoin as $key => $value) {
                $this->query .= $value;
            }
        }
    }

    /**
     * clauseWhere
     * @return void
     */
    private function clauseWhere(): void
    {
        if ($this->where) {
            foreach ($this->where as $key => $value) {
                if (strpos($this->query, "WHERE") === false) {
                    $this->query .= " WHERE {$value} ";
                } else {
                    $this->query .= " AND {$value} ";
                }
            }
        }
    }

    /**
     * @return int|null
     */
    public function count(): ?int
    {
        try {
            $this->clauseJoins();
            $this->clauseWhere();
            $stmt = $this->db->prepare($this->query);
            $stmt->execute($this->params);
            return $stmt->rowCount();
        } catch (\PDOException $exception) {
            $this->error = $exception;
            return null;
        }
    }

    /**
     * @return string
     */
    public function toSql(): string
    {
        $this->clauseJoins();
        $this->clauseWhere();
        return $this->query . $this->order . $this->limit . $this->offset;
    }

    /**
     * @param array $data
     * @return string|null
     */
    public function insert($data = [])
    {
        if (count($data)) {
            $values = array();
            for ($i = 0; $i < count($data); $i++) {
                $values[] = "?";
            }
            $query = sprintf("INSERT INTO {$this->table} (%s) VALUES (%s)",
                implode(",", array_keys($data)), implode(",", $values));

            try {
                $stmt = $this->db->prepare($query);
                $stmt->execute(array_values($data));

                return $this->db->lastInsertId();
            } catch (\PDOException $exception) {
                $this->error = $exception;
                return null;
            }
        }

        return null;
    }

    /**
     * @param array $data
     * @param array $where
     * @return int|null
     */
    public function update($data = [], $where = [])
    {
        if (count($data) && count($where)) {
            $fields = array_keys($data);
            $values = array_values($data);
            $dataSet = array();
            $wheres = array();

            foreach ($fields as $field) {
                $dataSet[] = $field . " = ?";
            }

            foreach (array_keys($where) as $w) {
                $wheres[] = $w . " = ?";
            }

            $query = sprintf("UPDATE {$this->table} SET %s WHERE %s",
                implode(",", $dataSet), implode(" AND ", $wheres));

            try {
                $stmt = $this->db->prepare($query);
                $stmt->execute(array_merge(array_values($values), array_values($where)));
                return ($stmt->rowCount() === 0 ? 1 : $stmt->rowCount());
            } catch (\PDOException $exception) {
                $this->error = $exception;
                return null;
            }
        }

        return null;
    }

    /**
     * @param array $where
     * @return bool
     */
    public function delete($where = [])
    {
        if (count($where)) {
            foreach (array_keys($where) as $w) {
                $wheres[] = $w . " = ?";
            }

            $query = sprintf("DELETE FROM {$this->table} WHERE %s", implode(" AND ", $wheres));

            try {
                $stmt = $this->db->prepare($query);
                $stmt->execute(array_values($where));
                return true;
            } catch (\PDOException $exception) {
                $this->error = $exception;
                return false;
            }
        }

        return false;
    }

    /**
     * @return \PDOException|null
     */
    public function error(): ?\PDOException
    {
        return $this->error;
    }
}