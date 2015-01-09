<?php
include "models/member.php";

class Members
{
        //------------------ OPERATIONS SUR LES MEMBRES ------------------------
        
	public function add_member()
	{
		$msg = "";
                $errors = array();
                
                // Instancie un nouvel objet member
                $member = new Member;
                
                if(filter_has_var(INPUT_POST, 'submit')){
                    
                    $pseudo =  filter_input(INPUT_POST,'pseudo', FILTER_SANITIZE_STRING);
                    if($pseudo == NULL || $pseudo == false || empty($pseudo)){
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner un pseudo.</div>';
                    }
                    
                    $mdp =  filter_input(INPUT_POST,'mdp', FILTER_SANITIZE_STRING);
                    if($mdp == NULL || $mdp == false || empty($mdp)){
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner un mot de passe.</div>';
                    }
                    
                    $nom =  filter_input(INPUT_POST,'nom', FILTER_SANITIZE_STRING);
                    if($nom == NULL || $nom == false || empty($nom)){
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner votre nom.</div>';
                    }
                    
                    $prenom =  filter_input(INPUT_POST,'prenom', FILTER_SANITIZE_STRING);
                    if($prenom == NULL || $prenom == false || empty($prenom)){
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner votre prénom.</div>';
                    }
                    
                    $email =  filter_input(INPUT_POST,'email', FILTER_VALIDATE_EMAIL);                
                    if($email == NULL){                
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner votre adresse email.</div>';
                    }elseif($email == false){
                        $errors[] = '<div class="alert alert-warning">L\'adresse email n\'est pas valide.</div>';
                        $email = filter_input(INPUT_POST,'email', FILTER_SANITIZE_EMAIL);
                    }
                    
                    $sexe =  filter_input(INPUT_POST,'sexe', FILTER_SANITIZE_STRING);
                    
                    $ville =  filter_input(INPUT_POST,'ville', FILTER_SANITIZE_STRING);
                    if($ville == NULL || $ville == false || empty($ville)){
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner une ville.</div>';
                    }
                    
                    $cp =  filter_input(INPUT_POST,'cp', FILTER_SANITIZE_STRING);
                    if($cp == NULL || $cp == false || empty($cp)){
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner un code postal.</div>';
                    }
                    
                    $adresse =  filter_input(INPUT_POST,'adresse', FILTER_SANITIZE_STRING);
                    if($adresse == NULL || $adresse == false || empty($adresse)){
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner une adresse.</div>';
                    }
                    
                    
//                    $pseudo = htmlentities($_POST['pseudo'], ENT_QUOTES, "utf-8");
//                    $mdp = htmlentities($_POST['mdp'], ENT_QUOTES, "utf-8");
//                    $nom = htmlentities($_POST['nom'], ENT_QUOTES, "utf-8");
//                    $prenom = htmlentities($_POST['prenom'], ENT_QUOTES, "utf-8");
//                    $email = htmlentities($_POST['email'], ENT_QUOTES, "utf-8");
//                    $sexe = htmlentities($_POST['sexe'], ENT_QUOTES, "utf-8");
//                    $ville = htmlentities($_POST['ville'], ENT_QUOTES, "utf-8");
//                    $cp = htmlentities($_POST['cp'], ENT_QUOTES, "utf-8");
//                    $adresse = htmlentities($_POST['adresse'], ENT_QUOTES, "utf-8");
                            
                    if(count($errors) == 0){                        
                    
    //                    $mdp = password_hash($mdp, PASSWORD_BCRYPT)."\n";  en PHP 5.5 uniquement
                        $mdp = md5($mdp);		

                        $member->setPseudo($pseudo);
                        $member->setMdp($mdp);
                        $member->setNom($nom);
                        $member->setPrenom($prenom);
                        $member->setEmail($email);
                        $member->setSexe($sexe);
                        $member->setVille($ville);
                        $member->setCp($cp);
                        $member->setAdresse($adresse);


                        $resultArray = $member->checkAllConstraint();

                        // Optionnel -  A la premiere instance rencontrée, dont la valeur Passed est a False
                        // On stoppe le parcours et on affiche les erreurs                    

                        $msgError = "";
                        // Je récupère la valeur de $passed pour chaque entrée du tableau (true or false)
                        // Si false, je récupère également le message d'erreur correspondant
                        for($i = 0; $i < count($resultArray); $i++){
                           if(!$resultArray[$i]->getPassed()){
                               $msgError .= $resultArray[$i]->getErrorMessage();
                               $msgError .= "<br/>";
                           }
                        }

                        // Si le message d'erreur est vide, je rentre le membre en base avec la méthode add_member
                        // Sinon j'affiche les erreurs
                        if($msgError == ""){
                           
                            $member->add_member();                            
                            header('location:index.php?controller=members&action=registered');                            
                            
                        }else{
                            $msg .= $msgError;
                        }
                    }else{
                        $errors[] .= '<div class="alert alert-warning">Vous n\'êtes pas inscrit</div>';
                    }
                        
		}
		
		include "views/members/add_member.php";		
	}
        
