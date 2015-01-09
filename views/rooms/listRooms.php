<?php
$title = 'Gestion des salles';

ob_start();
        echo $msg;
        $layout = ob_get_contents();
ob_clean();
include 'layouts/layout.php';
	


