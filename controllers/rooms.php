<?php

include "models/room.php";

class Rooms{
    
    public function addRoom(){
        $msg = "";
        $errors = array();
        
        // Instancie un nouvel objet room
        $room = new Room;
        
        if($room->access_ModelMember_sessionExists() && $room->access_ModelMember_userAdmin()){
            if($_POST){
                $pays = htmlentities($_POST['pays'], ENT_QUOTES, "utf-8");
                $ville = htmlentities($_POST['ville'], ENT_QUOTES, "utf-8");
                $adresse = htmlentities($_POST['adresse'], ENT_QUOTES, "utf-8");
                $cp = htmlentities($_POST['cp'], ENT_QUOTES, "utf-8");
                $titre = htmlentities($_POST['titre'], ENT_QUOTES, "utf-8");
//                $description = htmlentities($_POST['description'], ENT_QUOTES, "UTF-8");
                
                $description =  filter_input(INPUT_POST,'description', FILTER_SANITIZE_STRING);
                if($description == NULL || $description == false || empty($description)){
                    $errors[] = '<div class="alert alert-warning">Vous devez donner une description pour la salle.</div>';
                }
                    
                $photo = $_FILES['photo'];
                $capacite = htmlentities($_POST['capacite'], ENT_QUOTES, "utf-8");
                $categorie = htmlentities($_POST['categorie'], ENT_QUOTES, "utf-8");            

//                $this->resize_image($photo, $photoResized, 600, false);
//                $photo = $photoResized;
                
                if(count($errors) == 0){
                
                    // Je remplis le nouvel objet avec les valeurs récupérées dans le formulaire
                    $room->setPays($pays);
                    $room->setVille($ville);
                    $room->setAdresse($adresse);
                    $room->setCP($cp);
                    $room->setTitre($titre);
                    $room->setDescription($description);
                    $room->setPhoto($photo);
                    $room->setCapacite($capacite);
                    $room->setCategorie($categorie);

                    // VERIFICATIONS

                    $resultArray = $room->checkAll();

                    $msgError = "";

                    for($i = 0; $i < count($resultArray); $i++){
                       if(!$resultArray[$i]->getPassed()){
                           $msgError .= $resultArray[$i]->getErrorMessage();
                           $msgError .= "<br/>";
                       }
                    }

                    // Si le message d'erreur est vide, je rentre la salle en base avec la méthode addRoom()
                    // Sinon j'affiche les erreurs
                    if($msgError == ""){
                        $room->addRoom();                        
                        header('location:index.php?controller=rooms&action=roomAdded');
                    }else{
                        $msg .= $msgError;
                    }
                }else{
                    $errors[] .= '<div class="alert alert-warning">La salle n\'a pas été enregistrée.</div>';
                }
            }
        }else{
            $msg .= '<div class="alert alert-danger">Vous n\'avez pas le droit d\'accéder à cette page.</div>';
        }       
        
        include "views/rooms/addRoom.php";
    }
    
//    public function resize_image($file, $destination, $w, $h){
//        //Get the original image dimensions + type
//        list($source_width, $source_height, $source_type) = getimagesize($file);
//
//        //Figure out if we need to create a new JPG, PNG or GIF
//        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
//        if ($ext == "jpg" || $ext == "jpeg") {
//            $source_gdim=imagecreatefromjpeg($file);
//        } elseif ($ext == "png") {
//            $source_gdim=imagecreatefrompng($file);
//        } elseif ($ext == "gif") {
//            $source_gdim=imagecreatefromgif($file);
//        } else {
//            //Invalid file type? Return.
//            return;
//        }
//
//        //If a width is supplied, but height is false, then we need to resize by width instead of cropping
//        if ($w && !$h) {
//            $ratio = $w / $source_width;
//            $temp_width = $w;
//            $temp_height = $source_height * $ratio;
//
//            $desired_gdim = imagecreatetruecolor($temp_width, $temp_height);
//            imagecopyresampled(
//                $desired_gdim,
//                $source_gdim,
//                0, 0,
//                0, 0,
//                $temp_width, $temp_height,
//                $source_width, $source_height
//            );
//        } else {
//            $source_aspect_ratio = $source_width / $source_height;
//            $desired_aspect_ratio = $w / $h;
//
//            if ($source_aspect_ratio > $desired_aspect_ratio) {
//                /*
//                 * Triggered when source image is wider
//                 */
//                $temp_height = $h;
//                $temp_width = ( int ) ($h * $source_aspect_ratio);
//            } else {
//                /*
//                 * Triggered otherwise (i.e. source image is similar or taller)
//                 */
//                $temp_width = $w;
//                $temp_height = ( int ) ($w / $source_aspect_ratio);
//            }
//
//            /*
//             * Resize the image into a temporary GD image
//             */
//
//            $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
//            imagecopyresampled(
//                $temp_gdim,
//                $source_gdim,
//                0, 0,
//                0, 0,
//                $temp_width, $temp_height,
//                $source_width, $source_height
//            );
//
//            /*
//             * Copy cropped region from temporary image into the desired GD image
//             */
//
//            $x0 = ($temp_width - $w) / 2;
//            $y0 = ($temp_height - $h) / 2;
//            $desired_gdim = imagecreatetruecolor($w, $h);
//            imagecopy(
//                $desired_gdim,
//                $temp_gdim,
//                0, 0,
//                $x0, $y0,
//                $w, $h
//            );
//        }
//
//        /*
//         * Render the image
//         * Alternatively, you can save the image in file-system or database
//         */
//
//        if ($ext == "jpg" || $ext == "jpeg") {
//            ImageJpeg($desired_gdim,$destination,100);
//        } elseif ($ext == "png") {
//            ImagePng($desired_gdim,$destination);
//        } elseif ($ext == "gif") {
//            ImageGif($desired_gdim,$destination);
//        } else {
//            return;
//        }
//
//        ImageDestroy ($desired_gdim);
//    }
    