        public function modifyProfile(){
            $msg = '';
            $errors = array(); 
            
            if(filter_has_var(INPUT_GET,'id')){
                $id = $_GET['id']; 
            }
            
            $member = new Member;
            $member->setId($id);
            $modMember = $member->retrieveMember($id);
            
            if($member->sessionExists()){
                if(filter_has_var(INPUT_POST, 'submit')){

                    $pseudo =  filter_input(INPUT_POST,'pseudo', FILTER_SANITIZE_STRING);
                    if($pseudo == NULL || $pseudo == false || empty($pseudo)){
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner un pseudo.</div>';
                    }

                    $mdp =  filter_input(INPUT_POST,'mdp', FILTER_SANITIZE_STRING);
                    if($mdp == NULL || $mdp == false || empty($mdp)){
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner un mot de passe.</div>';
                    }

                    $nom =  filter_input(INPUT_POST,'nom', FILTER_SANITIZE_STRING);
                    if($nom == NULL || $nom == false || empty($nom)){
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner votre nom.</div>';
                    }

                    $prenom =  filter_input(INPUT_POST,'prenom', FILTER_SANITIZE_STRING);
                    if($prenom == NULL || $prenom == false || empty($prenom)){
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner votre prénom.</div>';
                    }

                    $email =  filter_input(INPUT_POST,'email', FILTER_VALIDATE_EMAIL);                
                    if($email == NULL){                
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner votre adresse email.</div>';
                    }elseif($email == false){
                        $errors[] = '<div class="alert alert-warning">L\'adresse email n\'est pas valide.</div>';
                        $email = filter_input(INPUT_POST,'email', FILTER_SANITIZE_EMAIL);
                    }

                    $sexe =  filter_input(INPUT_POST,'sexe', FILTER_SANITIZE_STRING);

                    $ville =  filter_input(INPUT_POST,'ville', FILTER_SANITIZE_STRING);
                    if($ville == NULL || $ville == false || empty($ville)){
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner une ville.</div>';
                    }

                    $cp =  filter_input(INPUT_POST,'cp', FILTER_SANITIZE_STRING);
                    if($cp == NULL || $cp == false || empty($cp)){
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner un code postal.</div>';
                    }

                    $adresse =  filter_input(INPUT_POST,'adresse', FILTER_SANITIZE_STRING);
                    if($adresse == NULL || $adresse == false || empty($adresse)){
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner une adresse.</div>';
                    }       


                    if(count($errors) == 0){             

                        // NE MET UN MD5 QUE SI L'UTILISATEUR ENTRE UN NOUVEAU MOT DE PASSE
                        if($mdp != $modMember['mdp']){
                            $mdp = md5($mdp);
                        }

                        $member->setPseudo($pseudo);
                        $member->setMdp($mdp);
                        $member->setNom($nom);
                        $member->setPrenom($prenom);
                        $member->setEmail($email);
                        $member->setSexe($sexe);
                        $member->setVille($ville);
                        $member->setCp($cp);
                        $member->setAdresse($adresse);

                        $resultArray = $member->checkLengthNames();                                    

                        $msgError = "";

                        for($i = 0; $i < count($resultArray); $i++){
                           if(!$resultArray[$i]->getPassed()){
                               $msgError .= $resultArray[$i]->getErrorMessage();
                               $msgError .= "<br/>";
                           }
                        }

                        if($msgError == ""){

                            $member->modifyMember();                            
                            header('location:index.php?controller=members&action=profil');                            

                        }else{
                            $msg .= $msgError;
                        }
                    }else{
                        $errors[] .= '<div class="alert alert-warning">Le profil n\'a pas été modifié</div>';
                    }                        
                }
            }else{
                $msg .= '<div class="alert alert-danger">';
                    $msg .= 'Vous n\'avez pas le droit d\'accéder à cette page.';
                $msg .= '</div>';
            }
            
            include 'views/members/modifyProfile.php';
        }
        
