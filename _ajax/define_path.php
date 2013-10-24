<?php

    // ini_set('display_errors', 1);
    // error_reporting(E_ALL);

	define('DS', DIRECTORY_SEPARATOR);
    define('PS', PATH_SEPARATOR);
    define('BP', dirname(dirname(__FILE__)));

    if ($_SERVER['DOCUMENT_ROOT'] != (DS. 'var' .DS. 'www')) {
        $_real_path = $_SERVER['DOCUMENT_ROOT'] .DS. 'goldfinger' .DS;
    } else {
        $_real_path = $_SERVER['DOCUMENT_ROOT'] .DS;
    }

    require_once($_real_path . '_ajax/cnx.php');
    require_once($_real_path . '_helpers/Helper.class.php');

?>