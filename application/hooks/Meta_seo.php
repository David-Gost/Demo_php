<?php

function meta_seo_override()
{
    $CI = &get_instance();
    $uri_string = $CI->uri->uri_string();

    $find_ajax_string = strpos($uri_string, "ajax");
    $find_api_string = strpos($uri_string, "api/");
    if ($find_ajax_string === false && $find_api_string === false) {
        global $OUT;
        $final_html = $OUT->final_output;
        $head_html = '';
        $head_start = strpos($final_html, '<head');
        $head_end = 0;
        if ($head_start !== false) {
            $head_end = strpos($final_html, '</head', $head_start);
            $head_html = substr($final_html, $head_start + 6, $head_end - $head_start - 6);
        }

        if (strpos($head_html, '<meta name="keywords') === false) {


            $base_url = base_url();

//             $head_html .= "
// <link rel=\"shortcut icon\" href=\"$base_url/admin/resource/images/$metadata->sysico\"/>
// <link rel=\"bookmark\" href=\"$base_url/admin/resource/images/$metadata->sysico\"/>
// <meta name=\"keywords\" content=\"$metadata->metakeywords\">
// <meta name=\"description\" content=\"$metadata->metadescription\">
// <meta name=\"company\" content=\"$metadata->metacompany\">
// <meta name=\"copyright\" content=\"$metadata->metacopyright\">
// <meta name=\"creation-date\" content=\"$metadata->metacreationdate\">
// <meta name=\"author\" content=\"$metadata->metaauthor\">
// <meta name=\"reply-to\" content=\"$metadata->metaauthoremail\">
// <meta name=\"url\" content=\"$metadata->metaauthorurl\">
// <meta name=\"rating\" content=\"$metadata->metarating\">
// <meta name=\"robots\" content=\"$metadata->metarobots\">
// <meta name=\"revisit-after\" content=\"$metadata->metarevisitafter\">
// <meta http-equiv=\"pragma\" content=\"$metadata->metapragma\"> 
// <meta http-equiv=\"cache-control\" content=\"$metadata->metacache_control\"> 
// <meta http-equiv=\"expires\" content=\"$metadata->metaexpires\">";
        }
        $OUT->final_output = substr($final_html, 0, $head_start + 6);
        // $OUT->final_output .= $head_html;
        $OUT->final_output .= substr($final_html, $head_end);
    }
}
