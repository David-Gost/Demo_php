<?php

defined('BASEPATH') OR exit('No direct script access allowed');


//設定額外的plugin ex:'video,lineheight';
$config['extraPlugins'] = 'video';

//修正html5 video無法顯示問題
$config['extraAllowedContent'] = 'video [*]{*}(*);source [*]{*}(*)';

//設定ckfinder存取資料夾
$config['backends_root'] = '/Applications/XAMPP/xamppfiles/htdocs/resource/';

//設定寬度
$config['width'] = '100%';
//設定高度
$config['height'] = '450';
