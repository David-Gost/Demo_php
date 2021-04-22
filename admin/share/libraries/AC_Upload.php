<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Image_moo $image_moo
 */
class AC_Upload extends CI_Upload {

    private $_ci;

    public function __construct($config = array()) {
        parent::__construct($config);

        $this->_ci = &get_instance();
    }

    public function errors() {
        return $this->display_errors();
    }

    public function has_uploaded_file($file) {
        return array_key_exists($file,$_FILES) && $_FILES[$file]['error'] !== UPLOAD_ERR_NO_FILE;
    }

    /**
	 * Finalized Data Array
	 *
	 * Returns an associative array containing all of the information
	 * related to the upload, allowing the developer easy access in one array.
	 *
	 * @param	string	$index
	 * @return	mixed
	 */
	public function data($index = NULL){
        $data = parent::data();
        $data['full_path'] = $data['file_path'].checkfile_classfoldername($data['file_name']).$data['file_name'];
        if (!empty($index))
		{
			return isset($data[$index]) ? $data[$index] : NULL;
		}else{
            return $data;
        }
    }

    /**
     *
     * @param type $field
     * @param type $use_watermark 
     * @param type $mw
     * @param type $mh
     * @param type $position
     * @param type $transparency
     * @param type $colour
     * @param type $quality
     *
     * @return boolean
     */
    public function do_upload(
    $field = 'userfile', $use_watermark = false, $mw = 800, $mh = 600, $position = 3, $transparency = 50, $colour = "#ffffff", $quality = 75
    ) {
        if (is_array($field)){
            $_file = $field;
        }else{
            // Is $_FILES[$field] set? If not, no reason to continue.
            if (isset($_FILES[$field])) {
                $_file = $_FILES[$field];
            } // Does the field name contain array notation?
            elseif (($c = preg_match_all('/(?:^[^\[]+)|\[[^]]*\]/', $field, $matches)) > 1) {
                $_file = $_FILES;
                for ($i = 0; $i < $c; $i++) {
                    // We can't track numeric iterations, only full field names are accepted
                    if (($field = trim($matches[0][$i], '[]')) === '' OR ! isset($_file[$field])) {
                        $_file = null;
                        break;
                    }

                    $_file = $_file[$field];
                }
            }
        }

        if (!isset($_file)) {
            $this->set_error('upload_no_file_selected');
            return false;
        }

        // Is the upload path valid?
        if (!$this->validate_upload_path()) {
            // errors will already be set by validate_upload_path() so just return FALSE
            return false;
        }

        // Was the file able to be uploaded? If not, determine the reason why.
        if (!is_uploaded_file($_file['tmp_name'])) {
            $error = isset($_file['error']) ? $_file['error'] : 4;

            switch ($error) {
                case UPLOAD_ERR_INI_SIZE:
                    $this->set_error('upload_file_exceeds_limit');
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $this->set_error('upload_file_exceeds_form_limit');
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $this->set_error('upload_file_partial');
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $this->set_error('upload_no_file_selected');
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $this->set_error('upload_no_temp_directory');
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $this->set_error('upload_unable_to_write_file');
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $this->set_error('upload_stopped_by_extension');
                    break;
                default:
                    $this->set_error('upload_no_file_selected');
                    break;
            }

            return false;
        }

        // Set the uploaded data as class variables
        $this->file_temp = $_file['tmp_name'];
        $this->file_size = $_file['size'];

        // Skip MIME type detection?
        if ($this->detect_mime !== false) {
            $this->_file_mime_type($_file);
        }

        $this->file_type = preg_replace('/^(.+?);.*$/', '\\1', $this->file_type);
        $this->file_type = strtolower(trim(stripslashes($this->file_type), '"'));
        $this->file_name = $this->_prep_filename($_file['name']);
        $this->file_ext = $this->get_extension($this->file_name);
        $this->client_name = $this->file_name;

        // Is the file type allowed to be uploaded?
        if (!$this->is_allowed_filetype()) {
            $this->set_error('upload_invalid_filetype');
            return false;
        }

        // if we're overriding, let's now make sure the new name and type is allowed
        if ($this->_file_name_override !== '') {
            $this->file_name = $this->_prep_filename($this->_file_name_override);

            // If no extension was provided in the file_name config item, use the uploaded one
            if (strpos($this->_file_name_override, '.') === false) {
                $this->file_name .= $this->file_ext;
            } else {
                // An extension was provided, let's have it!
                $this->file_ext = $this->get_extension($this->_file_name_override);
            }

            if (!$this->is_allowed_filetype(true)) {
                $this->set_error('upload_invalid_filetype');
                return false;
            }
        }

        // Convert the file size to kilobytes
        if ($this->file_size > 0) {
            $this->file_size = round($this->file_size / 1024, 2);
        }

        // Is the file size within the allowed maximum?
        if (!$this->is_allowed_filesize()) {
            $this->set_error('upload_invalid_filesize');
            return false;
        }

        // Are the image dimensions within the allowed size?
        // Note: This can fail if the server has an open_basedir restriction.
        if (!$this->is_allowed_dimensions()) {
            $this->set_error('upload_invalid_dimensions');
            return false;
        }

        // Sanitize the file name for security
        $this->file_name = $this->_CI->security->sanitize_filename($this->file_name);

        // Truncate the file name if it's too long
        if ($this->max_filename > 0) {
            $this->file_name = $this->limit_filename_length($this->file_name, $this->max_filename);
        }

        // Remove white spaces in the name
        if ($this->remove_spaces === true) {
            $this->file_name = preg_replace('/\s+/', '_', $this->file_name);
        }

        /*
         * Validate the file name
         * This function appends an number onto the end of
         * the file if one with the same name already exists.
         * If it returns false there was a problem.
         */
        $this->orig_name = $this->file_name;

        if ($this->overwrite === false) {
            $this->file_name = $this->set_filename($this->upload_path, $this->file_name);

            if ($this->file_name === false) {
                return false;
            }
        }

        /*
         * Run the file through the XSS hacking filter
         * This helps prevent malicious code from being
         * embedded within a file. Scripts can easily
         * be disguised as images or other file types.
         */
        if ($this->xss_clean && $this->do_xss_clean() === false) {
            $this->set_error('upload_unable_to_write_file');
            return false;
        }

        /*
         * Move the file to the final destination
         * To deal with different server configurations
         * we'll attempt to use copy() first. If that fails
         * we'll use move_uploaded_file(). One of the two should
         * reliably work in most environments
         */
        if (!@copy(
            $this->file_temp, $this->upload_path . checkfile_classfoldername($this->file_name) . $this->file_name
                )
        ) {
            if (!@move_uploaded_file($this->file_temp, $this->upload_path . checkfile_classfoldername($this->file_name) . $this->file_name)) {
                $this->set_error('upload_destination_error');
                return false;
            }
        }

        if ($use_watermark) {

            $this->watermark($mw, $mh, $position, $transparency, $colour, $quality);

            if ($this->_ci->image_moo->errors) {
                $this->set_error($this->image_moo->display_errors());
                return false;
            }
        }

        /*
         * Set the finalized image dimensions
         * This sets the image width/height (assuming the
         * file was an image). We use this information
         * in the "data" function.
         */
        $this->set_image_properties($this->upload_path . $this->file_name);

        return true;
    }

    /**
     * @param $mw
     * @param $mh
     * @param $position
     * @param $transparency
     * @param $colour
     * @param $quality
     */
    private function watermark($mw, $mh, $position, $transparency, $colour, $quality) {
        $upload_file = $this->upload_path . checkfile_classfoldername($this->file_name) . $this->file_name;
        $watermark_img = $this->_ci->config->item('watermark_img');
        $this->_ci->image_moo
                ->load($upload_file)
                ->load_watermark(get_upload_base_path() . get_upload_default_folder() . $watermark_img)
                ->set_background_colour($colour)
                ->set_jpeg_quality($quality)
                ->set_watermark_transparency($transparency)
                ->watermark($position)
                ->save($upload_file, true)
                ->load($upload_file)
                ->resize($mw, $mh)
                ->save($upload_file, true);
    }

}
