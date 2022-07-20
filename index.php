<?php
declare (strict_types=1);

use  App\EvolvClient\EvolvClient;

require_once __DIR__ . '/App/EvolvClient.php';

require 'vendor/autoload.php';

function display($arr, $title = null)
{
    if ($title) {
        echo "<p>$title</p>";
    }

    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}


?>
<html lang="en" class="no-js">
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title>Test</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>

    <!-- GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Hind:300,400,500,600,700" rel="stylesheet" type="text/css">
    <link href="src/vendor/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="src/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <!-- PAGE LEVEL PLUGIN STYLES -->
    <link href="src/css/animate.css" rel="stylesheet">
    <link href="src/vendor/swiper/css/swiper.min.css" rel="stylesheet" type="text/css"/>

    <!-- THEME STYLES -->
    <link href="src/css/layout.css" rel="stylesheet" type="text/css"/>

    <!-- Favicon -->
    <link rel="shortcut icon" href="favicon.ico"/>

    <style>

    p {
        margin-top: 20px;
        margin-bottom: 5px;
    }

    </style>
</head>
<!-- END HEAD -->
<!-- BODY -->
<body id="body" data-spy="scroll" data-target=".header">
    
        <div class="content-lg container">
            <div class="row">
                <div class="col-md-12 col-sm-12 md-margin-b-60">
                    <div class="margin-t-50 margin-b-30">
                    <?php

                        $environment = '7f4099bfbc';
                        $uid = '12345';
                        $endpoint = 'https://participants-stg.evolv.ai/';

                        $client = new EvolvClient($environment, $endpoint);

                        // listening to lifecycle events
                        // $client->on('initialized', function() {
                        //     display('INITIALIZED');
                        // });

                        // $client->on('context.value.changed', function($type, $key, $value) {
                        //     display("CONTEXT_VALUE_CHANGED. KEY: $key, VALUE: $value");
                        // });

                        $client->initialize($uid);

                        $client->context->set('native.newUser', true);
                        $client->context->set('native.pageCategory', 'home');
                        $client->context->set('native.pageCategory', 'pdp');

                    ?>
                    </div>
                </div>
            </div>
        </div>

<!-- Back To Top -->
<a href="javascript:void(0);" class="js-back-to-top back-to-top">Top</a>

<!-- JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- CORE PLUGINS -->
<script src="src/vendor/jquery.min.js" type="text/javascript"></script>
<script src="src/vendor/jquery-migrate.min.js" type="text/javascript"></script>
<script src="src/vendor/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

<!-- PAGE LEVEL PLUGINS -->
<script src="src/vendor/jquery.easing.js" type="text/javascript"></script>
<script src="src/vendor/jquery.back-to-top.js" type="text/javascript"></script>
<script src="src/vendor/jquery.smooth-scroll.js" type="text/javascript"></script>
<script src="src/vendor/jquery.wow.min.js" type="text/javascript"></script>
<script src="src/vendor/swiper/js/swiper.jquery.min.js" type="text/javascript"></script>
<script src="src/vendor/masonry/jquery.masonry.pkgd.min.js" type="text/javascript"></script>
<script src="src/vendor/masonry/imagesloaded.pkgd.min.js" type="text/javascript"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBsXUGTFS09pLVdsYEE9YrO2y4IAncAO2U"></script>

<!-- PAGE LEVEL SCRIPTS -->
<script src="src/js/layout.min.js" type="text/javascript"></script>
<script src="src/js/components/wow.min.js" type="text/javascript"></script>
<script src="src/js/components/swiper.min.js" type="text/javascript"></script>
<script src="src/js/components/masonry.min.js" type="text/javascript"></script>
<script src="src/js/components/google-map.min.js" type="text/javascript"></script>

</body>
<!-- END BODY -->
</html>

