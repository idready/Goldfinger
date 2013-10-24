<?php

    /* set the cache expire to one day */
    session_cache_expire(86400);
    // store some useful information for reuse and avoid useless calls on db
    session_start();


    // TODO : set jointure request
    // request users' days
    if (isset($_POST['user_front'])) {

        // default path value
        require_once('define_path.php');

        // prepare data for sql request
    	$datas = array('user_id' => htmlentities($_POST['user_front']));

        // how many rows
        if (isset($_POST['rows'])) {
            $_user_fingers_rows = intval(htmlentities($_POST['rows']));

            if (is_numeric($_user_fingers_rows)) {

                if ($_user_fingers_rows < 0) {

                    return 'Rows must be a Number and at least equals to 0';

                // case : first load returns 9 rows
                } else if ($_user_fingers_rows == 0) {

                    $_sql = 'SELECT finger_counter.finger_counter_day, finger_counter.finger_counter_count
                             from finger_counter WHERE finger_counter_user = :user_id
                             ORDER BY finger_counter_id DESC limit 9';
                // case : returns range of rows from older to earlier starting from the earliest already displayed
                } else {

                    $_sql = 'SELECT finger_counter.finger_counter_day, finger_counter.finger_counter_count
                             from finger_counter WHERE finger_counter_user = :user_id
                             ORDER BY finger_counter_id DESC limit ' . $_user_fingers_rows . ',' . ( $_user_fingers_rows * 2) .'';
                }
            }

        }

        $_req = $pdo->prepare($_sql);
        $_req->execute($datas);

        // if there is less than 9 days left
        ($_req->rowCount($_sql) < 9 ) ? $_last_days = true : $_last_days = false;

        $_result = array('User_Days'=> array(), 'User_Counter'=>0, 'last_days' => $_last_days, 'today_count' => 0);
        $_row_count = 0;

        // traduction for french
        include_once($_real_path . '_lang/trad_french.php');


        while ($_row = $_req->fetch( PDO::FETCH_ASSOC )) {

            $_datas_day = array();

            // :BUGFIX
            // $_datas[$_row_count] = $_datas_day; WHY ISN'T THIS WORKING ???
        	$_datas_day['count'] = $_row['finger_counter_count'];
            $_result['User_Counter'] = $_result['User_Counter'] + intval($_row['finger_counter_count']);

        	// retrive this count day
        	$_find_day_sql = 'SELECT day.day_date FROM day WHERE day_id=' .$_row['finger_counter_day']. '';

        	$_req_day = $pdo->query($_find_day_sql);
        	$_count_day = $_req_day->rowCount($_find_day_sql);

        	if ($_count_day > 0) {

        		while ($_row_day = $_req_day->fetch( PDO::FETCH_ASSOC )) {

        			$day = getdate(strtotime($_row_day['day_date']));
        			$_datas_day['count_day_dweek'] = $day['weekday'];
        			$_datas_day['count_day_day'] = $day['mday'];
        			$_datas_day['count_day_month'] = $day['mon'];
        			$_datas_day['count_day_year'] = $day['year'];

                    // Value for selected language
                    $_helper = new Helper();
                    $_datas_day['count_day_jour'] = $_helper->getArrayValue($day['weekday'], $_jours);
                    $_datas_day['count_day_mois'] = $_helper->getArrayValue($day['mon'], $_mois);

                    /* TAG TODAY */
                    // if the current day is today ** converted timestamp to date
                    if (date('Y-m-d 00:00:00', $day['0']) == date('Y-m-d 00:00:00')) {
                        $_datas_day['count_is_today'] = true;
                        $_datas_day['count_for_today'] = $_row['finger_counter_count'];
                        $_result['today_count'] = $_row['finger_counter_count'];
                    }

        		}

                // close connection
        		$_req_day->closeCursor();
    			$_req_day = NULL;
        	}

            $_result['User_Days'][] = $_datas_day;
            $_row_count++;
        }

        // close connection
        $_req->closeCursor();
    	$_req = NULL;

    echo json_encode($_result);

    }

?>