        public function registered(){
            
            $msg = '';            
            $msg .= '<div class="alert alert-success">Vous êtes désormais inscrit sur le site !</div>';            
            $msg .= '<a class="btn btn-success btnDetail" href="index.php?controller=members&action=connect">Se connecter</a><br/>';
            $msg .= '<a class="btn btn-success btnDetail" href="index.php">Aller à l\'accueil</a>';
            
            include 'views/members/registered.php';
        }
        
        public function listMember()
        {            
            $msg = "";
            $table = "";
            $member = new Member;
            
            if($member->sessionExists() && $member->userAdmin()){
                $list = $member->list_member();
                                  
                $table .= '<table id="members" class="table table-hover">';
                $table .= '<tr>';
                    $table .= '<th>Id Membre</th>';
                    $table .= '<th>Pseudo</th>';
                    $table .= '<th>MDP</th>';
                    $table .= '<th>Nom</th>';
                    $table .= '<th>Prenom</th>';
                    $table .= '<th>Email</th>';
                    $table .= '<th>Sexe</th>';
                    $table .= '<th>Ville</th>';
                    $table .= '<th>Code Postal</th>';
                    $table .= '<th>Adresse</th>';
                    $table .= '<th>Statut</th>';
                    $table .= '<th>Supprimer</th>';
                $table .= '</tr>';

                foreach($list as $valeur){
                    $table .= '<tr>';
                        $table .= '<td>' . $valeur['id_membre'] . '</td>';
                        $table .= '<td>' . $valeur['pseudo'] . '</td>';
                        $table .= '<td class="mdp">' . $valeur['mdp'] . '</td>';
                        $table .= '<td>' . $valeur['nom'] . '</td>';
                        $table .= '<td>' . $valeur['prenom'] . '</td>';
                        $table .= '<td>' . $valeur['email'] . '</td>';
                        $table .= '<td>' . $valeur['sexe'] . '</td>';
                        $table .= '<td>' . $valeur['ville'] . '</td>';
                        $table .= '<td>' . $valeur['cp'] . '</td>';
                        $table .= '<td class="adresse">' . $valeur['adresse'] . '</td>';
                        $table .= '<td class="statut">' . $valeur['statut'] . '</td>';
                        $table .= '<td class="supprimer"><a href="index.php?controller=members&action=deleteMember&id=' . $valeur['id_membre'] . '" title="Supprimer"><i class="fa fa-trash-o fa-2x"></i></a></td>';
                   $table .= '</tr>';
                }
                $table .= '</table>';

                $table .= '<a class="btn btn-success" href="index.php?controller=members&action=createAdmin" title="Nouvel admin">Créer un nouveau compte administrateur</a>';
            }else{
                $msg .= '<div class="alert alert-danger">';
                    $msg .= 'Vous n\'avez pas le droit d\'accéder à cette page.';
                $msg .= '</div>';
            }                    

            include "views/members/listMembers.php";
        }
        
