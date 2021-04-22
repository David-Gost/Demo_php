<?php

/**
 * @property Mailinfo_dto $mailinfo_dto
 */
class Mailinfo_model extends AC_Model {

    public function __construct() {
        parent::__construct();

        $this->load->library("dto/Mailinfo_dto", "mailinfo_dto");
        $this->_table = 'sys_mailinfo';
        $this->_dto = 'Mailinfo_dto';
    }

    /**
     * 
     * @return  array Mailinfo_dto
     */
    public function find_all() {

        $data = $this->all();
        return $data;
    }

    /**
     * 
     * @param type $sid
     * @return int affected_rows
     */
    public function del_mailinfo($sid) {

        return $this->delete($sid);
    }
    
    
        /**
     * 
     * @param type $classification_sid
     * @return Mailinfo_dto
     */
    public function find_by_classification_sid($classification_sid) {

        $where = array(
            'classification_sid' => $classification_sid
        );
        return $this->find($where)->all();
    }

    /**
     * 
     * @param type $sid
     * @return Mailinfo_dto
     */
    public function find_by_id($sid) {

        $where = array(
            'sid' => $sid
        );
        return $this->find($where)->get();
    }
    
     /**
     * 
     * @param Mailinfo_dto $mailinfo_dto
     * @return int insert_id
     */
    public function add_mailinfo(Mailinfo_dto $mailinfo_dto) {

        $data = object_to_array($mailinfo_dto);
//        $this->fb->warn($mailinfo_dto, 'dto');
        return $this->create($data);
    }
    
    /**
     * 
     * @param Mailinfo_dto $mailinfo_dto
     * @return int affected_rows
     */
    public function modify_mailinfo(Mailinfo_dto $mailinfo_dto) {

        $props = object_to_array($mailinfo_dto);
        return $this->update($mailinfo_dto->sid, $props);
    }


}