    public function roomAdded(){
        $msg = '';
        
        $msg .= '<div class="alert alert-success">La salle a bien été enregistrée !</div>';            
        $msg .= '<a class="btn btn-success btnRoom" href="index.php?controller=rooms&action=listRooms" title="Afficher les salles">Afficher les salles</a><br/>';
        $msg .= '<a class="btn btn-success btnRoom" href="index.php?controller=rooms&action=addRoom" title="Ajouter une nouvelle salle">Ajouter une nouvelle salle</a><br/>';
        $msg .= '<a class="btn btn-success btnRoom" href="index.php" title="Accueil">Aller à l\'accueil</a>';
        
        include 'views/rooms/roomAdded.php';
    }
    
    public function listRooms(){
        $msg = "";
                
        $room = new Room;        
        if($room->access_ModelMember_sessionExists() && $room->access_ModelMember_userAdmin()){            
            $list = $room->listRooms();
            rsort($list);
            $msg .= '<div class="row">';
                foreach($list as $valeur){
                    $msg .= '<div class="col-sm-4 prods">';
                        $msg .= '<div class="blocProduit">';
                            $msg .= '<img src="' . $valeur['photo'] . '" width=360 /><br/>';
                            $msg .= '<div class="blocTexte">';
                                $msg .= '<h4>' . $valeur['titre'] . '</h4>';                                
                                $msg .= '<h3>Catégorie: ' . $valeur['categorie'] .'</h3>';                                
                                $msg .= '<p>Capacité: ' . $valeur['capacite'] . ' personnes.</p>';                                              
                                $msg .= '<p>'.strtoupper($valeur['ville']).'</p>';                                              
                                $msg .= '<p><strong>Description: </strong></p>'; 
                                $msg .= '<p class="description">' . $valeur['description'] . '</p>';                                
                                $msg .= '<a class="btn btn-success btnAccueil" href="index.php?controller=rooms&action=modifyRoom&id=' . $valeur['id_salle'] . '" title="Modifier">Modifier </a>';
                                $msg .= "<br/>";
                                $msg .= '<a class="btn btn-success btnAccueil" href="index.php?controller=rooms&action=deleteRoom&id=' . $valeur['id_salle'] . '" title="Supprimer">Supprimer</a>';
                            $msg .= '</div>';                    
                        $msg .= '</div>';                    
                    $msg .= '</div>';                    
                }
            $msg .= '</div>';
        }else{
            $msg .= 'Vous n\'avez pas le droit d\'accéder à cette page.<br/>';
        } 
        
        include "views/rooms/listRooms.php";
    }
    