        public function createAdmin()
	{
            $msg = "";
            $errors = array();
            // Instancie un nouvel objet member
            $member = new Member;
            
            if($member->sessionExists() && $member->userAdmin()){
                if(filter_has_var(INPUT_POST,'submit')){
                    
                    $pseudo =  filter_input(INPUT_POST,'pseudo', FILTER_SANITIZE_STRING);
                    if($pseudo == NULL || $pseudo == false || empty($pseudo)){
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner un pseudo.</div>';
                    }

                    $mdp =  filter_input(INPUT_POST,'mdp', FILTER_SANITIZE_STRING);
                    if($mdp == NULL || $mdp == false || empty($mdp)){
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner un mot de passe.</div>';
                    }

                    $nom =  filter_input(INPUT_POST,'nom', FILTER_SANITIZE_STRING);
                    if($nom == NULL || $nom == false || empty($nom)){
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner votre nom.</div>';
                    }

                    $prenom =  filter_input(INPUT_POST,'prenom', FILTER_SANITIZE_STRING);
                    if($prenom == NULL || $prenom == false || empty($prenom)){
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner votre prénom.</div>';
                    }

                    $email =  filter_input(INPUT_POST,'email', FILTER_VALIDATE_EMAIL);                
                    if($email == NULL){                
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner votre adresse email.</div>';
                    }elseif($email == false){
                        $errors[] = '<div class="alert alert-warning">L\'adresse email n\'est pas valide.</div>';
                        $email = filter_input(INPUT_POST,'email', FILTER_SANITIZE_EMAIL);
                    }

                    $sexe =  filter_input(INPUT_POST,'sexe', FILTER_SANITIZE_STRING);

                    $ville =  filter_input(INPUT_POST,'ville', FILTER_SANITIZE_STRING);
                    if($ville == NULL || $ville == false || empty($ville)){
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner une ville.</div>';
                    }

                    $cp =  filter_input(INPUT_POST,'cp', FILTER_SANITIZE_STRING);
                    if($cp == NULL || $cp == false || empty($cp)){
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner un code postal.</div>';
                    }

                    $adresse =  filter_input(INPUT_POST,'adresse', FILTER_SANITIZE_STRING);
                    if($adresse == NULL || $adresse == false || empty($adresse)){
                        $errors[] = '<div class="alert alert-warning">Vous devez renseigner une adresse.</div>';
                    }  
                    
//                    $pseudo = htmlentities($_POST['pseudo'], ENT_QUOTES, "utf-8");
//                    $mdp = htmlentities($_POST['mdp'], ENT_QUOTES, "utf-8");
//                    $nom = htmlentities($_POST['nom'], ENT_QUOTES, "utf-8");
//                    $prenom = htmlentities($_POST['prenom'], ENT_QUOTES, "utf-8");
//                    $email = htmlentities($_POST['email'], ENT_QUOTES, "utf-8");
//                    $sexe = htmlentities($_POST['sexe'], ENT_QUOTES, "utf-8");
//                    $ville = htmlentities($_POST['ville'], ENT_QUOTES, "utf-8");
//                    $cp = htmlentities($_POST['cp'], ENT_QUOTES, "utf-8");
//                    $adresse = htmlentities($_POST['adresse'], ENT_QUOTES, "utf-8");
                    $statut = 1;
                    
                    $mdp = md5($mdp);
                    
                    $member->setPseudo($pseudo);
                    $member->setMdp($mdp);
                    $member->setNom($nom);
                    $member->setPrenom($prenom);
                    $member->setEmail($email);
                    $member->setSexe($sexe);
                    $member->setVille($ville);
                    $member->setCp($cp);
                    $member->setAdresse($adresse);
                    $member->setStatut($statut);

                    // Verification dans le model
                    $resultArray = $member->checkAllConstraint();

                    $msgError = "";                    
                    for($i = 0; $i < count($resultArray); $i++){
                       if(!$resultArray[$i]->getPassed()){
                           $msgError .= $resultArray[$i]->getErrorMessage();
                           $msgError .= "<br/>";
                       }
                    }

                    // Si le message d'erreur est vide, je rentre le membre en base avec la méthode add_member
                    // Sinon j'affiche les erreurs
                    if($msgError == ""){
                        $member->addAdmin();
                        header('location:index.php?controller=members&action=admincreated'); 
                        
                    }else{
                        $msg .= $msgError;
                    }
                }
            }else{
                $msg .= '<div class="alert alert-danger">';
                    $msg .= 'Vous n\'avez pas le droit d\'accéder à cette page.';
                $msg .= '</div>';
            }         

            include "views/members/createAdmin.php";		
	}
        
