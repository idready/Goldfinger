<?php

    // ini_set('display_errors', 1);
    // error_reporting(E_ALL);

	define('DS', DIRECTORY_SEPARATOR);
    define('PS', PATH_SEPARATOR);
    define('BP', dirname(dirname(__FILE__)));
<<<<<<< HEAD
    define('BASE_URL', 'idready.fr/goldfinger');
=======
    define('BASE_URL', 'idready.fr');
>>>>>>> 2c1f8d62fce2755fd7783248b81a312586743e2e

    if ($_SERVER['DOCUMENT_ROOT'] != (DS. 'var' .DS. 'www')) {
        $_real_path = $_SERVER['DOCUMENT_ROOT'] .DS. 'goldfinger' .DS;
    } else {
        $_real_path = $_SERVER['DOCUMENT_ROOT'] .DS;
    }

    // define current base url
<<<<<<< HEAD
    ($_SERVER['SERVER_NAME'] != BASE_URL) ? $current_base_url = $_SERVER['SERVER_NAME'] .':'. $_SERVER['SERVER_PORT'] : $current_base_url = BASE_URL;
=======
    ($_SERVER['SERVER_NAME'] != BASE_URL) ? $current_base_url = $_SERVER['SERVER_NAME'] .':'. $_SERVER['SERVER_PORT'] : $current_base_url = BASE_URL . '/goldfinger';
>>>>>>> 2c1f8d62fce2755fd7783248b81a312586743e2e

    define('CURRENT_BASE_URL', $current_base_url);

    require_once($_real_path . '_ajax/cnx.php');
    require_once($_real_path . '_helpers/Helper.class.php');

?>