<?php
include_once "models/member.php";
$member = new Member();

$headerLP = '';

$headerLP .= '<div class="rowHeaderLP">';

    $headerLP .= '
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php?controller=rooms&action=accueil"><img src="public/images/lokisalle_logo.png" alt="Lokisalle" /></a>
      </div>';

    $headerLP .= '<div class="collapse navbar-collapse">';
        $headerLP .= '<ul class="nav navbar-nav navbar-right menu1">';

            $headerLP .= '<li><a href="index.php?controller=rooms&action=accueil" title="ACCUEIL">ACCUEIL</a></li>';
            $headerLP .= '<li><a href="index.php?controller=products&action=listProductsReservation" title="RÉSERVATION">RÉSERVATION</a></li>';
            $headerLP .= '<li><a href="index.php?controller=products&action=searchProducts" title="RECHERCHE">RECHERCHE</a></li>';


            // Menu membre non connecté (visiteur)
            if(!$member->sessionExists()){
                $headerLP .= '<li><a href="index.php?controller=members&action=connect" title="CONNEXION">CONNEXION</a></li>';
                $headerLP .= '<li><a href="index.php?controller=members&action=add_member" title="INSCRIPTION">INSCRIPTION</a></li>';
            }

            // Menu membre connecté
            if($member->sessionExists()){
                $headerLP .= '<li><a href="index.php?controller=members&action=profil" title="MON PROFIL">MON PROFIL</a></li>';
                $headerLP .= '<li><a href="index.php?controller=orders&action=showCart" title="MON PANIER">MON PANIER</a></li>';
            }

            if($member->userAdmin() || $member->sessionExists()){
                $headerLP .= '<li><a href="index.php?controller=members&action=disconnect" title="DÉCONNEXION">DÉCONNEXION</a></li><br/>';
            }
        $headerLP .= '</ul>';
    $headerLP .= '</div>';

// TITRE -----------------------------------------------------------------------

    $headerLP .= '<h1 class="mainTitle">Bienvenue sur Lokisalle.</h1>';
    $headerLP .= '<h2 class="tagline">Numéro 1 de la location de salles de réunion en France.</h2>';

    $headerLP .= '<div class="intro">';
        $headerLP .= '<p>Lokisalle, créé en 2014, vous permet de louer en toute tranquillité des salles pour vos évènements, partout en France. Au programme, ce sont salles de réunion diverses et salles de conférence qui vous attendent sur le site, bonne navigation !</p>';
    $headerLP .= '</div>';
    
    if(!$member->sessionExists()){
        $headerLP .= '<a class="btn btn-subscribe" href="index.php?controller=members&action=add_member" title="INSCRIPTION">COMMENCER</a>';
    }

$headerLP .= '</div>';




