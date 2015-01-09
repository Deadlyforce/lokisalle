<?php
    $title = 'Vider le panier';
    ob_start();

        echo $msg;
        echo '<a href="index.php">Retourner à l\'accueil</a><br/>';
                       
        $layout = ob_get_contents();
    ob_clean();
    include 'layouts/layout.php';

