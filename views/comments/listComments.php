<?php
$title = 'Gestion des avis';
ob_start();
    echo $table;
    echo $msg;
    $layout = ob_get_contents();
ob_clean();
include 'layouts/layout.php';
   


