<?php

defined('BASEPATH') || exit('Access Denied.');


function is_uploaded($video, $tmp = false) {
	return ($tmp) ? file_exists($video) : file_exists($_FILES[$video]['tmp_name']);
}

function is_video($video, $mime = false) {
	$mimes = array(
		'video/mp4',
		'video/mov',
		'video/flv',
		'video/avi',
		'video/wmv'
	);

	return ($mime) ? in_array($video, $mimes) : in_array(mime_content_type($_FILES[$video]['tmp_name']), $mimes);
}

function generate_slug_id() {
    return strtoupper(uniqid(random_string('alpha', 3)));
}