<?php
    $title = 'Mentions légales';   
    ob_start();

        echo $msg;
        $layout = ob_get_contents();
    ob_clean();
    include 'layouts/layout.php';

