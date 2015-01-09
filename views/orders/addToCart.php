<?php
    $title = 'Ajouter au panier';
    ob_start();
        echo $msg;
        echo '<a class="btn btn-success btnPanier" href="index.php?controller=orders&action=showCart" title="Voir le panier">Voir le panier</a><br/>';
        echo '<a class="btn btn-success btnPanier" href="index.php?controller=products&action=listProductsReservation" title="Voir les produits disponibles">Voir les produits disponibles</a><br/>';
        
        $layout = ob_get_contents();
    ob_clean();
    include 'layouts/layout.php';


