<?php
include_once "models/member.php";
include_once 'models/newsletter.php';
$member = new Member();
$newsletter = new Newsletter();

if(isset($_SESSION['user']['id_membre'])){
    $id = $_SESSION['user']['id_membre'];
    $newsletter->setIdMembre($id);
}        
$memberSubscribed = $newsletter->findSubscribedMember();

$footer = "";

$footer .= '<div class="container">';
    $footer .= '<div class="row">';    
        $footer .= '<div class="col-sm-4">';
            $footer .= '<p class="title">LEGAL</p>';
            $footer .= '<ul>';
                $footer .= '<li><a href="index.php?controller=orders&action=mentions" title="Mentions légales">Mentions légales</a></li>';
                $footer .= '<li><a href="index.php?controller=orders&action=cgv" title="C.G.V">C.G.V</a></li>';
            $footer .= '</ul>';
        $footer .= '</div>';
        
        $footer .= '<div class="col-sm-4">';
            $footer .= '<p class="title">NAVIGATION</p>';
            $footer .= '<ul>';
                $footer .= '<li><a href="index.php?controller=orders&action=sitemap" title="Plan du site">Plan du site</a></li>';
                $footer .= '<li><a href="javascript:window.print();" title="Imprimer la page">Imprimer la page</a></li>';
            $footer .= '</ul>';
        $footer .= '</div>';
        
        $footer .= '<div class="col-sm-4">';
            $footer .= '<p class="title">CONTACT</p>';
            $footer .= '<ul>';
                $footer .= '<li><a href="index.php?controller=members&action=contactus" title="Nous contacter">Nous contacter</a></li>';

                // Menu membre non connecté (visiteur)
                if(!$member->sessionExists()){
                    $footer .= '<li><a href="index.php?controller=newsletters&action=addVisitor" title="S\'inscrire à la newsletter">S\'inscrire à la newsletter</a></li>';
                }

                // Menu membre connecté
                if($member->sessionExists() && ($_SESSION['user']['statut'] == 0 || $_SESSION['user']['statut'] == 1) && !$memberSubscribed){
                    $footer .= '<li><a href="index.php?controller=newsletters&action=addMember" title="S\'inscrire à la newsletter">S\'inscrire à la newsletter</a></li>';        
                }elseif($member->sessionExists() && $memberSubscribed){
                    $footer .= '<li><a href="index.php?controller=newsletters&action=addMember" title="Se désinscrire de la newsletter">Se désinscrire de la newsletter</a></li>';  
                }

                // Menu administrateur
                if($member->userAdmin()){
                    $footer .= '<li><a href="index.php?controller=newsletters&action=sendNewsletter" title="Gestion de la newsletter">Gestion de la newsletter</a></li>';
                }
            $footer .= '</ul>';
        $footer .= '</div>';
    $footer .= '</div>';
    
    $footer .= '<div class="row">';    
        $footer .= '<p id="copyright">Copyright © 2014 Lokisalle. Tous droits réservés.</p>';    
    $footer .= '</div>';    
$footer .= '</div>';

