<?php

    /* set the cache expire to one day */
    session_cache_expire(3600);
    // store some useful information for reuse and avoid useless calls on db
    session_start();

    require_once('_ajax/define_path.php');
    if (!isset($_SESSION['is_user_logged']) || !$_SESSION['is_user_logged']) {

        header('Location: http://'. CURRENT_BASE_URL);
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
                                <a href="<?php echo CURRENT_BASE_URL; ?>">
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
                        var base_url = 'http://<?php echo CURRENT_BASE_URL; ?>';
                    //]]>
                    </script>
                    <div class="row">
                        <div class="twelvecol tac">
                            <p class="space_moutain">Créer un utilisateur</p>
                            <form id="create_user" action="/" enctype="multipart/form-data" method="Post" class="space_moutain">
                                <div class="form_info_block clearfix">
                                    <label class="dib fl" for="user_name">Pseudo</label>
                                    <input type="text" name="user_name" id="user_name" class="fl">
                                </div>
                                <div class="form_info_block clearfix">
                                    <label class="dib fl" for="user_first_name">Prénom</label>
                                    <input type="text" name="user_first_name" id="user_first_name" class="fl">
                                </div>
                                <div class="form_info_block pr clearfix">
                                    <label class="dib fl" for="user_avatar">Avatar URL</label>
                                    <input type="text" name="user_avatar" id="user_avatar" class="fl">
                                </div>
                                <div class="form_info_block clearfix">
                                    <input type="submit" value="Créer" name="create_usr">
                                </div>
                            </form>
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
        <script src="js/_user_create.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <?php require_once('_ajax/gAnalytics.php'); ?>
    </body>
</html>
