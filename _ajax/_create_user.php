<?php

    /* set the cache expire to one day */
    session_cache_expire(86400);
    // store some useful information for reuse and avoid useless calls on db
    session_start();


    if ( (isset($_POST['user_name']) && !empty($_POST['user_name'])) &&
            (isset($_POST['user_first_name']) && !empty($_POST['user_first_name'])) &&
                (isset($_POST['user_avatar']) && !empty($_POST['user_avatar'])) ) {

        // get today
        require_once($_real_path . '_ajax/get_today.php');

        // returns all users
        $_result = array();

        $datas = array('user_name'  => htmlentities($_POST['user_name']),
                  'user_first_name' => htmlentities($_POST['user_first_name'])
                 );

        $_sql_duplicate_user = 'SELECT user.user_name, user.user_first_name FROM user
                                WHERE user.user_name = :user_name
                                AND user.user_first_name = :user_first_name';
        $_req_duplicate_user = $pdo->prepare($_sql_duplicate_user);
        $_req_duplicate_user->execute($datas);
        $_is_req_duplicate_user = $_req_duplicate_user->rowCount($_sql_duplicate_user);

        $_req_duplicate_user->closeCursor();
        $_req_duplicate_user = NULL;

        // user already exists
        if ($_is_req_duplicate_user > 0) {

            $_result['results'] = array('result' => false,  'message' => 'Cet utilisateur existe déjà.');
        }  else {

            // Create user
            $_avatar = htmlentities($_POST['user_avatar']);

            $datas = array('user_name' => htmlentities($_POST['user_name']),
                  'user_first_name'    => htmlentities($_POST['user_first_name']),
                  'user_avatar'        => $_avatar
                 );

            $_sql_user = 'INSERT INTO user (user_id, user_age, user_name, user_first_name, user_status_id, user_created_at, user_avatar)
                          VALUES (null, 18, :user_name, :user_first_name, 0, "' .$_SESSION['today_id']. '" , :user_avatar)';


            $_req_user = $pdo->prepare($_sql_user);
            $_req_user->execute($datas);
            $_is_created = $_req_user->rowCount($_sql_user);
            $_last_id = $pdo->lastInsertId();

            // user creation succeded
            if ($_is_created == 1) {

                $_avatar_file_url = '';

                if (isset($_POST['user_avatar']) && !empty($_POST['user_avatar'])) {

                    $_uploaded_img_dir = $_real_path . 'ressources' . DS . 'avatars' . DS . $_last_id;
                    $_avatar_file_name = $_last_id . '_avatar';

                    $_file_uploaded_temp = explode('/', str_replace('//', '', $_POST['user_avatar']));
                    $_file_uploaded_ext_temp = $_file_uploaded_temp[sizeof($_file_uploaded_temp) - 1];
                    // $_file_uploaded_ext = explode('.', $_file_uploaded_ext_temp)[1]; // only for php >= 5.4
                    $_file_uploaded_ext = explode('.', $_file_uploaded_ext_temp);
                    $_file_extenstion = $_file_uploaded_ext[sizeof($_file_uploaded_ext) - 1];
                    $_avatar_file_url = $_uploaded_img_dir . DS . $_avatar_file_name . '.' . $_file_extenstion;
                    $_avatar_on_db = $_last_id . '_avatar' . '.' . $_file_extenstion;

                    // chmod($_SERVER['DOCUMENT_ROOT']  . DS . 'ressources', 0777);
                    // chmod($_SERVER['DOCUMENT_ROOT']  . DS . 'ressources' . DS . 'avatars', 0777);
                    // chmod($_uploaded_img_dir, 0777);

                    // create user avatar image upload directory if there is any
                    if (!is_dir($_uploaded_img_dir)) {
                        $oldumask = umask(0);
                        // $_uploaded_img_dir_clean = $_uploaded_img_dir;
                        mkdir($_uploaded_img_dir, 0777);
                        umask($oldumask);
                    }

                    // copy file to server succeded
                    if (copy($_POST['user_avatar'], $_avatar_file_url)) {

                        $data_url = array('avatar_url' => $_avatar_on_db);
                        // update user avatar on db
                        $_sql_set_avatar = 'UPDATE user SET user.user_avatar = :avatar_url WHERE user.user_id = "'.$_last_id.'"';
                        $_req_set_avatar = $pdo->prepare($_sql_set_avatar);
                        $_req_set_avatar->execute($data_url);
                        $_is_avatar_set = $_req_set_avatar->rowCount($_sql_set_avatar);

                        // avatar string successfully updated on database
                        if ($_is_avatar_set > 0) {
                            $_result['results'] = array('result' => true, 'message' => $datas['user_first_name'] . ' crée avec succès', 'redirect_user' => htmlentities($_POST['user_name']));
                        }

                        $_req_set_avatar->closeCursor();
                        $_req_set_avatar = NULL;

                       // copy file to server failed
                    }  else {
                        $_result['results'] = array ('result' => false, 'message' => 'Image non sauvegardé dans la base');
                    }

                }
              // user creation failed
            } else {

                $_result['results'] = array('result' => false, 'message' => $datas['user_first_name'] . ' n\'a pas pu être crée dans la base.');
            }

            $_req_user->closeCursor();
            $_req_user = NULL;
        }

        echo json_encode($_result);
    } else {

        $_result['results'] = array('result' => false, 'message' => 'Merci de ne pas envoyer des valeurs vides.');
        echo json_encode($_result);
    }

?>