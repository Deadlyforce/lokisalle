<?php
include_once "models/member.php";
$member = new Member();

$header = '';

$header .= '<div class="rowHeader">';
    $header .= '
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php?controller=rooms&action=accueil"><img src="public/images/lokisalle_logo.png" alt="Lokisalle" /></a>
      </div>';

    $header .= '<div class="collapse navbar-collapse">';
        $header .= '<ul class="nav navbar-nav navbar-right menu1">';

            $header .= '<li><a href="index.php?controller=rooms&action=accueil" title="ACCUEIL">ACCUEIL</a></li>';
            $header .= '<li><a href="index.php?controller=products&action=listProductsReservation" title="RÉSERVATION">RÉSERVATION</a></li>';
            $header .= '<li><a href="index.php?controller=products&action=searchProducts" title="RECHERCHE">RECHERCHE</a></li>';


            // Menu membre non connecté (visiteur)
            if(!$member->sessionExists()){
                $header .= '<li><a href="index.php?controller=members&action=connect" title="CONNEXION">CONNEXION</a></li>';
                $header .= '<li><a href="index.php?controller=members&action=add_member" title="INSCRIPTION">INSCRIPTION</a></li>';
            }

            // Menu membre connecté
            if($member->sessionExists()){
                $header .= '<li><a href="index.php?controller=members&action=profil" title="MON PROFIL">MON PROFIL</a></li>';
                $header .= '<li><a href="index.php?controller=orders&action=showCart" title="MON PANIER">MON PANIER</a></li>';
            }

            if($member->userAdmin() || $member->sessionExists()){
                $header .= '<li><a href="index.php?controller=members&action=disconnect" title="DECONNEXION">DÉCONNEXION</a></li><br/>';
            }
        $header .= '</ul>';
    $header .= '</div>';

// TITRE -----------------------------------------------------------------------

    $header .= '<h1 class="mainTitle">Bienvenue sur Lokisalle.</h1>';
    $header .= '<h2 class="tagline">Numéro 1 de la location de salles de réunion en France.</h2>';
$header .= '</div>';






