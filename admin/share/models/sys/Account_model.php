<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Account_dto $account_dto
 * @property Account_role_model $account_role_model
 */
class Account_model extends AC_Model {

    public function __construct() {

        parent::__construct();


        $this->load->library('dto/Account_dto', 'account_dto');
        $this->_table = 'sys_account';
        $this->_dto = 'Account_dto';
    }

    public function find_by_search($searchword, $groups_sid, $offset = '', $limit = '') {

        $sql = "SELECT
	sys_account.*,sys_groups.cname AS groups_cname 
FROM
	sys_account
INNER JOIN sys_groups ON sys_account.groups_sid = sys_groups.sid         
WHERE
	(sys_account.cname LIKE '%" . $searchword . "%' OR 
        sys_account.phone LIKE '%" . $searchword . "%' OR
        sys_account.address LIKE '%" . $searchword . "%')";


        if ($groups_sid != '' && !empty($groups_sid)) {
            $sql .= " AND sys_account.groups_sid = $degree_sid";
        }

        if ($offset == '' && $limit == '') {
            $data = $this->query_by_sql($sql, '', true);
        } else {
            $data = $this->query_page_by_sql($sql, $offset, $limit, true);
        }

        return $data;
    }

    public function check($username, $password = '') {

        $where = array(
            'account' => $username,
            'password' => $password,
            'isuse >' => '0'
        );

        return $this->find($where)->get();
    }

    public function find_by_groups_sid($groups_sid) {

        $sql = 'select sys_groups.company_sid as groups_company_sid,sys_groups.cname as groups_cname,sys_company.cname as company_cname,sys_account.*
                from sys_account
                inner join sys_groups on sys_account.groups_sid = sys_groups.sid
                inner join sys_company on sys_account.company_sid = sys_company.sid
                ';

        if ($groups_sid != 0) {
            $sql .= ' where sys_account.groups_sid = ' . $groups_sid;
        }

        $sql .= ' order by sequence, sid desc';

        $data = $this->query_by_sql($sql);

        return $data;
    }

    public function account_is_exist($account) {

        $where = array(
            'account' => $account
        );
        return $this->find($where)->count();
    }

    public function account_is_exist_not_sid($account, $sid) {

        $where = array(
            'account' => $account,
            'sid !=' => $sid
        );
        return $this->find($where)->count();
    }

    /**
     *
     * @param Account_dto $account_dto
     * @return int insert_id
     */
    public function add_account(Account_dto $account_dto) {

        $data = object_to_array($account_dto);

        unset($data['group_name']);
        unset($data['company_name']);
        unset($data['job_name']);
        unset($data['confirmpassword']);

        return $this->create($data);
    }

    public function modify_account(Account_dto $account_dto) {

        if ($account_dto->departuredate != '') {
            //???????????????????????????????????????????????????update
            if ($account_dto->departuredate <= date('Y-m-d')) {
                $account_dto->setIsuse('0');
            } else {
                $account_dto->setIsuse('1');
            }
        } else {
            $account_dto->setIsuse('1');
        }

        $data = object_to_array($account_dto);
        unset($data['group_name']);
        unset($data['company_name']);
        unset($data['job_name']);
        unset($data['isuse']);
        unset($data['confirmpassword']);
        if (empty($account_dto->password)) {
            unset($data['password']);
        }

        $affected_rows = $this->update($account_dto->sid, $data);

        return $affected_rows;
    }

    public function modify_profile_account(Account_dto $account_dto) {

        $data = object_to_array($account_dto);

        unset($data['groups_sid']);
        unset($data['company_sid']);
        unset($data['company_title_sid']);
        unset($data['dutydate']);
        unset($data['departuredate']);
        unset($data['isuse']);
        unset($data['group_name']);
        unset($data['company_name']);
        unset($data['job_name']);
        unset($data['confirmpassword']);
        if (empty($account_dto->password)) {
            unset($data['password']);
        }

        $this->db->where('sid', $account_dto->sid)->update($this->_table, $data);

        return $this->db->affected_rows();
    }

    /**
     *
     * @param type $sid
     * @return int affected_rows
     */
    public function del_account($sid) {

        return $this->delete($sid);
    }

    /**
     *
     * @param type $sid
     * @return Account_dto
     */
    public function find_account_by_sid($sid) {

        $where = array(
            'sid' => $sid
        );
        return $this->find($where)->get();
    }

    /**
     * ?????????????????????????????? (?????????????????????)
     * 
     * @param array $select    ???????????????????????????????????????????????????CI????????????
     * @param mixed $isuse       ???????????????isuse????????????????????????????????????????????????CI????????????
     * @return Account_dto 
     */
    public function find_all(array $select = [], $isuse = null) {
        if (count($select) > 0) {
            //???????????????????????????????????????????????????CI????????????
            $this->db->select(implode(',', $select));
        }

        if (!is_null($isuse)) {
            $this->db->where('isuse', $isuse);
        }

        $this->db->where('sid != ', 1);

        $data = $this->all();
        return $data;
    }

    public function find_advisory_list($searchword = '') {

        $sql = "SELECT
	sys_account.*,sys_groups.cname AS groups_cname 
FROM
	sys_account
INNER JOIN sys_groups ON sys_account.groups_sid = sys_groups.sid         
WHERE
	(sys_account.cname LIKE '%" . $searchword . "%' OR 
        sys_account.phone LIKE '%" . $searchword . "%' OR
        sys_account.address LIKE '%" . $searchword . "%')";

        $data = $this->query_by_sql($sql, '', true);
        return $data;
    }

}
