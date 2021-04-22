<?php


/**
 * <p>建立裁切modal</p>
 * @param type $output_width
 * @param type $output_height
 * @return type
 */
function create_image_madal($output_width, $output_height) {

    $ci = &get_instance();
    $ci->load->library("Crop_image", "crop_image");
    
    $crop_option=new Crop_option();
    $crop_option->setOutput_height($output_height);
    $crop_option->setOutput_width($output_width);
    
    return $ci->crop_image->create_crop_view($crop_option);
}

/**
 * <p>建立單一視窗</p>
 * @param type $id
 * @param type $img_src
 * @param type $image_width
 * @param type $image_height
 * @return type
 */
function create_image_view($id, $img_src,$image_width,$image_height) {
    $ci = &get_instance();
    $ci->load->library("Crop_image", "crop_image");

    return $ci->crop_image->create_image_view($id, $img_src,$image_width,$image_height);
}
