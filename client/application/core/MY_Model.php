<?php

class MY_model extends CI_Model {

    const DB_TABLE = 'abstract';
    const DB_TABLE_PK = 'abstract';
    const DB_TABLE_SK = 'abstract';

    /**
     * Create record.
     */
    public function insert() {
        $this->db->insert($this::DB_TABLE, $this);
        $this->{$this::DB_TABLE_PK} = $this->db->insert_id();
        return $this->{$this::DB_TABLE_PK};
    }

    /**
     * Update record.
     */
    public function update() {
        $this->db->where($this::DB_TABLE_PK, $this->{$this::DB_TABLE_PK});
        $this->db->update($this::DB_TABLE, $this);
    }

    /**
     * Populate from an array or standard class.
     * @param mixed $row
     */
    public function populate($row) {
        foreach ($row as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Load from the database.
     * @param int $id
     */
    public function load($id) {
        $query = $this->db->get_where($this::DB_TABLE, array(
            $this::DB_TABLE_PK => $id,
        ));

        if (sizeof($query->row()) != 0)
            $this->populate($query->row());
        else
            echo FALSE;
    }

    /**
     * Load from the database.
     * @param string $key 
     * @param string $value
     */
    public function load_with_key($key, $value) {
        $query = $this->db->get_where($this::DB_TABLE, array(
            $key => $value
        ));
        if (sizeof($query->row()) != 0)
            $this->populate($query->row());
        else
            echo FALSE;
    }

    /**
     * Delete the current record.
     */
    public function delete() {
        $this->db->delete($this::DB_TABLE, array(
            $this::DB_TABLE_PK => $this->{$this::DB_TABLE_PK},
        ));
        unset($this->{$this::DB_TABLE_PK});
    }

    /**
     * Save the record.
     */
    public function save() {
        if (isset($this->{$this::DB_TABLE_PK})) {
            $this->update();
        } else {
            $this->insert();
        }
    }

    /**
     * Get an array of Models with an optional limit, offset.
     * 
     * @param int $limit Optional.
     * @param int $offset Optional; if set, requires $limit.
     * @param string $where_value.
     * @param string $where_tag Optional; if set requieres  
     * @return array Models populated by database, keyed by PK.
     */
    public function get($limit = 0, $offset = 0, $array_where = array(),$selected_cloumns = "") {
        if ($selected_cloumns != '')
            $this->db->select($this::DB_TABLE . '.' . $selected_cloumns);
        if (sizeof($array_where) > 0) {
            foreach ($array_where as $array_condition) {
                $this->db->where($array_condition['where_tag'], $array_condition['where_value']);
            }
        }
        $this->db->order_by($this::DB_TABLE_PK, 'DESC');
        if ($limit) {
            $query = $this->db->get($this::DB_TABLE, $limit, $offset);
        } else {
            $query = $this->db->get($this::DB_TABLE);
        }
        $ret_val = array();
        $class = get_class($this);
        foreach ($query->result() as $row) {
            $model = new $class;
            $model->populate($row);
            $ret_val[$row->{$this::DB_TABLE_PK}] = $model;
        }
        return $ret_val;
    }

    /**
     * Get an array of Models with an optional limit, offset.
     * @param int $limit Optional.
     * @param int $limit Optional.
     * @param int $offset Optional; if set, requires $limit.
     * @param string $where_value.
     * @param string $where_tag Optional; if set requieres.
     * @param int $count limit 
     * @param int $random  
     * @return array String.
     */
    public function get_random_php($selected_cloumns = "", $limit = 0, $offset = 0, $array_where = array(), $count = 0, $random) {
        if ($selected_cloumns != '')
            $this->db->select($this::DB_TABLE . '.' . $selected_cloumns);
        if (sizeof($array_where) > 0) {
            foreach ($array_where as $array_condition) {
                $this->db->where($array_condition['where_tag'], $array_condition['where_value']);
            }
        }
        if ($limit) {
            $query = $this->db->get($this::DB_TABLE, $limit, $offset);
        } else {
            $query = $this->db->get($this::DB_TABLE);
        }
        $res = $query->result();
     
        if ($random) {
            shuffle($res);
            $res=array_slice($res, 0, $count);
         
            
        } 
        return $res;
    }

    /**
     * @param string $selected_row
     * @param array $join_array
     * @param array $where_array
     * @return \class
     */
    public function get_with_join($selected_cloumns = "", $join_array, $where_array, $limit, $offset, $get_count = FALSE) {
        if ($selected_cloumns != '')
            $this->db->select($this::DB_TABLE . '.' . $selected_cloumns);

        $this->db->from($this::DB_TABLE);
        $previous_table = $this::DB_TABLE;
        foreach ($join_array as $tojoin) {
            $this->db->join($tojoin['table']::DB_TABLE, $tojoin['table']::DB_TABLE . '.' . $tojoin['table']::DB_TABLE_PK . ' = ' . $previous_table . '.' . $tojoin['table']::DB_TABLE_PK);
            $previous_table = $tojoin['table']::DB_TABLE;
        }

        if (sizeof($where_array) > 0) {
            foreach ($where_array as $array_condition) {
                $this->db->where($array_condition['where_tag'], $array_condition['where_value']);
            }
        }
        if (!$get_count) {
            $query = $this->db->get('', $limit, $offset);
            $ret_val = array();
            $class = get_class($this);
            foreach ($query->result() as $row) {
                $model = new $class;
                $model->populate($row);
                $ret_val[$row->{$this::DB_TABLE_PK}] = $model;
            }
            $query->num_rows();
            return $ret_val;
        } else {
            $query = $this->db->get();
            $rowcount = $query->num_rows();
            return $rowcount;
        }
    }

    /**
     * @param type $where_tag
     * @param type $where_value
     */
    public function get_count($array_where) {
        if (sizeof($array_where) > 0) {
            foreach ($array_where as $array_condition) {
                $this->db->where($array_condition['where_tag'], $array_condition['where_value']);
            }
        }
        $query = $this->db->get($this::DB_TABLE);
        $rowcount = $query->num_rows();
        return $rowcount;
    }

    /*     * *
     * check key already exist in bd
     */

    function var_exists($name, $key) {
        $key = preg_replace('/\s+/', '', $key);
        $this->db->where($name, $key);
        $query = $this->db->get($this::DB_TABLE);
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $name
     * @param type $key
     * @param  $skey
     * @param type $id
     * @return boolean
     */
    function var_exists_for_user($name, $key, $skey, $id) {
        $key = preg_replace('/\s+/', '', $key);
        $this->db->where($name, $key);
        $this->db->where($skey, $id);
        $query = $this->db->get($this::DB_TABLE);
        if ($query->num_rows() > 0) {

            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param object $object_to_join
     * @param string $key_user
     * @param int $user_id
     * @param string $key /for where
     * @param int $value /for where
     * @param string $selected 
     * @return boolean/object
     */
    public function var_exists_for_user_join($object_to_join, $key_user, $user_id, $key, $value, $selected) {
        // $this->db->select($object_to_join::DB_TABLE.'.name');
        $this->db->select($selected);
        $this->db->from($this::DB_TABLE);
        $this->db->join($object_to_join::DB_TABLE, $object_to_join::DB_TABLE . '.' . $object_to_join::DB_TABLE_PK . ' = ' . $this::DB_TABLE . '.' . $object_to_join::DB_TABLE_PK);
        if ($key_user != "") {
            $this->db->where($object_to_join::DB_TABLE . '.' . $key_user, $user_id);
        }
        if ($key != "") {
            $this->db->where($this::DB_TABLE . '.' . $key, $value);
        }
        $query = $this->db->get();
        // var_dump($query->row() );
        if ($query->num_rows() > 0) {

            return $query->row();
        } else {
            return false;
        }
    }

}
