<?php

    /* set the cache expire to one day */
    session_cache_expire(86400);
    // store some useful information for reuse and avoid useless calls on db
    session_start();

    // default path value
    require_once('define_path.php');


    $_today = array(':today' => date('Y-m-d 00:00:00'));


    /*-------- GET TODAY ID AND DATE --------*/
    /* check if there is a today record on db */
    $_sql_is_today = 'SELECT day.day_id, day.day_date
                      FROM day
                      WHERE day.day_date = :today LIMIT 1';

    $_req_is_today = $pdo->prepare($_sql_is_today);
    $_req_is_today->execute($_today);
    $_row_is_today_count = $_req_is_today->rowCount($_sql_is_today);

    // today is already on db
    if ($_row_is_today_count == 1) {

        while ($_row = $_req_is_today->fetch( PDO::FETCH_ASSOC )) {

            $_SESSION['today_id'] = $_row['day_id'];
            $_SESSION['today_date'] = $_row['day_date'];
        }

        // close connection
        $_req_is_today->closeCursor();
        $_req_is_today = NULL;
    // let's create the day record
    } else {

        $_sql_create_today = 'INSERT INTO day (day_id, day_date) VALUES (null, :today)';
        $_req_create_today = $pdo->prepare($_sql_create_today);
        $_req_create_today->execute($_today);
        $_row_result = $_req_create_today->rowCount($_sql_create_today);

        if ($_row_result == 1) {

            // get today id and date (just created ones)
            $_sql_today_datas = 'SELECT day.day_id, day.day_date FROM day
                                     WHERE day.day_date = :today LIMIT 1';

            $_req_today_datas = $pdo->prepare($_sql_is_today);
            $_req_today_datas->execute($_today);
            $_row_today_datas = $_req_today_datas->rowCount($_sql_today_datas);

            if ($_row_today_datas == 1) {
                while ($_row = $_req_today_datas->fetch( PDO::FETCH_ASSOC )) {

                    $_SESSION['today_id'] = $_row['day_id'];
                    $_SESSION['today_date'] = $_row['day_date'];
                }

                // close connection
                $_req_today_datas->closeCursor();
                $_req_today_datas = NULL;
            } else {
                var_dump('Trouble on the road : -- Can\'t select inserted day');
            }
        }

        // close connection
        $_req_create_today->closeCursor();
        $_req_create_today = NULL;
    }

?>