    public function modifyRoom(){
        $msg = "";
        $room = new Room;   
        
        
        if($room->access_ModelMember_sessionExists() && $room->access_ModelMember_userAdmin()){

            // Récupération de l'id passée en URL
            $id = $_GET['id'];            
            $room->setIdSalle($id);

            $modRoom = $room->findRoom();

            if($_POST){
                $id_salle = $modRoom['id_salle'];
                $pays = htmlentities($_POST['pays'], ENT_QUOTES, "utf-8");
                $ville = htmlentities($_POST['ville'], ENT_QUOTES, "utf-8");
                $adresse = htmlentities($_POST['adresse'], ENT_QUOTES, "utf-8");
                $cp = htmlentities($_POST['cp'], ENT_QUOTES, "utf-8");
                $titre = htmlentities($_POST['titre'], ENT_QUOTES, "utf-8");
                $description = htmlentities($_POST['description'], ENT_QUOTES, "utf-8");
                $photo = $_FILES['photo'];
                $capacite = htmlentities($_POST['capacite'], ENT_QUOTES, "utf-8");
                $categorie = htmlentities($_POST['categorie'], ENT_QUOTES, "utf-8");

                // Instancie un nouvel objet room
                $room = new Room;

                $room->setIdSalle($id_salle);
                // Je remplis le nouvel objet avec les valeurs récupérées dans le formulaire
                $room->setPays($pays);
                $room->setVille($ville);
                $room->setAdresse($adresse);
                $room->setCP($cp);
                $room->setTitre($titre);
                $room->setDescription($description);
                $room->setPhoto($photo);
                $room->setCapacite($capacite);
                $room->setCategorie($categorie);

                $msgError = "";

                // VERIFICATIONS SI LA PHOTO EST CHANGEE
                 if(!empty($_FILES['photo']['name'])){
                     $resultArray = $room->checkPhotoReplace();

                     for($i = 0; $i < count($resultArray); $i++){
                        if(!$resultArray[$i]->getPassed()){
                        $msgError .= $resultArray[$i]->getErrorMessage();
                        $msgError .= "<br/>";
                        }
                     }
                 }


                if($msgError == ""){
                    $room->replaceRoom();
                    header('location:index.php?controller=rooms&action=roomModified');
//                    $msg = '<p class="validation">Bravo, la salle a bien été enregistrée</p>';
                    // Appeler une autre page pour rafficher la salle modifiée ? 

                }else{
                    $msg .= $msgError;
                }
            }
        }else{
            $msg .= 'Vous n\'avez pas le droit d\'accéder à cette page.<br/>';
        } 
            
            
        include "views/rooms/modifyRooms.php";
    }
    
    public function roomModified(){
        $msg = '';
        
        $msg .= '<div class="alert alert-success">La salle a bien été modifiée !</div>';            
        $msg .= '<a class="btn btn-success btnRoom" href="index.php?controller=rooms&action=listRooms" title="Afficher les salles">Afficher les salles</a><br/>';
        $msg .= '<a class="btn btn-success btnRoom" href="index.php?controller=rooms&action=addRoom" title="Ajouter une autre salle">Ajouter une autre salle</a><br/>';
        $msg .= '<a class="btn btn-success btnRoom" href="index.php" title="Accueil">Aller à l\'accueil</a>';
        
        include 'views/rooms/roomModified.php';
    }
    
