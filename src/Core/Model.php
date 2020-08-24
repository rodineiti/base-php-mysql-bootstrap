<?php

namespace Src\Core;

class Model
{
    protected $db;
    protected $table;
    protected $order;
    protected $error;

    public function __construct($table)
    {
        global $db;
        $this->db = $db;
        $this->table = $table;
        $this->order = null;
    }

    public function read($all = false, $columns = ["*"], $where = [], $in = null, $limit = null, $offset = null)
    {
        $query = "SELECT " . implode(",", $columns) . " FROM " . $this->table;

        if (count($where)) {
            $fields = array_keys($where);
            $wheres = array();

            foreach ($fields as $field) {
                $wheres[] = $field . " = ?";
            }

            $query .= " WHERE " . implode(" AND ", $wheres);
        }

        if ($in && count($in) === 3) {
            if (strpos($query, "WHERE") === false) {
                $query .= " WHERE " . $in[0] . " {$in[1]} (" . implode(",", $in[2]) . ") ";
            } else {
                $query .= " AND " . $in[0] . " {$in[1]} (" . implode(",", $in[2]) . ") ";
            }
        }

        if ($this->order) {
            $query .= " ORDER BY {$this->order} ";
        }

        if ($limit) {
            $query .= " LIMIT {$limit} ";
        }

        if ($offset) {
            $query .= " OFFSET {$offset} ";
        }

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute(array_values($where));

            if (!$stmt->rowCount()) {
                return null;
            }

            return ($all ? $stmt->fetchAll(\PDO::FETCH_OBJ) : $stmt->fetchObject());
        } catch (\PDOException $exception) {
            $this->error = $exception;
            return null;
        }
    }

    public function readJoin($all = false, $columns = ["*"], $where = [], $join = null, $in = null, $limit = null, $offset = null)
    {
        $query = "SELECT " . implode(",", $columns) . " FROM " . $this->table;

        if ($join) {
            $query .= " {$join[0]} ON ({$join[1]}) ";
        }

        if (count($where)) {
            $fields = array_keys($where);
            $wheres = array();

            foreach ($fields as $field) {
                $wheres[] = $field . " = ?";
            }

            $query .= " WHERE " . implode(" AND ", $wheres);
        }

        if ($in && count($in) === 3) {
            if (strpos($query, "WHERE") === false) {
                $query .= " WHERE " . $in[0] . " {$in[1]} (" . implode(",", $in[2]) . ") ";
            } else {
                $query .= " AND " . $in[0] . " {$in[1]} (" . implode(",", $in[2]) . ") ";
            }
        }

        if ($this->order) {
            $query .= " ORDER BY {$this->order} ";
        }

        if ($limit) {
            $query .= " LIMIT {$limit} ";
        }

        if ($offset) {
            $query .= " OFFSET {$offset} ";
        }

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute(array_values($where));

            if (!$stmt->rowCount()) {
                return null;
            }

            return ($all ? $stmt->fetchAll(\PDO::FETCH_OBJ) : $stmt->fetchObject());
        } catch (\PDOException $exception) {
            $this->error = $exception;
            return null;
        }
    }

    public function customQuery($query = null, $all = true)
    {
        if ($query) {
            try {
                if ($this->order) {
                    $query .= " ORDER BY {$this->order} ";
                }

                $stmt = $this->db->prepare($query);
                $stmt->execute();

                if (!$stmt->rowCount()) {
                    return null;
                }

                return ($all ? $stmt->fetchAll(\PDO::FETCH_OBJ) : $stmt->fetchObject());
            } catch (\PDOException $exception) {
                $this->error = $exception;
                return null;
            }
        }
        return null;
    }

    public function findById($id, $columns = ["*"])
    {
        return $this->read(false, $columns, ["id" => $id]);
    }

    public function count($columns = ["*"], $where = [], $in = null)
    {
        $query = "SELECT " . implode(",", $columns) . " FROM " . $this->table;

        if (count($where)) {
            $fields = array_keys($where);
            $wheres = array();

            foreach ($fields as $field) {
                $wheres[] = $field . " = ?";
            }

            $query .= " WHERE " . implode(" AND ", $wheres);
        }

        if ($in && count($in) === 3) {
            if (strpos($query, "WHERE") === false) {
                $query .= " WHERE " . $in[0] . " {$in[1]} (" . implode(",", $in[2]) . ") ";
            } else {
                $query .= " AND " . $in[0] . " {$in[1]} (" . implode(",", $in[2]) . ") ";
            }
        }

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute(array_values($where));

            return $stmt->rowCount();
        } catch (\PDOException $exception) {
            $this->error = $exception;
            return null;
        }
    }

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

    public function error(): ?\PDOException
    {
        return $this->error;
    }
}