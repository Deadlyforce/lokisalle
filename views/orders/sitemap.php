<?php
include_once 'models/member.php';
include_once 'models/newsletter.php';
$member = new Member();
$newsletter = new Newsletter();

if(isset($_SESSION['user']['id_membre'])){
    $id = $_SESSION['user']['id_membre'];
    $newsletter->setIdMembre($id);
}        
$memberSubscribed = $newsletter->findSubscribedMember();

$title = 'Plan du site';
ob_start();
    
    echo $msg;
    ?>
    <div class="row">
        <div class="col-sm-4">
            <ul class="sitemapUl">
                <li><a href="index.php?controller=rooms&action=accueil" title="ACCUEIL">ACCUEIL</a></li>
                <li><a href="index.php?controller=products&action=listProductsReservation" title="RÉSERVATION">RÉSERVATION</a></li>
                <li><a href="index.php?controller=products&action=searchProducts" title="RECHERCHE">RECHERCHE</a></li>    
            <?php
            if(!$member->sessionExists()){
                echo '<li><a href="index.php?controller=members&action=connect" title="CONNEXION">CONNEXION</a></li>';
                echo '<li><a href="index.php?controller=members&action=add_member" title="INSCRIPTION">INSCRIPTION</a></li>';
            }
            if($member->sessionExists()){
                echo '<li><a href="index.php?controller=members&action=profil" title="MON PROFIL">MON PROFIL</a></li>';
                echo '<li><a href="index.php?controller=orders&action=showCart" title="MON PANIER">MON PANIER</a></li>';
            }
            if($member->userAdmin() || $member->sessionExists()){
                echo '<li><a href="index.php?controller=members&action=disconnect" title="DECONNEXION">DÉCONNEXION</a></li><br/>';
            }
            ?>
            </ul>
        </div>
        
        <div class="col-sm-4">
            <ul class="sitemapUl">
                <li><a href="index.php?controller=orders&action=mentions" title="Mentions légales">Mentions légales</a></li>
                <li><a href="index.php?controller=orders&action=cgv" title="C.G.V">C.G.V</a></li>
                <li><a href="index.php?controller=orders&action=sitemap" title="Plan du site">Plan du site</a></li>
                <li><a href="javascript:window.print();" title="Imprimer la page">Imprimer la page</a></li>
                <li><a href="index.php?controller=members&action=contactus" title="Nous contacter">Nous contacter</a></li>
                <?php
                if(!$member->sessionExists()){
                    echo '<li><a href="index.php?controller=newsletters&action=addVisitor" title="S\'inscrire à la newsletter">S\'inscrire à la newsletter</a></li>';
                }
                
                if($member->sessionExists() && ($_SESSION['user']['statut'] == 0 || $_SESSION['user']['statut'] == 1) && !$memberSubscribed){
                    echo '<li><a href="index.php?controller=newsletters&action=addMember" title="S\'inscrire à la newsletter">S\'inscrire à la newsletter</a></li>';        
                }elseif($member->sessionExists() && $memberSubscribed){
                    echo '<li><a href="index.php?controller=newsletters&action=addMember" title="Se désinscrire de la newsletter">Se désinscrire de la newsletter</a></li>';  
                }
                
                if($member->userAdmin()){
                    echo '<li><a href="index.php?controller=newsletters&action=sendNewsletter" title="Gestion de la newsletter">Gestion de la newsletter</a></li>';
                }
                ?>
            </ul>
        </div>
        
        <div class="col-sm-4">
            <ul class="sitemapUl">
            <?php
            if($member->userAdmin()){

                echo '<li><a href="index.php?controller=rooms&action=listRooms">GESTION SALLES</a>
                        <ul class="menu3">
                            <li><a href="index.php?controller=rooms&action=addRoom">Ajout salle</a></li>
                            <li><a href="index.php?controller=rooms&action=listRooms">Afficher salles</a></li>
                        </ul>
                    </li>';
                echo '<li><a href="index.php?controller=products&action=listProducts&order=default">GESTION PRODUITS</a>
                        <ul class="menu3">
                            <li><a href="index.php?controller=products&action=addProduct">Ajout produit</a></li>
                            <li><a href="index.php?controller=products&action=listProducts&order=default">Afficher produit</a></li>
                        </ul>
                    </li>';
                echo '<li><a href="index.php?controller=members&action=listMember">GESTION MEMBRES</a>
                        <ul class="menu3">
                            <li><a href="index.php?controller=members&action=listMember">Liste utilisateurs</a></li>
                            <li><a href="index.php?controller=members&action=createAdmin">Nouvel administrateur</a></li>
                        </ul>
                </li>';
                echo '<li><a href="index.php?controller=orders&action=manageOrders&order=default">GESTION COMMANDES</a></li>';
                echo '<li><a href="index.php?controller=comments&action=listComments">GESTION AVIS</a></li>';
                echo '<li>
                        <a href="index.php?controller=promotions&action=listPromo">CODES PROMO</a>
                        <ul class="menu3">
                            <li><a href="index.php?controller=promotions&action=addPromo">Ajouter</a></li>
                            <li><a href="index.php?controller=promotions&action=listPromo">Afficher</a></li>
                        </ul>
                    </li>';
                echo '<li>
                        <a href="#">STATISTIQUES</a>
                        <ul class="menu3">
                            <li><a href="index.php?controller=comments&action=top5note">Top 5 salles</a></li>
                        </ul>
                        </li>';
                echo '<li><a href="index.php?controller=newsletters&action=sendNewsletter">NEWSLETTER</a></li>';                       
            }
            ?>
            </ul>
        </div>
    </div>
    <?php
    $layout = ob_get_contents();
ob_clean();

include 'layouts/layout.php';