        public function admincreated(){
            $msg = ''; 
            
            $msg .= '<div class="alert alert-success">';
                $msg .= 'Administrateur créé !';
            $msg .= '</div>';
            $msg .= '<a class="btn btn-success btnMargin" href="index.php?controller=members&action=listMember">Voir les membres</a><br/>';
            $msg .= '<a class="btn btn-success btnMargin" href="index.php">Retour à l\'accueil</a>';
            
            include 'views/members/admincreated.php';
        }
        
        
        public function deleteMember()
        {
            $msg="";
            if(filter_has_var(INPUT_GET, 'id')){
                $id = filter_input(INPUT_GET, 'id',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            
            $member = new Member;
            
            if($member->sessionExists() && $member->userAdmin()){
                $member->setId($id);
                // SUPPRESSION DU MEMBRE DANS LA TABLE membre
                $member->del_member();
                
                // DESINSCRIPTION DE LA NEWSLETTER SI INSCRIT
                if($membersubscribed){
                    $member->access_modelNewsletter_unsubscribe();
                }
                
                // SUPPRESSION DES COMMENTAIRES DANS TABLE avis
                $member->access_modelComment_deleteAllCommentsByMemberId();
                
                // CONSERVATION DES COMMANDES - AVEC PSEUDO "MEMBRE SUPPRIMÉ" POUR AFF DU DETAIL COMM
                // VOIR CONTROLLER ORDERS
                
                
                $msg .= '<div class="alert alert-success">';
                    $msg .= 'Membre supprimé !<br/>';
                    $msg .= 'Les commandes associées ont été conservées.<br/>';
                $msg .= '</div>';
            }else{
                $msg .= '<div class="alert alert-danger">';
                    $msg .= 'Vous n\'avez pas le droit d\'accéder à cette page.';
                $msg .= '</div>';
            }            
                          
            include "views/members/delete_member.php";
        }
        
                
        public function connect()
        {
                                  
            $msg="";
            $errors = array(); 
            
//            if(isset($_SESSION['pseudo']) || isset($_COOKIE['pseudo'])){
//                header('location:index.php?controller=members&action=connected');
//            }
            
            if(filter_has_var(INPUT_POST, 'connexion')){
                
                $pseudo = filter_input(INPUT_POST,'pseudo', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_SANITIZE_STRING);
                if($pseudo == NULL || $pseudo == false || empty($pseudo)){
                    $errors[] = '<div class="alert alert-warning">Vous devez fournir un pseudo.</div>';
                }
                
                $mdp = filter_input(INPUT_POST,'mdp', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_SANITIZE_STRING);
                if($mdp == NULL || $mdp == false || empty($mdp)){
                    $errors[] = '<div class="alert alert-warning">Vous devez fournir un mot de passe valide.</div>';
                }
                
                $mdp = md5($mdp);
                
//                $remember = filter_input(INPUT_POST,'remember');              
//                
//                if($remember === 1){
//                    setcookie('pseudo', $pseudo, time()+7600);                    
//                }else{
//                    setcookie('pseudo', $pseudo, false);                    
//                }
                
                if(count($errors) == 0){
                    // Instancie un nouvel objet member
                    $member = new Member;
                    $member->setPseudo($pseudo);                
                    $member->setMdp($mdp);

                    $resultArray = $member->checkConnect();

                    $msgError = "";                

                    for($i = 0; $i < count($resultArray); $i++){
                           if(!$resultArray[$i]->getPassed()){
                               $msgError .= $resultArray[$i]->getErrorMessage();
                               $msgError .= "<br/>";
                           }
                    }

                    if($msgError == ''){
                        $member->openSession();
                        header('location:index.php?controller=members&action=connected');
                        
                    }else{
                        $msg .= $msgError;
                    }
                }else{
                    $errors[] = '<div class="alert alert-warning">Il y a un problème avec vos identifiants.</div>';
                }               
            }
            
            $msg .= '<span>Pas encore inscrit ?</span><a href="index.php?controller=members&action=add_member" title="S\'inscrire">  Inscrivez-vous ici</a><br/>';
            $msg .= '<a href="index.php?controller=members&action=passwordRecovery" title="Mot de passe oublié">Mot de passe oublié ?</a>';
            
            include "views/members/connect_member.php";
        }
        
        public function passwordRecovery(){
            
            $msg ='';
            $errors = array(); 
            
            if(filter_has_var(INPUT_POST, 'send')){
                
                $email =  filter_input(INPUT_POST,'email', FILTER_VALIDATE_EMAIL);
                    if($email == NULL){                
                        $errors[] = 'Vous devez renseigner votre adresse email.';
                    }elseif($email == false){
                        $errors[] = 'L\'adresse email n\'est pas valide.';
                        $email = filter_input(INPUT_POST,'exp', FILTER_SANITIZE_EMAIL);
                    }
                   
                if(count($errors) == 0){
                    
                    // CHECKER QUE L'ADRESSE EST EN BASE
                    $member = new Member();
                    $member->setEmail($email);                

                    $resultArray = $member->checkEmail();

                    $msgError = "";                

                    for($i = 0; $i < count($resultArray); $i++){
                           if(!$resultArray[$i]->getPassed()){
                               $msgError .= $resultArray[$i]->getErrorMessage();
                               $msgError .= "<br/>";
                           }
                    }
                    
                    // SI L'ADRESSE EXISTE (ET DONC L'UTILISATEUR)
                    if($msgError == ''){
                        // GENERATION D'UN MOT DE PASSE ALEATOIRE                    
                        $tempPass = $member->randomPassword();
                                                
                        //REMPLACEMENT EN BASE AVEC LE NOUVEAU PASS
                        $member->setMdp($tempPass);
                        $member->replacePassword();
                        
                        // ENVOI DU MOT DE PASSE TEMPORAIRE A L'UTILISATEUR
                        $exp = 'noreply@free.fr';
                        $to = $email;                        
                        $subject = 'Réinitialisation de votre mot de passe';
                        
                        $message = '';
                        $message .= '<html><body><p>Bonjour, veuillez trouver ci-dessous votre mot de passe temporaire: </p>';
                        $message .= '<p>' . $tempPass . '</p>';
                        $message .= '<p>Suivez le lien ci-dessous pour enregistrer un nouveau mot de passe: </p>';
                        $message .= '<a href="http://www.lokisalle.normanwebdev.com/index.php?controller=members&action=resetPassword&pass='.$tempPass.'">Réinitialiser mon mot de passe</a><br/>';
                        $message .= '<p>Merci</p></body></html>';

                        $type = 'HTML'; // or HTML
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

                        $msg .= 'Un mot de passe temporaire a été envoyé sur votre boîte mail.';
                        
                    }else{
                        $msg .= $msgError;
                    }                    
                }else{
                    $errors[] = 'Votre nouveau mot de passe n\'a pas été envoyé.';
                }
            }
            
            include 'views/members/passwordRecovery.php';
        }
        
        public function resetPassword(){
            $msg = '';
            $errors = array(); 
            
            $tempPass = filter_input(INPUT_GET, 'pass', FILTER_SANITIZE_URL);
            
            if($tempPass == NULL || $tempPass == false){                
                $errors[] = 'Vous n\'avez pas le droit d\'accéder à cette page.';
            }
            
            // SI LA PREMIERE ETAPE EST PASSEE
            if(count($errors) == 0){
            
                // VERIFIER QUE LE PASSE TEMPORAIRE EXISTE EN BASE
                $member = new Member();
                $member->setMdp($tempPass);                

                $resultArray = $member->checkMdp();

                $msgError = "";                

                for($i = 0; $i < count($resultArray); $i++){
                       if(!$resultArray[$i]->getPassed()){
                           $msgError .= $resultArray[$i]->getErrorMessage();
                           $msgError .= "<br/>";
                       }
                }

                // SI LE PASSE TEMPORAIRE EST BIEN EN BASE
                if($msgError == ''){
                    if(filter_has_var(INPUT_POST, 'send')){
                        
                        $email =  filter_input(INPUT_POST,'email', FILTER_VALIDATE_EMAIL);
                        if($email == NULL){                
                            $errors[] = 'Vous devez renseigner votre adresse email.';
                        }elseif($email == false){
                            $errors[] = 'L\'adresse email n\'est pas valide.';
                            $email = filter_input(INPUT_POST,'email', FILTER_SANITIZE_EMAIL);
                        }
                        
                        $password = filter_input(INPUT_POST,'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_SANITIZE_STRING);
                        if($password == NULL || $password == false || empty($password)){
                            $errors[] = 'Vous devez entrer un mot de passe.';
                        }
                        
                        if(count($errors) == 0){
                            
                            $member->setMdp(md5($password));
                            $member->setEmail($email);
                            
                            $member->replacePassword();
                            $msg .= '<div class="alert alert-success">';
                                $msg .= 'Votre mot de passe a bien été réinitialisé.';
                            $msg .= '</div>';
                            
                            //ALLER A LA PAGE CONNEXION OU ACCUEIL
                            $msg .= '<a class="btn btn-success btnAccueil" href="index.php" title="Accueil">Accueil</a>';
                            $msg .= '<a class="btn btn-success btnAccueil" href="index.php?controller=members&action=connect" title="Connexion">Se connecter</a>';
                            
                        }else{
                            $errors[] = 'Votre mot de passe n\'est pas réinitalisé.';
                        } 
                    }
                }else{
                    $msg .= $msgError;
                }
            }else{
                $errors[] = '';
            }
            
            include 'views/members/resetPassword.php';
        }
        
        public function connected(){
            $msg = '';
            $msg .= '<div class="alert alert-success">';
                $msg .= 'Pseudo et Mot de passe acceptés, vous êtes connecté.';
            $msg .= '</div>';
            $msg .= '<a class="btn btn-success btnDetail" href="index.php?controller=members&action=profil">Voir mon profil</a><br/>';
            $msg .= '<a class="btn btn-success" href="index.php">Retour à l\'accueil</a>';
            
            include 'views/members/connected.php';
        }
        
        public function disconnect(){
            $msg = "";
            if(isset($_SESSION)){
                session_destroy();
                
//                if(isset($_COOKIE['pseudo'])){
//                    setcookie("pseudo", $_COOKIE['pseudo'], false);                    
//                }
            }
            
            $msg .= '<div class="alert alert-success">Fin de la session !</div>';
            $msg .= '<a class="btn btn-success btnPanier" href="index.php" title="Retour à l\'accueil">Retourner à l\'accueil</a>';
            
            include "views/members/disconnect_member.php";
        }
        
        public function profil(){
            $msg = "";
            
            $member = new Member;
            
            // LA PAGE N'EST ACCESSIBLE QUE SI UN MEMBRE OU ADMIN EST CONNECTE
            if($member->sessionExists()){
                $memberID = $_SESSION['user']['id_membre'];
                $member->setId($memberID);
                $connectedMember = $member->retrieveMember($memberID);
                $orders = $member->access_modelOrder_findOrdersById();                
                
                $msg .= '<div class="panel panel-primary">';
                    $msg .= '<div class="panel-heading">';
                        if($connectedMember['statut'] == 1){
                            $msg .= '<span class="profilTitle">Administrateur : </span>';
                        }else{
                            $msg .= '<span class="profilTitle">Membre : </span>';
                        }
                        $msg .= '<span class="profilTitle">' . $connectedMember['pseudo'] . '</span>';
                    $msg .= '</div>';
                    $msg .= '<div class="panel-body ficheProfil">';
                        $msg .= '<p><label class="profilDetail">Pseudo: </label>' . $connectedMember['pseudo'] . '</p>';
                        $msg .= '<p><label class="profilDetail">Nom: </label>' . $connectedMember['nom'] . '</p>';
                        $msg .= '<p><label class="profilDetail">Prénom: </label>' . $connectedMember['prenom'] . '</p>';
                        $msg .= '<p><label class="profilDetail">Adresse mail: </label>' . $connectedMember['email'] . '</p>';
                        $msg .= '<p><label class="profilDetail">Adresse: </label>' . $connectedMember['adresse'] . '</p>';
                        $msg .= '<p><label class="profilDetail">Code postal: </label>' . $connectedMember['cp'] . '</p>';
                        $msg .= '<p><label class="profilDetail">Ville: </label>' . $connectedMember['ville'] . '</p>';
                        $msg .= '<a class="btn btn-default pull-right" href="index.php?controller=members&action=modifyProfile&id='.$memberID.'" title="Modifier mon profil">Mettre à jour mes informations</a>';
                        $msg .= '<div class="clearfix"></div><br/>';
                        if($connectedMember['statut'] == 1){
                            $msg .= '<div class="panel panel-default">';
                                $msg .= '<div class="panel-body">';
                                    $msg .= 'Vous êtes administrateur';
                                $msg .= '</div>';
                            $msg .= '</div>';
                        }
                    $msg .= '</div>';
                $msg .= '</div>';
                
                $msg .= '<div class="panel panel-primary">';
                    $msg .= '<div class="panel-heading">';
                        $msg.='Vos dernières commandes';
                    $msg .= '</div>';
                    $msg .= '<div class="panel-body">';
                        $msg .= '<table class="table table-hover">';
                            $msg .= '<tr>';
                                $msg .= '<th>Numéro de suivi</th>';
                                $msg .= '<th>Montant</th>';
                                $msg .= '<th>Date</th>';
                            $msg .= '</tr>';
                            
                            foreach($orders as $lastorder){   
                                
                                $date = new DateTime($lastorder['date']);
                                $lastorder['date'] = $date->format('d-m-Y');
                                
                                $msg .= '<tr>';
                                    $msg .= '<td>'.$lastorder['id_commande'].'</td>';
                                    $msg .= '<td>'.$lastorder['montant'].'</td>';
                                    $msg .= '<td>'.$lastorder['date'].'</td>';
                                $msg .= '</tr>';
                            }
                        $msg .= '</table>';
                    $msg .= '</div>';
                $msg .= '</div>';
            }else{
                $msg .= 'Vous n\'avez pas le droit d\'accéder à cette page.<br/>Vous devez être connecté en tant que membre.';
            }         
            
            include "views/members/profile.php";
        }
        
        public function contactus(){
            $msg = "";
            $errors = array(); // Tableau des erreurs de saisie
            
            $member = new Member();
            
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
                    // Envoi de l'email récapitulatif à l'administrateur
                    $to = 'norman.rosenstech@gmail.com';          

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

                    $msg .= 'Message envoyé !';
                }else{
                    $errors[] = 'Votre message n\'a pas été envoyé.';
                }              
            }          
            
            include 'views/members/contactus.php';
        }
}

?>