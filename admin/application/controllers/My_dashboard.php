<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * @property Account_model $account_model
 * @property Module_model $module_model
 */
class My_dashboard extends AC_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("sys/Account_model", "account_model");
        $this->load->model("sys/Module_model", "module_model");
    }

    private function validation_message()
    {

        $error_message = [];

        $imagezoom = new ImageZoom();
        $imagezoom->outputJpeg();
        $imagezoom->inputBase64 = true;

        $count = $this->account_model->account_is_exist_not_sid($this->input->post('account'), $this->input->post('sid'));
        if ($count > 0)
        {
            $error_message[] = '帳號已有人使用，不可重複!!';
        }

        if (!check_account($this->input->post('account')))
        {
            $error_message[] = '帳號欄位空白或格式錯誤!!';
        }

        if ($this->input->post('password') != $this->input->post('confirmpassword'))
        {
            $error_message[] = ' 密碼與確認密碼不一樣!!';
        }

        if (empty($this->input->post('cname')))
        {
            $error_message[] = ' 姓名欄位不得空白!!';
        }

        if (!check_tel($this->input->post('tel')))
        {
            $error_message[] = ' 電話欄位格式錯誤!!';
        }

        if (!check_mobile($this->input->post('phone')))
        {
            $error_message [] = ' 手機欄位格式錯誤!!';
        }

        if (!check_email($this->input->post('email')))
        {
            $error_message[] = ' E-Mail格式錯誤!!';
        }

        if ($this->upload->has_uploaded_file('picinfo'))
        {
            if (check_image_file($_FILES['picinfo']))
            {
                $pic_data = $this->input->post('hid_base64_pic');
                if ($pic_data)
                {
                    $new_pic = str_replace('data:image/png;base64,', '', $pic_data);
                    if ($new_pic)
                    {
                        $imagezoom->sourceBase64 = $new_pic;
                        $result = $imagezoom->makepic();
                    }
                    else
                    {
                        $result = makepic_to_base64($_FILES['picinfo']['tmp_name'], 300, 300, FALSE);
                    }
                }
                else
                {
                    $result = makepic_to_base64($_FILES['picinfo']['tmp_name'], 300, 300, FALSE);
                }

                if (empty($result))
                {
                    $error_message[] = '個人圖片上傳失敗!!';
                }
                else
                {
                    $this->account_dto->setPicinfo($result);
                    if (is_file(base_url(get_fullpath_with_file($this->input->post('origin_picinfo')))))
                    {
                        del_file($this->input->post('origin_picinfo'));
                    }
                }
            }
            else
            {
                $error_message[] = '圖片格式錯誤!!';
            }
        }
        else
        {
            $this->account_dto->setPicinfo($this->input->post('origin_picinfo'));
        }

        return $error_message;
    }

    private function prepare_dto()
    {

        $this->account_dto->setSid($this->input->post('sid'));
        $this->account_dto->setAccount($this->input->post('account'));
        $this->account_dto->setPassword($this->input->post('password'));
        $this->account_dto->setCname($this->input->post('cname'));
        $this->account_dto->setCname_en($this->input->post('cname_en'));
        $this->account_dto->setEmail($this->input->post('email'));
        $this->account_dto->setTel($this->input->post('tel'));
        $this->account_dto->setPhone($this->input->post('phone'));
        $this->account_dto->setBirthday($this->input->post('birthday'));
        $this->account_dto->setCity_sid($this->input->post('city_sid'));
        $this->account_dto->setArea_sid($this->input->post('area_sid'));
        $this->account_dto->setPost_code($this->input->post('post_code'));
        $this->account_dto->setAddress($this->input->post('address'));
        $this->account_dto->setContact_name($this->input->post('contact_name'));
        $this->account_dto->setContact_phone($this->input->post('contact_phone'));
        $this->account_dto->setContact_relationship($this->input->post('contact_relationship'));
        $this->account_dto->setRemark($this->input->post('remark'));

        return $this->account_dto;
    }

    //--------------------------------個人資料修改-------------------------------------
    public function profile()
    {

        $this->mTitle = "個人資料修改";
        $this->mViewFile = 'admin/profile';
    }

    public function merge_profile()
    {

        $account_dto = $this->prepare_dto();
        $error_message = $this->validation_message();

        if (!empty($error_message))
        {

            $this->mTitle = '個人資料修改';
            set_warn_alert($error_message);
            $this->mViewFile = 'admin/profile';
            $this->mViewData['account_dto'] = $account_dto;

            return;
        }

        $rows = $this->account_model->modify_profile_account($account_dto);

        if (empty($rows))
        {
            set_warn_alert('修改失敗!!');
            $this->mTitle = "個人資料修改";
            $this->mViewData['account_dto'] = $account_dto;
            $this->mViewFile = 'admin/profile';
        }
        else
        {

            set_success_alert('修改成功!!');

            $this->account_dto = $this->account_model->find_account_by_sid($account_dto->sid);

            if (sizeof($this->account_dto))
            {
                $this->module_dto = $this->module_model->find_module_by_account($this->account_dto->groups_sid);
                $account_array = object_to_array($this->account_dto);
                $module_array = object_to_array($this->module_dto);
                $append_array = array_merge($account_array, array("module" => $module_array));

                login_session_user($append_array);
            }
            redirect('My_dashboard/profile');
        }
    }
}