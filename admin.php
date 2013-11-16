<?php

    /* set the cache expire to one day */
    session_cache_expire(3600);
    // store some useful information for reuse and avoid useless calls on db
    session_start();

    if (isset($_SESSION['is_user_logged'])) {

        if ($_SESSION['is_user_logged']) {
            $_is_admin = true;
        }
    }

    if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == 'dev.goldfinger.fr') {
        $base_url = 'http://' . $_SERVER['SERVER_NAME']. ':' .$_SERVER['SERVER_PORT'];
    } else {
        $base_url = 'http://' . 'irie-design.fr/goldfinger';
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
                                <a href="<?php echo $base_url; ?>">
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
                       var base_url = '<?php if ($_SERVER['SERVER_NAME'] != 'localhost') { echo $_SERVER['SERVER_NAME'] . '/goldfinger'; }  ?>' + '';
                    //]]>
                    </script>
                    <div class="row">
                        <div class="twelvecol tac">
                            <p class="space_moutain">Modifier un utilisateur</p>
                            <div id="users">
                                {{#user}}
                                    <div class="user">
                                        <a class="dib" href="<?php if ($_is_admin) { echo $base_url. '/user_edit'; } else { echo $base_url. '/user'; } ?>.php?user={{user_name}}" title="">
                                            <span class="user_avatar dib">
                                                <img src="{{user_avatar}}" alt="" >
                                            </span>
                                            <span class="dib">
                                                <span class="user_pseudo">Nom : </span>{{user_name}}<br />
                                                <span>1er péché : </span>{{created_at_day}}/{{created_at_month}}/{{created_at_year}}<br />
                                                <span>Statut : </span>{{status_name}}<br />
                                                <span>Total doigts : </span><span class="nice_blue">{{finger_counter_count}}</span><br />
                                            </span>
                                            <?php // user edit ?>
                                            <span class="edit-user"></span>
                                        </a>
                                        <?php // display a separator if this is not last row ?>
                                        {{#not_last_row}}
                                        <span class="tac db profil_sep">
                                            <img src="img/profil_sep.png" alt="">
                                        </span>
                                        {{/not_last_row}}
                                    </div>
                                {{/user}}
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
        <script src="js/_users.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <?php require_once('_ajax/gAnalytics.php'); ?>
    </body>
</html>
