<?php
    $title = 'Supprimer un code promo';    
    ob_start();

        echo $msg;
        echo '<a href="index.php">Retourner à l\'accueil</a>';
        $layout = ob_get_contents();
    ob_clean();
    include 'layouts/layout.php';

