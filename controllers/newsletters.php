<?php
include 'models/newsletter.php';

class Newsletters{
    
    public function addMember(){
        $id = '';
        $msg = "";
        
        // VERIFICATION SI L'UTILISATEUR EST DEJA INSCRIT
        $newsletter = new Newsletter();
        
        if(isset($_SESSION['user']['id_membre'])){
            $id = $_SESSION['user']['id_membre'];
            $newsletter->setIdMembre($id);
        }        
        
        $memberSubscribed = $newsletter->findSubscribedMember();
        
                
        include "views/newsletter/addMember.php";
    }
    
    public function unsubscribe(){
        
        $msg = '';
        $newsletter = new Newsletter();
        
        if(isset($_SESSION['user']['id_membre'])){
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $newsletter->setIdMembre($id);
        }  
        
        $newsletter->unsubscribe();
        
        $msg .= '<p>Votre désinscription à la newsletter a bien été prise en compte.</p>';
        $msg .= '<a href="index.php">Retour à l\'accueil</a>';
        
        include 'views/newsletter/unsubscribe.php';
    }
    
    public function addVisitor(){
        
        $msg = "";
        $msg .= 'Inscrivez-vous à Lokisalle pour pouvoir bénéficier de la newsletter.';
        $msg .= '<a href="index.php?controller=members&action=add_member" title="S\'inscrire">S\'inscrire</a>';
       
        include "views/newsletter/addVisitor.php";
    }
    
    public function subscribe(){
                
        $msg = "";
        $memberId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        $newsletter = new Newsletter;
        $newsletter->setIdMembre($memberId);
        $newsletter->addNewsMember();       
        
        $msg .= '<p>Vous êtes désormais inscrit à la newsletter.</p>';
        $msg .= '<a href="index.php">Retour à l\'accueil</a>';
        
        include "views/newsletter/subscribe.php";
    }
    
    public function sendNewsletter(){
        
        $msg = "";
        $errors = array();
        
        // Compter les nombre d'abonnés à la newsletter
        $newsletter = new Newsletter();
        
        if($newsletter->access_ModelMember_sessionExists() && $newsletter->access_ModelMember_userAdmin()){            
            
            // DECOMPTE DES MEMBRES INSCRITS
            $subscribedMembers = $newsletter->listSubscribedMembers();
            $nbr = count($subscribedMembers);

            $msg .= '<div class="panel panel-default">';
                $msg .= '<div class="panel-body">';
                    $msg .= 'Nombre d\'abonnés à la newsletter: '. $nbr ;
                $msg .= '</div>';
            $msg .= '</div>';

            // RECUPERATION DU FORMULAIRE
            if(filter_has_var(INPUT_POST, 'submit')){

                $exp =  filter_input(INPUT_POST,'exp', FILTER_VALIDATE_EMAIL);                
                if($exp == NULL){                
                    $errors[] = 'Vous devez renseigner votre adresse email.';
                }elseif($exp == false){
                    $errors[] = 'L\'adresse email n\'est pas valide.';
                    $exp = filter_input(INPUT_POST,'exp', FILTER_SANITIZE_EMAIL);
                }

                $subject =  filter_input(INPUT_POST,'subject', FILTER_SANITIZE_STRING);
                if($subject == NULL || $subject == false || empty($subject)){
                    $errors[] = 'Vous devez remplir le sujet.';
                }

                $message =  filter_input(INPUT_POST,'message', FILTER_SANITIZE_STRING);
                if($message == NULL || $message == false || empty($message)){
                    $errors[] = 'Le champ message ne peut rester vide.';
                }

                if(count($errors) == 0){
                    // Récupération du mail de chaque abonné
                    $listMail = array();
                    $listMembers = $newsletter->accessModelMember_listMembers();

                    foreach($subscribedMembers as $subMember){
                        for($i=0; $i<count($listMembers); $i++){
                            if($subMember['id_membre'] == $listMembers[$i]['id_membre']){
                                $listMail[] = $listMembers[$i]['email'];
                            }
                        }
                    }                               

                    // Construction de la chaîne de mails séparés par des virgules
                    $listDest = '';  

                    foreach($listMail as $adresse){
                        $dest .= $adresse . ','; 
                    }

                    // Envoi de la newsletter aux abonnés
                    $to = $dest;     

                    $type = 'plain'; // or HTML
                    $charset = 'utf-8';
                    $uniqid = md5(uniqid(time())); // Génère un identifiant unique basé sur l'heure courante

                    $headers  = 'From: '.$exp."\n";
                    $headers .= 'Reply-to: '.$exp."\n";
                    $headers .= 'Return-Path: '.$exp."\n";
                    $headers .= 'Message-ID: <'.$uniqid.'@'.$_SERVER['SERVER_NAME'].">\n";
                    $headers .= 'MIME-Version: 1.0'."\n";
                    $headers .= 'Date: '.gmdate('D, d M Y H:i:s', time())."\n";
                    $headers .= 'X-Priority: 3'."\n";
                    $headers .= 'X-MSMail-Priority: Normal'."\n";
                    $headers .= 'Content-Type: multipart/mixed;boundary="----------'.$uniqid.'"'."\n\n";
                    $headers .= '------------'.$uniqid."\n";
                    $headers .= 'Content-type: text/'.$type.';charset='.$charset.''."\n";
                    $headers .= 'Content-transfer-encoding: 7bit';

                    mail($to, $subject, $message, $headers);

                    $msg .= '<div class="alert alert-success">';
                        $msg .= 'Message envoyé aux membres !';
                    $msg .= '</div>';
                }else{
                    $errors[] .= 'Votre message n\'a pas été envoyé.';
                } 
            }
            
        }else{
            $msg .= 'Vous n\'avez pas le droit d\'accéder à cette page';
        }        
        
        include 'views/newsletter/sendNewsletter.php';
    }
}

