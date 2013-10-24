<?php

    /* set the cache expire to one day */
    session_cache_expire(86400);
    // store some useful information for reuse and avoid useless calls on db
    session_start();


    if (isset($_POST['user_id'])) {

        $_user_id = htmlentities($_POST['user_id']);

        // default path value
        require_once('define_path.php');

        // get today
        require_once($_real_path . '_ajax/get_today.php');

        /* Now let's dance */

        /*-------- GET TODAY FINGER COUNTER FOR THE USER --------*/

        // 1. select today finger counter
        $_counter_datas = array(':today_id' => $_SESSION['today_id'], ':user_id' => $_user_id);
        $_sql_day_id = 'SELECT finger_counter.finger_counter_count
                        FROM finger_counter
                        WHERE finger_counter.finger_counter_day = :today_id
                        AND finger_counter.finger_counter_user = :user_id LIMIT 1';

        $_req_day = $pdo->prepare($_sql_day_id);
        $_req_day->execute($_counter_datas);
        $_row_count = $_req_day->rowCount($_sql_day_id);

        // Avoid bugs and only apply when one row returned
        if ($_row_count == 1) {

            while ($_row = $_req_day->fetch( PDO::FETCH_ASSOC )) {
                $finger_counter = $_row['finger_counter_count'];
            }

            $_req_day->closeCursor();
            $_req_day = NULL;

            // increment variable to insert
            $_update_data = array(':finger_counter'     => ($finger_counter+1),
                                  ':finger_counter_day' => $_SESSION['today_id']);

            // now update the value
            $_sql_counter_update = 'UPDATE finger_counter
                                    SET finger_counter.finger_counter_count = :finger_counter
                                    WHERE finger_counter.finger_counter_day = :finger_counter_day';

            $_req_update_counter = $pdo->prepare($_sql_counter_update);
            $_req_update_counter->execute($_update_data);
            $_row_result = $_req_update_counter->rowCount($_sql_counter_update);

            ($_row_result > 0) ? $_result = array('result' => 'success') : $_result = array('result' => 'failure');

        // add the row if not created yet for today
        } else {


            $datas = array( ':user_id'=> $_user_id, ':today'=> $_SESSION['today_id']);

            // create counter
            $_add_counter_sql = 'INSERT INTO finger_counter (finger_counter_id, finger_counter_user,
                                 finger_counter_count, finger_counter_day)
                                 VALUES (null, :user_id, 1, :today)';

            $_req_insert_count = $pdo->prepare($_add_counter_sql);
            $_req_insert_count->execute($datas);
            $_row_result = $_req_insert_count->rowCount($_add_counter_sql);

            ($_row_result == 1) ? $_result = array('result' => 'success') : $_result = array('result' => 'failure');

            // send today for display if asked
            if ( isset($_POST['ask_today']) && $_POST['ask_today'] ) {

                // traduction for french & helper
                include_once($_real_path . '_lang/trad_french.php');

                $_get_today_datas = array(':today_id'=> $_SESSION['today_id'], ':user_id' => $_POST['user_id']);
                $_sql_send_today = 'SELECT finger_counter.finger_counter_count FROM finger_counter
                                    WHERE finger_counter.finger_counter_day = :today_id
                                    AND finger_counter.finger_counter_user = :user_id';

                $_req_send_today = $pdo->prepare($_sql_send_today);
                $_req_send_today->execute($_get_today_datas);

                while ($_row_today = $_req_send_today->fetch( PDO::FETCH_ASSOC )) {
                    $_result['get_today']['count_is_today'] = true;
                    $_result['get_today']['count'] = $_row_today['finger_counter_count'];

                    $day = getdate(strtotime($_SESSION['today_date']));

                    // Value for selected language
                    $_helper = new Helper();
                    $_result['get_today']['count_day_day'] = $day['mday'];
                    $_result['get_today']['count_day_jour'] = $_helper->getArrayValue($day['weekday'], $_jours);
                    $_result['get_today']['count_day_mois'] = $_helper->getArrayValue($day['mon'], $_mois);
                    $_result['get_today']['count_day_year'] = $day['year'];

                }

            }

        }

        echo json_encode( $_result );
    }
?>