    public function deleteRoom(){
        
        $msg="";
        
        if(filter_has_var(INPUT_GET, 'id')){
             $id = filter_input(INPUT_GET, 'id',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
        
        $room = new Room;
        
        if($room->access_ModelMember_sessionExists() && $room->access_ModelMember_userAdmin()){
        
            $room->setIdSalle($id);
            
            // SUPPRESSION DES AVIS RATTACHÉS
            $room->access_ModelComment_deleteAllCommentsByRoomId();
            
            // CONSERVATION DES PRODUITS RATTACHÉS POUR AFFICHAGE COMMANDES PASSÉES MAIS PASSAGE EN ETAT 0 DE TOUS PRODUITS(HORS CIRCUIT)
            $room->access_ModelProduct_setToUnavailableByIdSalle();
            
            $room->deleteRoom();

            $msg .= '<div class="alert alert-success">Salle supprimée !</div>';
        
        }else{
            $msg .= '<div class="alert alert-danger>"Vous n\'avez pas le droit d\'accéder à cette page.</div>';
        } 
        
        include "views/rooms/deleteRoom.php";
    }
    
    public function accueil(){
        $msg = "";
        $salle = "";
        
        // Recupération des 3 derniers produits
        $room = new Room;        
        $listProd = $room->access_ModelProduct_List(); 
        rsort($listProd);
        
        // Affichage des 3 derniers produits sauf les produits non réservables etat = 0
        $i=0;
        $j=0;
        
        $msg .= '<div class="row">';
            while(isset($listProd[$i]['etat']) && $j<3){
                // Si le produit est dispo et si la date est supérieure à aujourd'hui
                if($listProd[$i]['etat'] != 0 && $room->checkProductDate($listProd[$i]['date_arrivee'])){ 
                    $j++;
                    // Conversion des dates en dates Françaises et uniquement j/m/Y
                    $dateArrivee = new DateTime(''. $listProd[$i]['date_arrivee'] .'');    
                    $dateDepart = new DateTime(''. $listProd[$i]['date_depart'] .'');         
                    $listProd[$i]['date_arrivee'] = $dateArrivee->format('d-m-Y');
                    $listProd[$i]['date_depart'] = $dateDepart->format('d-m-Y');

                    $room->setIdSalle($listProd[$i]['id_salle']);
                    $salle = $room->findRoom();

                    $msg .= '<div class="col-sm-4">';
                        $msg .= '<div class="blocProduit">';                                                       
                            $msg .= '<a href="index.php?controller=products&action=showProduct&id=' . $listProd[$i]['id_produit'] . '" title="Voir fiche détaillée"><img class="photo" src="' . $salle['photo'] . '" width=360 /></a>';
                            $msg .= '<div class="blocTexte">';
                                $msg .= '<h4>' . $salle['titre'] . "</h4>";
                                $msg .= '<p class="date">Disponible du ' . $listProd[$i]['date_arrivee'] . "</p>";
                                $msg .= '<p class="date">Jusqu\'au ' . $listProd[$i]['date_depart'] . "</p>";
                                $msg .= '<h3>' . $salle['ville'] . '</h3>';
                                $msg .= '<p>Capacité de ' . $salle['capacite'] . " personnes.</p>";
                                $msg .= '<h3 class="prix">' . $listProd[$i]['prix'] . "€</h3>";                
                                $msg .= '<a class="btn btn-success btnAccueil" href="index.php?controller=products&action=showProduct&id=' . $listProd[$i]['id_produit'] . '" title="Voir fiche détaillée">Voir la fiche détaillée</a>';                                
                                if($room->access_ModelMember_sessionExists()){
                                    $msg .= '<a class="btn btn-success btnAccueil" href="index.php?controller=orders&action=addToCart&id='.$listProd[$i]['id_produit'].'" title="Ajouter au panier">Ajouter au panier</a>';
                                }else{
                                    $msg .= '<a class="btn btn-success btnAccueil" href="index.php?controller=members&action=connect" title="Connectez-vous pour l\'ajouter au panier">Connectez-vous pour l\'ajouter au panier</a>';
                                }                            
                            $msg .= '</div>';
                        $msg .= '</div>';
                    $msg .= '</div>';
                }
                $i++;
            }
            $msg .= '<a class="btn btn-default btnReservation btnOffre" href="index.php?controller=products&action=listProductsReservation">TOUTES NOS OFFRES</a>';
            
        $msg .= '</div>';

        include "views/rooms/accueil.php";
    }   
}

