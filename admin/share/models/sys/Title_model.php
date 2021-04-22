<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @property Title_dto $title_dto
 *
 */
class Title_model extends AC_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();


        $this->load->library("dto/Title_dto", "title_dto");

        $this->_table = 'sys_title';
        $this->_dto = 'Title_dto';
    }

    /**
     * 
     * @return title_dto 
     */
    public function find_all() {

        $data = $this->all();
        return $data;
    }

    /**
     * 
     * @return title_dto 
     */
    public function count_all() {

        $data = $this->count();
        return $data;
    }

    public function find_title_by_all_page($offset, $limit) {

        $data = $this->paginate($offset, $limit)->all();
        return $data;
    }

    /**
     *
     * @param Title_dto $title_dto
     * @return int insert_id
     */
    public function add_title(Title_dto $title_dto) {

        $data = object_to_array($title_dto);

        return $this->create($data);
    }

    public function modify_title(Title_dto $title_dto) {

        $data = object_to_array($title_dto);

        $affected_rows = $this->update($title_dto->sid, $data);

        return $affected_rows;
    }

    /**
     *
     * @param type $sid
     * @return int affected_rows
     */
    public function del_title($sid) {

        return $this->delete($sid);
    }

    /**
     *
     * @param type $sid
     * @return Title_dto
     */
    public function find_title_by_sid($sid) {

        $where = array(
            'sid' => $sid
        );
        return $this->find($where)->get();
    }

    public function find_title($search) {

        $sql = 'SELECT
	sys_title.*
FROM
	sys_title
WHERE
	url = "' . $search . '"';

        $data = $this->query_by_sql($sql, '', false);

        return $data;
    }

}
