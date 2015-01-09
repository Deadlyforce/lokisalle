<?php
    $title = 'Supprimer un membre';    
    ob_start();

        echo $msg;
        echo '<a class="btn btn-success btnMargin" href="index.php?controller=members&action=listMember">Retour à la liste des membres</a><br/>';
        echo '<a class="btn btn-success btnMargin" href="index.php">Retourner à l\'accueil</a>';
        $layout = ob_get_contents();
    ob_clean();
    include 'layouts/layout.php';