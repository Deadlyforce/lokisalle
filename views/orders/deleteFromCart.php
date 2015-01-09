<?php
    $title = 'Supprimer un produit du panier';
    ob_start();

        echo $msg;
        echo '<a href="index.php?controller=orders&action=showCart">Retourner au panier</a><br/>';
                       
        $layout = ob_get_contents();
    ob_clean();
    include 'layouts/layout.php';



