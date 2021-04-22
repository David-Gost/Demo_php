<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Cityarea_dto $cityarea_dto
 *
 */
class Cityarea_model extends AC_Model {

    public function __construct() {
        parent::__construct();

        $this->load->library("dto/Cityarea_dto", "cityarea_dto");

        $this->_table = 'sys_cityarea';
        $this->_dto = 'Cityarea_dto';
    }

    /**
     *
     *
     * @return string
     */
    public function get_cityarea_by_parent_id($parent_id, $first_option_value = '0', $first_option_text = '---- 請選擇 ----') {

        $cityarea_array = [];

        if (empty($parent_id)) {
            $cityarea_array = [
                $first_option_value => $first_option_text
            ];
        } else {
            $cityarea_array = [
                $first_option_value => $first_option_text
            ];

            $where = array('parent_sid' => $parent_id);
            $cityarea_list = $this->find($where)->all();

            foreach ($cityarea_list as $cityarea_dto) {

                $cityarea_array[$cityarea_dto->sid] = $cityarea_dto->area_name;
            }
        }

        return $cityarea_array;
    }

    public function find_cityarea_by_parent_id($parent_sid) {

        $where = array(
            'parent_sid' => $parent_sid
        );
        $data = $this->find($where, 'sequence,sid')->all();
        return $data;
    }

    public function find_cityarea_by_sid($sid) {

        $where = array(
            'sid' => $sid
        );
        $data = $this->find($where)->get();

        return $data;
    }

    /**
     *
     * @param Cityarea_dto $cityarea_dto
     * @return int insert_id
     */
    public function add_cityarea(Cityarea_dto $cityarea_dto) {

        $data = object_to_array($cityarea_dto);
//        $this->fb->warn($cityarea_dto, 'dto');
        return $this->create($data);
    }

    /**
     *
     * @param Cityarea_dto $cityarea_dto
     * @return type
     */
    public function modify_cityarea(Cityarea_dto $cityarea_dto) {

        $props = object_to_array($cityarea_dto);
        return $this->update($cityarea_dto->sid, $props);
    }

    /**
     *
     * @param Cityarea_dto $cityarea_dto
     * @return type
     */
    public function del_cityarea(Cityarea_dto $cityarea_dto) {

        $props = object_to_array($cityarea_dto);
        return $this->delete($props);
    }

    public function delete_by_sid($sid) {

        return $this->delete($sid);
    }

    /**
     * 
     * @param type $city_sid
     * @param type $area_sid
     * @return array Cityarea_dto
     */
    public function get_post_code($city_sid, $area_sid) {

        $where = array(
            'parent_sid' => $city_sid,
            'sid' => $area_sid
        );
        return $this->find($where)->get();
    }

    public function find_cityarea_by_name($name) {

        $sql = "select * from sys_cityarea where area_name like '%$name%'";

        return $this->query_by_sql($sql, '', false);
    }

}
