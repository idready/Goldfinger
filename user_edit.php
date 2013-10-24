<?php

    /* set the cache expire to one day */
    session_cache_expire(3600);

    // store some useful information for reuse and avoid useless calls on db
    session_start();

    // TODO : do all of this in a class or maybe the helper
    // load the user
    define('DS', DIRECTORY_SEPARATOR);
    define('PS', PATH_SEPARATOR);
    define('BP', dirname(dirname(__FILE__)));

    if ($_SERVER['DOCUMENT_ROOT'] != (DS. 'var' .DS. 'www')) {
        $_real_path = $_SERVER['DOCUMENT_ROOT'] .DS. 'goldfinger' .DS;
    } else {
        $_real_path = $_SERVER['DOCUMENT_ROOT'] .DS;
    }


    require_once($_real_path. '_ajax/cnx.php');

    if (isset($_GET['user'])) {

       $data = array('user_name' => strtolower(htmlentities($_GET['user'])) ) ;
       $sql_user = 'SELECT user_id, user_picto FROM user WHERE user_name = :user_name LIMIT 1';
       $_req = $pdo->prepare($sql_user);
       $_req->execute($data);

        if ($_req->rowCount($sql_user) < 1) {

            // fake user? who care kick him out mon
            header('location:index.php');
        } else if ($_req->rowCount($sql_user) == 1){

            $result = $_req->fetch( PDO::FETCH_ASSOC );
            $_user['user_id'] = $result['user_id'];
            $_user['user_picto'] = $result['user_picto'];
        } else {

            // this will never ever happen but heh!
            header('location:www.google.fr');
        }

        // only admin allowed
        if (!isset($_SESSION['is_user_logged'])) {
          header('location:index.php');
        }

        if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == 'dev.goldfinger.fr') {
            $base_url = 'http://' . $_SERVER['SERVER_NAME']. ':' .$_SERVER['SERVER_PORT'];
        } else {
            $base_url = 'http://' . 'irie-design.fr/goldfinger';
        }

    // no user send
    } else {
        header('location:index.php');
    }
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
        <!--[if !IE]>
        <meta name="viewport" content="width=device-width">
        <![endif]-->
        <title>GoldFinger</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link href='http://fonts.googleapis.com/css?family=Cabin+Sketch:400,700' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">

        <!-- Framework -->
        <link rel="stylesheet" href="css/vendor/1140.css">
        <!--[if IE]>        <link rel="stylesheet" href="css/vendor/ie.css"><![endif]-->
        <!-- Custom style -->
        <link rel="stylesheet" href="css/styles.css">
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>

        <meta name="description" content="" />
        <meta name="keywords" content="" />

    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
        <div id="wrapper">
            <div class="container">
                <!-- Header -->
                <header>
                    <div class="tac row">
                        <div class="twelvecol header tac">
                            <h1 class="logo">
                                <a href="<?php echo $base_url . '/'; ?>">
                                    <img src="img/goldfinger_logo.png" alt="goldfinger logo">
                                </a>
                            </h1>
                            <p>
                                <span class="">Chaque jour, j’ai droit au doigt d’honneur. Mais combien ?</span>
                            </p>
                        </div>
                    </div>
                </header>
                <!-- Body -->
                <section>
                    <script type="text/javascript">
                    //<![CDATA[
                       var user =  <?php echo $_user['user_id'] ?>;
                       var base_url = '<?php if ($_SERVER['SERVER_NAME'] != 'localhost') { echo $_SERVER['SERVER_NAME'] . '/goldfinger'; }  ?>' + '';
                    //]]>
                    </script>
                    <div class="row">
                        <div class="twelvecol">
                            <div class="single_user">
                                {{#user}}
                                    <div class="tac user">
                                        <div class="dib">
                                            <span class="user_avatar dib">
                                                <img src="{{user_avatar}}" alt="" >
                                            </span>
                                            <span class="dib">
                                                <span class="user_pseudo">Nom : </span>{{user_name}}<br />
                                                <span>Premier péché : </span>{{created_at_jour}} {{created_at_day}} {{created_at_mois}} {{created_at_year}}<br />
                                                <span>Statut : </span>{{status_name}}<br />
                                                <span>Total doigts : </span><span class="total_fingers nice_blue">{{finger_counter_count}} (total)</span><br />
                                            </span>
                                        </div>
                                        <span class="tac db profil_sep">
                                            <img src="img/profil_sep.png" alt="">
                                        </span>
                                    </div>
                                {{/user}}
                            </div>
                        </div>
                        <div class="tac" id="count">
                            <div id="add_finger_count" class="tac">
                                <a href="" class="db nice_blue">Ajouter <span class="color_wh tac">+1</span></a>
                                <span class="for_today">Aujourd'hui <span id="today_count">{{today_count}}</span></span>
                            </div>
                            <span class="tac db profil_sep">
                                <img src="img/profil_sep.png" alt="">
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div id="in_the_past" class="twelvecol">
                            <div class="center_inner">
                                <div class="section_title"></div>
                                <div id="days" class="days">
                                {{#days}}
                                    <div class="day clearfix" data-today="{{count_is_today}}">
                                        <span class="day_arrow">></span>
                                        <span class="date">{{count_day_jour}} {{count_day_day}} {{count_day_mois}} {{count_day_year}}</span>
                                        <span class="sum_golfingers">
                                           <span>+{{count}}</span>
                                           <span class="<?php echo strtolower($_user['user_picto']) ?>_picto"></span>
                                        </span>
                                    </div>
                                {{/days}}
                                {{! Condition in case users has no fingers shown yet }}
                                {{^days}}
                                    <p class="tac space_moutain">Un gars poli ^^, eh bien eh bien.</p>
                                {{/days}}

                                </div>
                                <div class="more_fingers tac row">
                                    <a href="#">Plus loin dans le passé ...</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Footer -->
                <footer>
                    <div class="tac row">
                        <div class="twelvecol">
                            <p>finger rights &copy; <?php echo date('Y') ?></p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script> -->
        <script src="http://code.jquery.com/jquery-latest.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>')</script>
        <script src="js/vendor/jquery.fittext.js"></script>
        <script src="js/vendor/mustache.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/scripts.js"></script>
        <script src="js/_user.js"></script>
        <script src="js/_admin.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    </body>
</html>