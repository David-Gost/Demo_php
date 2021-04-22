<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Common_classification_dto $common_classification_dto
 *
 */
class Common_classification_model extends AC_Model {

    public function __construct() {
        parent::__construct();

        $this->load->library("dto/Common_classification_dto", "common_classification_dto");

        $this->_table = 'sys_common_classification';
        $this->_dto = 'Common_classification_dto';
    }

    public function find_common_by_parent_id($parent_sid) {

        $where = array(
            'parent_sid' => $parent_sid
        );
        $data = $this->find($where, 'sequence,sid desc')->all();
        return $data;
    }

    public function find_classification_by_parent_id($parent_sid) {

        $where = array(
            'parent_sid' => $parent_sid
        );
        $data = $this->find($where, 'sequence,sid desc')->all();
        return $data;
    }

    public function count_by_parent_sid($parent_sid) {
        $sql = 'SELECT
	count(sys_common_classification.sid) AS count
	FROM
		sys_common_classification';

        if ($parent_sid != '') {
            $sql .= ' WHERE sys_common_classification.parent_sid = ' . $parent_sid;
        } else {
            $sql .= ' WHERE sys_common_classification.parent_sid = 0 ';
        }

        $data = $this->query_by_sql($sql, '', false);
        return $data->count;
    }

    public function find_classification_by_parent_id_page($parent_sid, $offset, $limit) {

        $sql = 'SELECT
	sys_common_classification.*, ( SELECT
		count(*)
	FROM
		sys_common_classification a
	WHERE
		a.parent_sid = sys_common_classification.sid ) AS page_count
	FROM
		sys_common_classification';

        if ($parent_sid != '') {
            $sql .= ' WHERE sys_common_classification.parent_sid = ' . $parent_sid;
        } else {
            $sql .= ' WHERE sys_common_classification.parent_sid = 0 ';
        }

        $sql .= ' order by sys_common_classification.sequence, sys_common_classification.sid desc';

        $data = $this->query_page_by_sql($sql, $offset, $limit, true);
        return $data;
    }

    public function find_classification_by_sid($sid) {

        $where = array(
            'sid' => $sid
        );
        $data = $this->find($where)->get();

        return $data;
    }

    /**
     *
     * @param Common_classification_dto $common_classification_dto
     * @return int insert_id
     */
    public function add_common_classification(Common_classification_dto $common_classification_dto) {

        $data = object_to_array($common_classification_dto);
//        $this->fb->warn($common_classification_dto, 'dto');
        return $this->create($data);
    }

    /**
     *
     * @param Common_classification_dto $common_classification_dto
     * @return type
     */
    public function modify_common_classification(Common_classification_dto $common_classification_dto) {

        $props = object_to_array($common_classification_dto);
        return $this->update($common_classification_dto->sid, $props);
    }

    /**
     *
     * @param long $sid
     * @return affected_rows
     */
    public function del_common_classification($sid) {
        return $this->delete($sid);
    }

}
