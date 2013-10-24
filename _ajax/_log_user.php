<?php

    /* set the cache expire to one day */
    session_cache_expire(86400);
    // store some useful information for reuse and avoid useless calls on db
    session_start();


    if ( (isset($_POST['user_login']) && !empty($_POST['user_login'])) &&
            (isset($_POST['user_pwd']) && !empty($_POST['user_pwd'])) ) {

        // default path value
        require_once('define_path.php');

        // get today
        require_once($_real_path . '_ajax/get_today.php');

        // returns all users
        $_result['results'] = array();

        $datas = array('user_login'  => htmlentities($_POST['user_login']),
                        'user_pass'  => md5(htmlentities($_POST['user_pwd'])));

        $_sql_log_user = 'SELECT auth.auth_login FROM auth WHERE auth.auth_login = :user_login
                          AND auth.auth_pass = :user_pass';
        $_req_log_user = $pdo->prepare($_sql_log_user);
        $_req_log_user->execute($datas);
        $_is_user_logged = $_req_log_user->rowCount($_sql_log_user);
        $_user_logged = false;

        // user found, now we check the password
        if ($_is_user_logged == 1) {
            $_user_logged = true;
            // set sessions vars
            $_SESSION['is_user_logged'] = true;
        }

        $_result['row_count'] = $_is_user_logged;
        $_result['user_logged'] = $_user_logged;

        if ($_user_logged) {
            $_result['results'] = array('result' => true, 'message' => 'Bienvenu, Oh '.$datas['user_login'], 'redirect' => true);
        } else if (!$_user_logged) {
            $_result['results'] = array('result' => false, 'message' => 'Mauvais Login/passw', 'redirect' => false);
        }

        $_req_log_user->closeCursor();
        $_req_log_user = NULL;

        echo json_encode($_result);

    } else {

        echo json_encode(array('result' => false, 'message' => 'Valeurs vides envoyées.'));
    }

?>