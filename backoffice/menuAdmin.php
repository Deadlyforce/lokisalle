<?php
include_once "models/member.php";
$member = new Member();

$menuAdmin = '';
    
// Menu administrateur
if($member->userAdmin()){
    $menuAdmin .= '<nav>';
        $menuAdmin .= '<div class="container">';
        
                $menuAdmin .= '<ul class="menuAdmin pull-left">';
                    $menuAdmin .= '<li><a href="index.php?controller=rooms&action=listRooms">GESTION SALLES</a>
                            <ul class="menu3">
                                <li><a href="index.php?controller=rooms&action=addRoom">AJOUT SALLE</a></li>
                                <li><a href="index.php?controller=rooms&action=listRooms">AFFICHER SALLES</a></li>
                            </ul>
                        </li>';
                    $menuAdmin .= '<li><a href="index.php?controller=products&action=listProducts&order=default">GESTION PRODUITS</a>
                            <ul class="menu3">
                                <li><a href="index.php?controller=products&action=addProduct">AJOUT PRODUIT</a></li>
                                <li><a href="index.php?controller=products&action=listProducts&order=default">AFFICHER PRODUIT</a></li>
                            </ul>
                        </li>';
                    $menuAdmin .= '<li><a href="index.php?controller=members&action=listMember">GESTION MEMBRES</a>
                            <ul class="menu3">
                                <li><a href="index.php?controller=members&action=listMember">LISTE UTILISATEURS</a></li>
                                <li><a href="index.php?controller=members&action=createAdmin">NOUVEL ADMIN</a></li>
                            </ul>
                    </li>';
                    $menuAdmin .= '<li><a href="index.php?controller=orders&action=manageOrders&order=default">GESTION COMMANDES</a></li>';
                    $menuAdmin .= '<li><a href="index.php?controller=comments&action=listComments">GESTION AVIS</a></li>';
                    $menuAdmin .= '<li>
                            <a href="index.php?controller=promotions&action=listPromo">CODES PROMO</a>
                            <ul class="menu3">
                                <li><a href="index.php?controller=promotions&action=addPromo">AJOUTER</a></li>
                                <li><a href="index.php?controller=promotions&action=listPromo">AFFICHER</a></li>
                            </ul>
                        </li>';
                    $menuAdmin .= '<li>
                            <a href="#">STATISTIQUES</a>
                            <ul class="menu3">
                                <li><a href="index.php?controller=comments&action=top5note">TOP5 SALLES (NOTE)</a></li>
                            </ul>
                            </li>';
                    $menuAdmin .= '<li><a href="index.php?controller=newsletters&action=sendNewsletter">NEWSLETTER</a></li>';
                $menuAdmin .= '</ul>'; 
        
        $menuAdmin .= '</div>';
    $menuAdmin .= '</nav>';
}
    

    
    
   
                



