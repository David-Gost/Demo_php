<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ajax_upload_file
 *
 * @property Editer_classification_model $editer_classification_model
 * @property Editer_model $editer_model
 */
class Ajax_editer_features extends AJAX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("custom/Editer_classification_model", "editer_classification_model");
        $this->load->model("custom/Editer_model", "editer_model");
    }

    public function show_group_editer() {

        $sid = $this->input->post('sid');

        $editer_parent_array = $this->editer_classification_model->find_editer_classification_by_parent_id(0);

        $view = '<form id="modify_editer_form" method="post" enctype="multipart/form-data">';
        $view .= '<input type="hidden" id="sid" name="sid" value="' . $sid . '"/>';
        $view .= '<div class="table-responsive"><table class="table table-hover table-condensed table-bordered">';

        foreach ($editer_parent_array as $parent_dto) {


            //---標題
            $view.='<tr>
                      <th colspan="3" style="background-color: #3F729B; color: white; font-size: 18px;">
                        ' . $parent_dto->cname . '
                      </th>
                    </tr>';

            $chid_array = $this->editer_model->find_user_editer_status($sid, $parent_dto->sid);


            foreach ($chid_array as $count => $chid_dto) {

                if ($count == 0) {
                    $view.='<tr>';
                }

                $check_status = $chid_dto->is_open == 0 ? '' : 'checked="checked"';
                $view.='   <td>
                                    <input type="checkbox" name="editer_select[]" class="minimal" value="' . $chid_dto->sid . '" ' . $check_status . ' >
                                    ' . $chid_dto->cname . '
                                </td>';
                if ($count % 3 == 2) {
                    $view.='</tr>';
                }
            }
        }
        $view.='</table></div></form>';

        echo json_encode($view);
        exit;
    }

    public function modify_group_editer() {

        $sid = $this->input->post('sid');
        $classification_sid=$this->input->post('editer_select')?implode(",",$this->input->post('editer_select')):'';
        
        $editer_dto=$this->editer_model->find_editer_by_sid($sid);
        $editer_dto->setUpdatedate('');
        $editer_dto->setClassification_sid($classification_sid);
        
        $this->editer_model->modify_editer($editer_dto);
        
        echo '修改成功！！！';
        exit;
    }

}
