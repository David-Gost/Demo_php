<?php defined('BASEPATH') or exit('No direct script access allowed');
include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';
class Sys_files_store_dto extends Base_dto
{
    public function __construct()
    {
        parent::__construct();
    }
public $name;    //檔案實際存在server內的名稱
public $file_ext;    //副檔名
public $size;    //為檔案大小，單位為Byte
public $file_hash;    //檔案的 hash，使用 MD5
public $file_path;    //檔案實際儲存路徑
public $remark;    //被哪個功能認領了 (只是用來記錄，未來要刪除不必要檔案時，方便尋找還有沒有人要使用這個檔案)
public $upload_token;    //這個上傳的檔案屬於誰的 (用一個token標記，等使用者真的按下確認後，才是真的確認上傳並儲存)
public $sigh;    //此檔案是甚麼狀態 (’save’=已按下確認儲存,’temporary’=未按下確認儲存,’same’=有人上傳過一模一樣的檔案並儲存，不能隨意刪除)
public $action_sigh;    //標記預計要做的動作 (檔案上傳後雖然已經在server內，但只有在使用者確認儲存後才會實際關聯需要的表or刪掉他) (’save’=儲存,’delete’=刪除)
public $upload_from;    //可以分辨從前台上傳還是後台上傳

function getName() {
 return $this->name; 
}

function getFile_ext() {
 return $this->file_ext; 
}

function getSize() {
 return $this->size; 
}

function getFile_hash() {
 return $this->file_hash; 
}

function getFile_path() {
 return $this->file_path; 
}

function getRemark() {
 return $this->remark; 
}

function getUpload_token() {
 return $this->upload_token; 
}

function getSigh() {
 return $this->sigh; 
}

function getAction_sigh() {
 return $this->action_sigh; 
}

function getUpload_from() {
 return $this->upload_from; 
}

function setName($name) {
 $this->name = $name; 
}

function setFile_ext($file_ext) {
 $this->file_ext = $file_ext; 
}

function setSize($size) {
 $this->size = $size; 
}

function setFile_hash($file_hash) {
 $this->file_hash = $file_hash; 
}

function setFile_path($file_path) {
 $this->file_path = $file_path; 
}

function setRemark($remark) {
 $this->remark = $remark; 
}

function setUpload_token($upload_token) {
 $this->upload_token = $upload_token; 
}

function setSigh($sigh) {
 $this->sigh = $sigh; 
}

function setAction_sigh($action_sigh) {
 $this->action_sigh = $action_sigh; 
}

function setUpload_from($upload_from) {
 $this->upload_from = $upload_from; 
}
}