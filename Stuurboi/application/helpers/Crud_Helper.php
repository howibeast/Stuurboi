<?php

/**
 * Insert data to the table specified
 * @param type $table
 * @param array $data
 * @return type
 */
function insert($table, Array $data) {
    $CI = & get_instance();
    $insertId = -1;
    if (!empty($data)) {
        $insert = $CI->db->insert($table, $data);  // insert 
        if ($insert) {
            $insertId = $CI->db->insert_id();  // id
        }
    }
    return $insertId;  // return id
}

function getRow($table, $id) {
    $CI = & get_instance();
    if ($id > 0) {
        $query = $CI->db->get_where($table, array('id' => $id));
        return $query->row_array();
    } else {
        return null;
    }
}

function getWhere($table, $where, $or = null) {
    $CI = & get_instance();
    if (!empty($where)) {
        $CI->db->select('*');
        $CI->db->from($table);
        $CI->db->where($where);
        if($or != null)$CI->db->or_where($or);
        $query = $CI->db->get();
        return $query->result_array();
    } else {
        return null;
    }
}

function getSingle($table, $where) {
    $CI = & get_instance();
    if (count($where) > 0) {
        $query = $CI->db->get_where($table, $where);
        if ($query) {
            return $query->row_array();
        }
    }
    return null;
}

function getRows($table) {
    $CI = & get_instance();
    $query = $CI->db->get($table);
    if ($query) {
        return $query->result_array();
    } else {
        return null;
    }
}

function updateRow($table, Array $updateData, $id) {
    $CI = & get_instance();
    if (count($data) > 0 && $id > 0) {
        $update = $CI->db->update($table, $data, array('id' => $id));
        return $update ? true : false;
    }
}

function update_where($table, Array $data, Array $where) {
    $CI = & get_instance();
    if (count($data) > 0 && count($where) > 0) {
        $update = $CI->db->update($table, $data, $where);
        return $update ? true : false;
    }
    return false;
}

function updateRows($table, Array $data) {
    $CI = & get_instance();
    if (count($data) > 0) {
        $update = $CI->db->update($table, $data);
        return $update ? true : false;
    }
}

function deleteRow($table, $id) {
    $CI = & get_instance();
    $delete = $CI->db->delete($table, array('id' => $id));
    return $delete ? TRUE : FALSE;
}

function deleteRows($table) {
    $CI = & get_instance();
    $delete = $CI->db->delete($table);
    return $delete ? TRUE : FALSE;
}

function getSingleJoin($table, $jointTable, $idOne, $idtwo, $data = array(), $where= array(), $joinType = 'INNER') {
    $CI = & get_instance();
    if( !empty($table) && !empty($idOne) && !empty($idtwo) && !empty($jointTable)) {
        if (count($data) > 0) {
            $CI->db->select($data);
        } else {
            $CI->db->select('*');
        }
        $CI->db->from($table);
        $CI->db->join($jointTable, $idOne."=".$idtwo, $joinType);
        count($where) > 0? $CI->db->where($where): "";
        $data = $CI->db->get();
         return $data->row_array();
    }
    return $data;
}

function getJoin($table, $jointTable, $idOne, $idtwo, $data = array(), $where = array(), $joinType = 'INNER')  {
    $CI = & get_instance();
    if( !empty($table) && !empty($idOne) && !empty($idtwo) && !empty($jointTable)) {
        if (count($data) > 0) {
            $CI->db->select($data);
        } else {
            $CI->db->select('*');
        }
        $CI->db->from($table);
        $CI->db->join($jointTable, $idOne."=".$idtwo, $joinType);
        count($where) > 0? $CI->db->where($where): "";
        $data = $CI->db->get();
         return $data->result_array();
    }
    return $data;
}
