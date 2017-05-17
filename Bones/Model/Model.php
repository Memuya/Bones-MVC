<?php
/**
 * Base model that holds generic database functions
 *
 * @author Mehmet Uyanik <mehmet.uyanik@live.com.au>
 */
namespace Bones\Model;

use Bones\Core\DB;

abstract class Model {
    protected $table;

    public function __construct($table) {
        $this->table = $table;
    }

    /**
     * Get a row from a table
     *
     * @param type $id
     * @return \stdClass
     */
    public function get($id) {
        try {
            $q = DB::$db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
            $q->execute([':id' => $id]);
            $count = $q->rowCount();
            $results = $q->fetch();

            $obj = new \stdClass();
            $obj->result = $results;
            $obj->count = $count;
            //echo "<pre>", print_r($obj), "</pre>";
            return $obj;
        } catch(\PDOException $ex) {
            die($ex->getMessage());
        }
    }

    /**
     * Return all rows from a table
     *
     * @return \stdClass
     */
    public function getAll() {
        try {
            $q = DB::$db->query("SELECT * FROM {$this->table}");
            $count = $q->rowCount();
            $results = $q->fetchAll();

            $obj = new \stdClass();
            $obj->result = $results;
            $obj->count = $count;
            //echo "<pre>", print_r($obj), "</pre>";
            return $obj;
        } catch(\PDOException $ex) {
            die($ex->getMessage());
        }
    }

    /**
     * Insert a row into the database
     *
     * @param array $data
     * @return int
     */
    public function create(array $data) {
        if(!empty($data) && is_array($data)) {
            $fields = array_keys($data);
            $values = array_values($data);
        }

        try {
            // Contruct query
            $sql = "INSERT INTO {$this->table} (";
            for($x = 0; $x < count($fields); $x++) {
                $sql .= $fields[$x];
                $sql .= $x !== count($fields) - 1 ? ', ' : null;
            }
            $sql .= ") VALUES (";
            for($x = 0; $x < count($fields); $x++) {
                $sql .= ":{$fields[$x]}";
                $sql .= $x !== count($fields) - 1 ? ', ' : null;
            }
            $sql .= ")";

            // Prepare query
            $q = DB::$db->prepare($sql);
            // The data variable can be passed straight through as its setup
            // exactly as it needs to be
            $q->execute($data);
            $last_id = DB::$db->lastInsertId();

            return $last_id;
        } catch(\PDOException $ex) {
            die($ex->getMessage());
        }
    }

    /**
     * Update a table with new values
     *
     * @param array $data       The data to update in the query
     * @param array $clause     The WHERE clause of the query
     */
    public function update(array $data, array $clause) {
        // Construct the SQL query
        $sql = "UPDATE {$this->table} SET";

        $data_fields = array_keys($data);

        if(!empty($data) && is_array($data)) {
            for($x = 0; $x < count($data); $x++) {
                if($x !== 0) {
                    $sql .= ", $data_fields[$x] = ?";
                } else {
                    $sql .= " $data_fields[$x] = ?";
                }
            }
        }

        $clause_fields = array_keys($clause);

        if(!empty($clause) && is_array($clause)) {
            $sql .= " WHERE";
            for($x = 0; $x < count($clause); $x++) {
                if($x !== 0) {
                    $sql .= " AND $clause_fields[$x] = ?";
                } else {
                    $sql .= " $clause_fields[$x] = ?";
                }
            }
        }

        // Run the query
        try {
            $q = DB::$db->prepare($sql);

            $bindCount = 1;
            foreach($data as $d) {
                $q->bindValue($bindCount, $d);
                $bindCount++;
            }

            foreach($clause as $c) {
                $q->bindValue($bindCount, $c);
                $bindCount++;
            }

            $q->execute();
            $last_id = DB::$db->lastInsertId();

            return $last_id;

            //echo $sql;
            //$e = array_merge($data, $clause);
            //print_r($e);


        } catch (\PDOException $ex) {
            die($ex->getMessage());
        }
    }

    /**
     * Delete a record from a table
     *
     * @param int $id
     * @return int
     */
    public function delete($id) {
        try {
            $q = DB::$db->prepare("DELETE FROM {$this->table} WHERE id = :id");
            $q->execute([':id' => $id]);

            return $q->rowCount();
        } catch (\PDOException $ex) {
            die($ex->getMessage());
        }
    }
}
