<?php

    if (isset($_POST['single_user'])) {

        // default path value
        require_once('define_path.php');

        // returns all users
        $_result = array();

        $data = array('user_id' => htmlentities($_POST['single_user']));

        $_sql_users = 'SELECT user.user_id, user.user_avatar, user.user_name, day.day_date, status.status_name
                       FROM user
                       INNER JOIN day
                       ON user.user_created_at = day.day_id
                       INNER JOIN status
                       ON user.user_status_id = status.status_id
                       WHERE user.user_id = :user_id';

        $_req_users = $pdo->prepare($_sql_users);
        $_req_users->execute($data);

        // first we get the users

        // traduction for french
        include_once($_real_path . '_lang/trad_french.php');

        $_row_count = 0;
        while ($_row = $_req_users->fetch( PDO::FETCH_ASSOC )) {

            $_result[$_row_count]['user_avatar'] = 'ressources' . DS . 'avatars' . DS . htmlentities($_POST['single_user']) . DS . $_row['user_avatar'];
            if (!file_exists('../' . DS . $_result[$_row_count]['user_avatar'])){
                $_result[$_row_count]['user_avatar'] = 'ressources' . DS . 'avatars' . DS . 'default/default.png';
            }
            $_result[$_row_count]['user_id'] = $_row['user_id'];
            $_result[$_row_count]['user_name'] = $_row['user_name'];

            $_result[$_row_count]['status_name'] = $_row['status_name'];

            // format date for the day of the first sin :HIHIHIHI:
            $day = getdate(strtotime($_row['day_date']));
            $_result[$_row_count]['created_at_day'] = $day['mday'];
            $_result[$_row_count]['created_at_month'] = $day['mon'];
            $_result[$_row_count]['created_at_year'] = $day['year'];

            // Value for selected language
            $_helper = new Helper();
            $_result[$_row_count]['created_at_jour'] = $_helper->getArrayValue($day['weekday'], $_jours);
            $_result[$_row_count]['created_at_mois'] = $_helper->getArrayValue($day['mon'], $_mois);
            $_result[$_row_count]['user_created_at_raw'] = $_row['day_date'];

            // TODO : improve by avoiding two requests
            // fingers count for the user
            $_sql_fingers_count = 'SELECT finger_counter_count FROM finger_counter
                                       WHERE finger_counter.finger_counter_user = :user';

            $_req_fingers = $pdo->prepare($_sql_fingers_count);
            $_req_fingers->execute( array('user'=> $_row['user_id']) );

            // TODO: this will be removed in case user is created once finger is shown :LOL:
            // in case user doesn't have fingers yet
            $_count_fingers = $_req_fingers->rowCount($_sql_fingers_count);

            if ($_count_fingers < 1) {

                $_result[$_row_count]['finger_counter_count'] = 0;
            } else {

                while ($_row_fingers = $_req_fingers->fetch( PDO::FETCH_ASSOC )) {
                    $_result[$_row_count]['finger_counter_count'] += $_row_fingers['finger_counter_count'];
                }

                // update user status with total fingers count
                $_sql_get_status = 'SELECT status.status_id, status.status_name, status.status_count FROM status';
                $_req_get_status = $pdo->query($_sql_get_status);

                while ($_row_status = $_req_get_status->fetch( PDO::FETCH_ASSOC )) {
                    // check status id's before updating...stop
                    if ($_result[$_row_count]['finger_counter_count'] >= $_row_status['status_count']) {
                        $_result[$_row_count]['status_name'] = $_row_status['status_name'];

                        $_sql_status_update = 'UPDATE user SET user_status_id = "' .$_row_status['status_id']. '"
                                               WHERE user.user_id = "' .$_row['user_id']. '"';
                        $_req_status_update = $pdo->query($_sql_status_update);

                    } else {
                        // nothing just go on
                    }
                }

                // close connection
                $_req_get_status->closeCursor();
                $_req_get_status = NULL;
            }

            // close connection
            $_req_fingers->closeCursor();
            $_req_fingers = NULL;
        }

        // close connection
        $_req_users->closeCursor();
        $_req_users = NULL;

        echo json_encode($_result);
    }

?>