<?php
    $title = 'Avis supprimé !';    
    ob_start();

        echo $msg;
        echo '<a class="btn btn-success btnMargin" href="index.php?controller=comments&action=listComments">Gestion des commentaires</a><br/>';
        echo '<a class="btn btn-success btnMargin" href="index.php">Retourner à l\'accueil</a>';
        $layout = ob_get_contents();
    ob_clean();
    include 'layouts/layout.php';
