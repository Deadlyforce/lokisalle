<?php
    $title = 'Consultez nos 3 dernières offres';
    
    ob_start();
        echo $msg;
        $layout = ob_get_contents();
    ob_clean();
include 'layouts/layout_landingPage.php';



