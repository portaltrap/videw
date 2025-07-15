<?php

defined('BASEPATH') || exit('Access Denied.');

function get_language() {
    $language = get_cookie('siu-lang-idiom');
    if(!$language) {
        $lang_cookie = array(
            'name' => 'siu-lang-idiom',
            'value' => 'english',
            'expire' => time() + (10 * 365 * 24 * 60 * 60),
            'path' => '/'
        );

        set_cookie($lang_cookie);

        $language = 'english';
    }

    return $language;
}

function set_language($idiom) {
    $lang_cookie = array(
        'name' => 'siu-lang-idiom',
        'value' => $idiom,
        'expire' => time() + (10 * 365 * 24 * 60 * 60),
        'path' => '/'
    );

    set_cookie($lang_cookie);

    return $idiom;
}