<?php

include "models/product.php";

class Products{
    
    public function addProduct(){
       
        $msg = "";
              
        // Création de la liste d'options déroulante pour les salles
        $product =  new Product;
        
        if($product->access_ModelMember_sessionExists() && $product->access_ModelMember_userAdmin()){
            
            $listRooms = $product->access_ModelRoom_List();
            $optionsSalles = "";
            
//            '<img src="' . $valeur['photo'] . '" width=360 />';

            foreach($listRooms as $valeur){
                $optionsSalles .= '<option value="'. $valeur['id_salle'] .'">'. $valeur['id_salle'] .' - '. $valeur['pays'] .' - '. $valeur['ville'] .' - '. $valeur['adresse'] .' - '. $valeur['titre'] .' - '. $valeur['capacite'] .' - '. $valeur['categorie'] .'</option>';
            }

            // Création de la liste d'options déroulante pour les codes promo
            $listPromos = $product->access_ModelPromotion_List();
            $optionsRemise = "";

            $optionsRemise .= '<option value="">Pas de promo</option>';
            foreach($listPromos as $promo){
                $optionsRemise .= '<option value="'. $promo['id_promo'] .'">'. $promo['id_promo'] .' - '. $promo['code_promo'] .' - '. $promo['reduction'] .'</option>';
            }

            if($_POST){
                $id_salle = $_POST['salles'];
                $dateArrivee = htmlentities($_POST['dateArrivee'], ENT_QUOTES, "utf-8");
                $dateDepart = htmlentities($_POST['dateDepart'], ENT_QUOTES, "utf-8");
                $prix = htmlentities($_POST['prix'], ENT_QUOTES, "utf-8");
                $etat = htmlentities($_POST['etat'], ENT_QUOTES, "utf-8");
                $id_promo = $_POST['remise'];

                // Instancie un nouvel objet room
                $product = new Product;

                // Je remplis le nouvel objet avec les valeurs récupérées dans le formulaire
                $product->setIdSalle($id_salle);
                $product->setDateArrivee($dateArrivee);
                $product->setDateDepart($dateDepart);
                $product->setPrix($prix);
                $product->setEtat($etat);
                $product->setIdPromo($id_promo);

                // Passage des dates entrées au format français en format US
                $product->changeDatesToUSFormat();

                // VERIFICATIONS
                $resultArray = $product->checkAll($product->getIdSalle(), $product->getDateArrivee());


                $msgError = "";

                for($i = 0; $i < count($resultArray); $i++){
                   if(!$resultArray[$i]->getPassed()){
                       $msgError .= $resultArray[$i]->getErrorMessage();
                       $msgError .= "<br/>";
                   }
                }

                // Si le message d'erreur est vide, je rentre le produit en base avec la méthode addProduct()
                // Sinon j'affiche les erreurs
                if($msgError == ""){
                    $product->addProduct();
                    header('location:index.php?controller=products&action=prodAdded');
//                    $msg = '<p class="validation">Bravo, nouveau produit créé !</p>';
                }else{
                    $msg .= $msgError;
                }

            } 
        }else{
            $msg .= 'Vous n\'avez pas le droit d\'accéder à cette page.<br/>';
        }            
        
        include "views/products/addProduct.php";
    }
    
    public function prodAdded(){
        $msg = "";
        
        $msg .= '<div class="alert alert-success">Le produit a bien été créé !</div>';            
        $msg .= '<a class="btn btn-success btnRoom" href="index.php?controller=products&action=listProducts" title="Afficher les produits">Afficher les produits</a><br/>';
        $msg .= '<a class="btn btn-success btnRoom" href="index.php?controller=products&action=addProduct" title="Ajouter un autre produit">Ajouter un autre produit</a><br/>';
        $msg .= '<a class="btn btn-success btnRoom" href="index.php" title="Accueil">Aller à l\'accueil</a>';
        
        include 'views/products/prodAdded.php';
    }
    
    public function listProducts(){
        
        $msg = "";
        $table = "";
        
        // Choix de la colonne
        if(isset($_GET['sort']) && !empty($_GET['sort'])){
            $sort = $_GET['sort'];
        }else{
            $sort = 'id_produit';
        }
        
        // Condition pour l'ordre des colonnes
        if(isset($_GET['switch']) && !empty($_GET['switch'])){
            $switch = $_GET['switch'];
        }else{
            $switch = 'SORT_ASC';
        }
             
        $product = new Product;
        
        if($product->access_ModelMember_sessionExists() && $product->access_ModelMember_userAdmin()){

            $list = $product->listProducts();            
            
            // MODIFICATION DU TABLEAU POUR array_multisort (cf doc)
            foreach ($list as $key => $row) {
                $id_produit[$key]  = $row['id_produit'];
                $date_arrivee[$key]  = $row['date_arrivee'];
                $date_depart[$key] = $row['date_depart'];
                $id_salle[$key] = $row['id_salle'];
                $id_promo[$key] = $row['id_promo'];
                $prix[$key] = $row['prix'];
                $etat[$key] = $row['etat'];
            }
            
            // TRI
            if($switch == 'SORT_ASC'){ 
                if($sort == 'date_arrivee'){
                    array_multisort($date_arrivee, SORT_ASC, $list);
                    $switch = 'SORT_DESC';
                }elseif($sort == 'date_depart'){
                    array_multisort($date_depart, SORT_ASC, $list);
                    $switch = 'SORT_DESC';
                }elseif($sort == 'prix'){
                    array_multisort($prix, SORT_ASC, $list);
                    $switch = 'SORT_DESC';
                }
            }else{
                if($sort == 'date_arrivee'){
                    array_multisort($date_arrivee, SORT_DESC, $list);
                    $switch = 'SORT_ASC';
                }elseif($sort == 'date_depart'){
                    array_multisort($date_depart, SORT_DESC, $list);
                    $switch = 'SORT_ASC';
                }elseif($sort == 'prix'){
                    array_multisort($prix, SORT_DESC, $list);
                    $switch = 'SORT_ASC';
                }
            }

            $table .= '<table id="products" class="table table-hover">';
                $table .= '<tr>';
                    $table .= '<th>id_produit</th>';
                    $table .= '<th class="icon"><a href="index.php?controller=products&action=listProducts&order=date_arrivee&sort=date_arrivee&switch='.$switch.'" title="Date Arrivée">Date d\'arrivée</a></th>';
                    $table .= '<th class="icon"><a href="index.php?controller=products&action=listProducts&order=date_depart&sort=date_depart&switch='.$switch.'" title="Date Départ">Date de départ</a></th>';
                    $table .= '<th class="icon">Salle (Id)</th>';
                    $table .= '<th>Photo</th>';
                    $table .= '<th>Promo (Id)</th>';
                    $table .= '<th><a href="index.php?controller=products&action=listProducts&order=prix&sort=prix&switch='.$switch.'" title="Prix">Prix</a></th>';
                    $table .= '<th class="icon">Etat</th>';
                    $table .= '<th class="icon">Modifier</th>';
                    $table .= '<th class="icon">Supprimer</th>';
                $table .= '</tr>';

                foreach($list as $valeur){
                    
                    // RECUP DE LA PHOTO
                    $room = $product->access_ModelRoom_FindOne($valeur['id_salle']);
                    
                    $table .= '<tr>';

                    // Conversion des dates en dates Françaises et uniquement j/m/Y
                    $valeur['date_arrivee'] = $product->changeDateToFrenchFormat($valeur['date_arrivee']);
                    $valeur['date_depart'] = $product->changeDateToFrenchFormat($valeur['date_depart']);

                    $table .= '<td>' . $valeur['id_produit'] . '</td>';
                    $table .= '<td class="icon">' . $valeur['date_arrivee'] . '</td>';
                    $table .= '<td class="icon">' . $valeur['date_depart'] . '</td>';
                    $table .= '<td class="icon">' . $valeur['id_salle'] . '</td>';
                    $table .= '<td><img src="'.$room['photo'].'" width = 100 /></td>';
                    if($valeur['id_promo'] == 0){
                        $table .= '<td>Non</td>';
                    }else{
                        $table .= '<td>' . $valeur['id_promo'] . '</td>';
                    }
                    $table .= '<td>' . $valeur['prix'] .' €</td>';
                    $table .= '<td class="icon">' . $valeur['etat'] . '</td>';
                    $table .= '<td class="icon"><a href="index.php?controller=products&action=modifyProduct&id=' . $valeur['id_produit'] . '" title="Modifier"><i class="fa fa-edit fa-2x"></i></a></td>';
                    $table .= '<td class="icon"><a href="index.php?controller=products&action=deleteProduct&id=' . $valeur['id_produit'] . '" title="Supprimer"><i class="fa fa-trash-o fa-2x"></i></a></td>';
                }
            $table .= '</table>';
        }else{
            $msg .= 'Vous n\'avez pas le droit d\'accéder à cette page.<br/>';
        }     
                
        include "views/products/listProducts.php";
    }
    
    
    public function listProductsReservation(){
        
        $msg = "";
        $product = new Product;
        
        $list = $product->listProducts();
        
        rsort($list);
//        var_dump($list);
        $msg .= '<div class="row">';
            foreach($list as $prod){
                if($prod['etat']!=0 && $product->checkProductDate($prod['date_arrivee'])){
                    $prod['date_arrivee'] = $product->changeDateToFrenchFormat($prod['date_arrivee']);
                    $prod['date_depart'] = $product->changeDateToFrenchFormat($prod['date_depart']);

                    $room = $product->access_ModelRoom_FindOne($prod['id_salle']);
                    
                    $msg .= '<div class="col-sm-4 prods">';
                        $msg .= '<div class="blocProduit">';                            
                            $msg .= '<div class="prodImg">';                            
                                $msg .= '<a href="index.php?controller=products&action=showProduct&id='.$prod['id_produit'].'" title="Fiche détaillée"><img src="'.$room['photo'].'" /></a>';
                            $msg .= "</div>";
                            $msg .= '<div class="blocTexte">';
                                $msg .= '<h4>'.$room['titre'].'</h4>';
                                $msg .= '<p class="date">Disponible du '. $prod['date_arrivee'].'</p>';                                
                                $msg .= '<p class="date">Jusqu\'au '. $prod['date_depart'] . '</p>';                                
                                $msg .= '<h3>'. $room['ville'] . '</h3>';
                                $msg .= '<p>Capacité de '. $room['capacite'] . ' personnes.</p>';
                                $msg .= '<h3 class="prix">' . $prod['prix'] . "€</h3>"; 
                                $msg .= '<a class="btn btn-success btnAccueil" href="index.php?controller=products&action=showProduct&id=' . $prod['id_produit'] . '" title="Voir fiche détaillée">Voir la fiche détaillée</a>';
                                $msg .= "<br/>";
                                if($product->access_ModelMember_sessionExists()){
                                    $msg .= '<a class="btn btn-success btnAccueil" href="index.php?controller=orders&action=addToCart&id='.$prod['id_produit'].'" title="Ajouter au panier">Ajouter au panier</a>';
                                }else{
                                    $msg .= '<a class="btn btn-success btnAccueil" href="index.php?controller=members&action=connect" title="Connectez-vous pour l\'ajouter au panier">Connectez-vous pour l\'ajouter au panier</a>';
                                }                                
                            $msg .= '</div>';
                        $msg .= '</div>';
                    $msg .= '</div>';
                }
            }           
        $msg .= '</div>';  
        
        include 'views/products/listProductsReservation.php';
    }
  
    
    public function modifyProduct(){
        
        $msg = "";
        
        // id du produit à modifier
        $id = $_GET['id'];
                
        $product = new Product;
        
        if($product->access_ModelMember_sessionExists() || $product->access_ModelMember_userAdmin()){
            
            $product->setIdProduit($id);
        
            // $modProduct est remplie avec les infos du produit à modifier
            $modProduct = $product->findProduct($id);

            // Conversion des dates en dates Françaises et uniquement j/m/Y
            $modProduct['date_arrivee'] = $product->changeDateToFrenchFormat($modProduct['date_arrivee']);
            $modProduct['date_depart'] = $product->changeDateToFrenchFormat($modProduct['date_depart']);

            // Recupération de la liste des salles existantes
            $listRooms = $product->access_ModelRoom_List();

            // Recupération de la salle actuelle correspondant au produit
            $actualRoom = $product->access_ModelRoom_FindOne($modProduct['id_salle']);

            $optionsSalles = "";

            // Première option : la salle actuelle
            $optionsSalles .= '<option value="'. $actualRoom['id_salle'] .'" selected>'. $actualRoom['id_salle'] .' - '. $actualRoom['pays'] .' - '. $actualRoom['ville'] .' - '. $actualRoom['adresse'] .' - '. $actualRoom['titre'] .' - '. $actualRoom['capacite'] .' - '. $actualRoom['categorie'] .'</option>';

            // Autres options salles SANS la salle actuelle
            foreach($listRooms as $valeur){
                if($valeur['id_salle'] != $actualRoom['id_salle']){
                    $optionsSalles .= '<option value="'. $valeur['id_salle'] .'">'. $valeur['id_salle'] .' - '. $valeur['pays'] .' - '. $valeur['ville'] .' - '. $valeur['adresse'] .' - '. $valeur['titre'] .' - '. $valeur['capacite'] .' - '. $valeur['categorie'] .'</option>';
                }
            }

            // Création de la liste d'options déroulante pour les codes promo SANS le code actuel
            $listPromos = $product->access_ModelPromotion_List();
            $optionsRemise = "";

            //Recupération du code promo actuel correspondant au produit
            $actualPromo = $product->access_ModelPromotion_FindOne($modProduct['id_promo']);

            $optionsRemise .= '<option value="'. $actualPromo['id_promo'] .'" selected>'. $actualPromo['id_promo'] .' - '. $actualPromo['code_promo'] .' - '. $actualPromo['reduction'] .'</option>';

            foreach($listPromos as $promo){
                if($promo['id_promo'] != $actualPromo['id_promo']){
                    $optionsRemise .= '<option value="'. $promo['id_promo'] .'">'. $promo['id_promo'] .' - '. $promo['code_promo'] .' - '. $promo['reduction'] .'</option>';
                }
            }

            // Nouvelle entrée du produit en base si POST
            if($_POST){
                $id_produit = $modProduct['id_produit'];
                $id_salle = $_POST['salles'];
                $dateArrivee = htmlentities($_POST['dateArrivee'], ENT_QUOTES, "utf-8");
                $dateDepart = htmlentities($_POST['dateDepart'], ENT_QUOTES, "utf-8");
                $prix = htmlentities($_POST['prix'], ENT_QUOTES, "utf-8");
                $etat = htmlentities($_POST['etat'], ENT_QUOTES, "utf-8");
                $id_promo = $_POST['remise'];

                // Instancie un nouvel objet room
                $product = new Product;

                // Je remplis le nouvel objet avec les valeurs récupérées dans le formulaire
                $product->setIdProduit($id_produit);
                $product->setIdSalle($id_salle);
                $product->setDateArrivee($dateArrivee);
                $product->setDateDepart($dateDepart);
                $product->setPrix($prix);
                $product->setEtat($etat);
                $product->setIdPromo($id_promo);

                // Passage des dates entrées au format français en format US
                $product->changeDatesToUSFormat();

                // VERIFICATIONS
                $resultArray = $product->modifyCheckAll($id_salle, $dateArrivee, $dateDepart, $id_produit);
                
                $msgError = "";

                for($i = 0; $i < count($resultArray); $i++){
                   if(!$resultArray[$i]->getPassed()){
                       $msgError .= $resultArray[$i]->getErrorMessage();
                       $msgError .= "<br/>";
                   }
                }

                if($msgError == ""){
                    $product->replaceProduct();
                    header('location:index.php?controller=products&action=modifiedProduct');                    
                    
                }else{
                    $msg .= $msgError;
                }                    
            }
        }else{
            $msg .= 'Vous n\'avez pas le droit d\'accéder à cette page';
        }      
        
        include "views/products/modifyProduct.php";
    }
    
    public function modifiedProduct(){
        $msg = '';
        
        $product = new Product();
        
        $msg .= '<div class="alert alert-success">';
            $msg .= 'Produit modifié avec succès !';
        $msg .= '</div>';
        $msg .= '<a class="btn btn-success btnMargin" href="index.php?controller=products&action=listProducts" title="Retour aux produits">Retour à la liste des produits</a><br/>';
        $msg .= '<a class="btn btn-success btnMargin" href="index.php">Retour à l\'accueil</a>';
        
        include 'views/products/modifiedProduct.php';
    }
    
    public function deleteProduct(){
        
        $msg="";
        $id = $_GET['id'];
        
        $product = new Product;
        
        if($product->access_ModelMember_sessionExists() || $product->access_ModelMember_userAdmin()){
            $product->setIdProduit($id);        
            $delProduct = $product->deleteProduct();

            $msg .= 'Le produit a bien été supprimé !';
        }else{
            $msg .= 'Vous n\'avez pas le droit d\'accéder à cette page';
        }
               
        include "views/products/deleteProduct.php";
    }
    
    public function showProduct(){
        $msg = "";
                
        if(filter_has_var(INPUT_GET,'id')){
            $idprod = $_GET['id']; 
        }
        
        $product = new Product;      
        $resultatProduct = $product->findProduct($idprod);
                 
        include_once "controllers/comments.php";
        $comm = new Comments;        
                     
        if($product->access_ModelMember_userAdmin() || $product->access_ModelMember_sessionExists()){
            $id_membre = $_SESSION['user']['id_membre'];
            $id_salle = $resultatProduct['id_salle'];
            $comm->addComment($id_membre, $id_salle);
        }                
               
        $resultatRoom = $product->access_ModelRoom_FindOne($resultatProduct['id_salle']);
        $resultatPromo = $product->access_ModelPromotion_FindOne($resultatProduct['id_promo']);
        
        $dateArrivee = $product->changeDatetoFrenchFormat($resultatProduct['date_arrivee']);
        $dateDepart = $product->changeDatetoFrenchFormat($resultatProduct['date_depart']);
        
        $msg .= '<div class="row rowColor">';
            $msg .= '<div class="col-sm-10 rowColor2">';
                $msg .= '<div class="blocImage">';
                    $msg .= '<img class="img-responsive" src="' . $resultatRoom['photo'] . '" width= 600/>';
                $msg .= '</div>';
            
                $msg .= '<div class="infos ">';
                    $msg .= '<p class="detailTitre">' . $resultatRoom['titre'] . '</p>';
                    $msg .= '<p class="detailCat">Catégorie: ' . $resultatRoom['categorie'] . '</p>';
                    $msg .= '<p class="detailCat">Max: ' . $resultatRoom['capacite'] . ' personnes</p>';                
                    $msg .= '<p class="detailAdresse">' . $resultatRoom['adresse'] . '</p>';
                    $msg .= '<p class="detailAdresse">'. $resultatRoom['cp'] . ' ' . $resultatRoom['ville'] .'</p>';
                    $msg .= '<p class="detailDate">Date d\'arrivée: '  . $dateArrivee . '</p>';
                    $msg .= '<p class="detailDate">Date de départ: ' . $dateDepart . '</p>';
                    $msg .= '<p class="detailPrix">' . $resultatProduct['prix'] . ' €</p>';

                    // SI LE CODE PROMO EXISTE
                    if(isset($resultatPromo['code_promo']) && $resultatPromo['code_promo'] != ''){
                        $msg .= '<p class="detailPromo">*Code promo: ' . $resultatPromo['code_promo'] . ' pour bénéficier d\'une réduction de '  . $resultatPromo['reduction'] . ' €</p>';
                    }
                    $msg .= '<div class="clearfix"></div>';
                $msg .= '</div>';
            $msg .= '</div>';
            
            $msg .= '<div class="col-sm-2 optionsButton">';
                if($product->access_ModelMember_userAdmin() || $product->access_ModelMember_sessionExists()){
                    $msg .= '<a class="btn btn-success" href="index.php?controller=orders&action=addToCart&id='. $resultatProduct['id_produit'] .'" title="Ajouter">Ajouter au panier</a>';                          
                }else{
                    $msg .= '<a class="btn btn-success btnDetail" href="index.php?controller=members&action=connect" title="Se connecter">Se connecter</a>';
                    $msg .= "<br/>";
                    $msg .= '<a class="btn btn-success btnDetail" href="index.php?controller=members&action=add_member" title="Inscription">Inscription</a>';                    
                }
            $msg .= '</div>';
        $msg .= '</div>';
        
        
        // COMMENTAIRES
        
        $msg .= '<div class="row row2">';
            $msg .= '<div class="col-sm-4 detailAvis">';
                // AFFICHAGE DES AVIS SUR LA SALLE        
                $comments = $product->access_ModelComment_roomComments($resultatProduct['id_salle']);

                if(isset($comments) && !empty($comments)){ 
                    
                    $msg .= '<p class="avisTitre">Avis sur cette salle: </p>';
                    foreach($comments as $comm){
                        $member = $product->access_ModelMember_retrieveMember($comm['id_membre']);                          
                        $msg .= '<div class="commentaire">';
                            $msg .= '<p class="avisMembre">Par '. $member['prenom'] .' '. $member['nom'] . ' le '. $comm['date'] .'</p>';
                            $msg .= '<p class="avisTexte">' . $comm['commentaire'] . '</p>';
                            $msg .= '<p class="avisNote">Note: ' . $comm['note'] . '/10  </p>'; 
                        $msg .= '</div>';
                    }
                    
                }else{
                    $msg .= '<p>Cette salle n\'a aucun commentaires pour le moment.</p>';
                }
            $msg .= '</div>';
            $msg .= '<div class="col-sm-8">';
                $msg .= '<div class="row rowDesc">';            
                    $msg .= '<p>' . $resultatRoom['description'] . '</p>';                    
                $msg .= '</div>';
                $msg .= '<div class="row rowMap">';
                    $msg .= '<input type="hidden" id="address" value="'.$resultatRoom['adresse'].' '. $resultatRoom['cp'] . ' ' . $resultatRoom['ville'] .'"/>';
                    $msg .= '<div id="map-canvas" class="pull-right"></div>';
                $msg .= '</div>';
            $msg .= '</div>';            
        $msg .= '</div>';
        
        $msg .= '<div class="row rowAvisForm">';
            // Vérification si l'utilisateur connecté a déjà posté un avis sur cette salle
            if($product->access_ModelMember_userAdmin() || $product->access_ModelMember_sessionExists()){
                $verif = $product->checkMemberComments($_SESSION['user']['id_membre'], $resultatProduct['id_salle']);
            }        

            
            if(empty($verif) || $verif == NULL){
                //Affichage du formulaire pour entrer un commentaire            
                if($product->access_ModelMember_userAdmin() || $product->access_ModelMember_sessionExists()){
                $msg .= '<form method="post" class="form-horizontal">'; 
                        $msg .= '<div class="form-group">';
                            $msg .= '<label class="label-control col-sm-4" for="comment">Ajouter un commentaire</label>';
                            $msg .= '<div class="col-sm-8">';
                                $msg .= '<textarea name="comment" id="comment">Votre commentaire...</textarea>';
                            $msg .= '</div>';
                        $msg .= '</div>';
                        
                        $msg .= '<div class="form-group">';
                            $msg .= '<label class="label-control col-sm-4" for="note">Note/10</label>';
                            $msg .= '<div class="col-sm-8">';
                                $msg .= '<select class="form-control" name="note" id="note">';
                                    $msg .= '<option value="0">0</option>';
                                    $msg .= '<option value="1">1</option>';
                                    $msg .= '<option value="2">2</option>';
                                    $msg .= '<option value="3">3</option>';
                                    $msg .= '<option value="4">4</option>';
                                    $msg .= '<option value="5" selected>5</option>';                    
                                    $msg .= '<option value="6">6</option>';
                                    $msg .= '<option value="7">7</option>';
                                    $msg .= '<option value="8">8</option>';
                                    $msg .= '<option value="9">9</option>';
                                    $msg .= '<option value="10">10</option>';
                                $msg .= '</select><br/>'; 
                            $msg .= '</div>';
                        $msg .= '</div>';
                        
                        $msg .= '<div class="form-group">';
                            $msg .= '<div class="col-sm-12">';
                                $msg .= '<input class="btn btn-success pull-right" type="submit" name="enregistrer" value="Enregistrer">';
                            $msg .= '</div>';
                        $msg .= '</div>';
                $msg .= '</form>';
                }else{
                    $msg .= 'Vous devez vous connecter pour pouvoir poster un commentaire';
                }
            }else{
                $msg .= '<div class="panel panel-default">';
                    $msg .= '<div class="panel-body">';
                        $msg .= 'Merci pour votre commentaire.';
                    $msg .= '</div>';
                $msg .= '</div>';
            }
        $msg .= '</div>';  
        
        // AUTRES SUGGESTIONS
        
        // Récupérer localisation de la salle et date du produit
//        var_dump($resultatRoom['ville']);
//        var_dump($resultatProduct['date_arrivee']);
                
        // Recupérer les produits dont la ville est identique et la date d'arrivée >= à celle du produit déjà choisi
        
        $rooms = $product->access_ModelRoom_findByCity($resultatRoom['ville']);
        
        $suggest = $product->findSuggestions($resultatProduct['date_arrivee']);
//        var_dump($rooms);
//        var_dump($suggest);
        
        $msg .= '<div class="row">'; 
            $msg .= '<div id="suggestTitle">';
                $msg .= '<h3>Autres suggestions</h3>';
            $msg .= '</div>';
            $msg .= '<div class="owl-carousel">';                   
                foreach($suggest as $suggestion){                
                    foreach($rooms as $room){
                        if($suggestion['id_salle'] == $room['id_salle'] && $suggestion['id_produit'] != $resultatProduct['id_produit']){
                            
                            $date = new DateTime($suggestion['date_arrivee']);
                            $suggestion['date_arrivee'] = $date->format('d-m-Y');
                            $date = new DateTime($suggestion['date_depart']);
                            $suggestion['date_depart'] = $date->format('d-m-Y');
                            
                            $msg .= '<div>';
                                $msg .= '<a href="index.php?controller=products&action=showProduct&id='.$suggestion['id_produit'].'"><img src="' . $room['photo'] . '" width=360 /></a><br/>';
                                $msg .= $room['titre'].'<br/>';                                
                                $msg .= $suggestion['prix']. '€ Pour ' .$room['capacite'].' personnes<br/>';
                                $msg .= 'Du '.$suggestion['date_arrivee'].' au '.$suggestion['date_depart'].'<br/>';                                
                                $msg .= $room['ville'].'<br/>';                                
                            $msg .= '</div>';
                        }
                    }                
                }           
            $msg .= '</div>';
        $msg .= '</div>';
               
        
        include "views/products/showProduct.php";
    }
    
    public function searchProducts(){
        $msg = ""; 
        
        // Extraction de année et mois actuels
        $today = new DateTime();
        $year = $today->format('Y');
        $month = $today->format('m');
        $day = $today->format('d');
                
        if($_POST){
            $selDay = filter_input(INPUT_POST, 'day');
            $selMonth = filter_input(INPUT_POST, 'month');
            $selYear = filter_input(INPUT_POST, 'year');
            $selKeyword = filter_input(INPUT_POST, 'keyword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $dateRecup = $selYear .'-'.$selMonth.'-'.$selDay;            
//            $selDate = new DateTime($dateRecup);            
                                    
            $product = new Product;
            
            // Si l'année est l'année en cours, empêcher la sélection des mois déjà passés
            
            // Si aucun mot clé n'est entré par l'utilisateur
            if(empty($selKeyword) || $selKeyword == ''){
                $searchRes = $product->listProductsByDateSup($dateRecup);
            
                foreach($searchRes as $prod){
                    if($prod['etat']!=0 && $product->checkProductDate($prod['date_arrivee'])){
                        $prod['date_arrivee'] = $product->changeDateToFrenchFormat($prod['date_arrivee']);
                        $prod['date_depart'] = $product->changeDateToFrenchFormat($prod['date_depart']);

                        $room = $product->access_ModelRoom_FindOne($prod['id_salle']);
                        
                        $msg .= '<div class="row rowSearch">';
                            $msg .= '<div class="picSearch">';
                                $msg .= '<a href="index.php?controller=products&action=showProduct&id=' . $prod['id_produit'] . '" title="Voir fiche détaillée"><img src="' . $room['photo'] . '" height=150 /></a>';
                            $msg .= '</div>';
                            $msg .= '<div class="infoSearch">';
                                $msg .= '<h3 class="titleSearch">'.$room['titre'] .'</h3>';                            
                                $msg .= '<p class="dateSearch">Du '. $prod['date_arrivee'].' au '.$prod['date_depart'] . '</p>';
                                $msg .= '<p class="detailSearch">'.$prod['prix'].' € pour '. $room['capacite'] . ' personnes</p>';
                            $msg .= '</div>';
                            $msg .= '<div class="btnSearchDiv pull-right">';
                                $msg .= '<a class="btn btn-success btnSearch pull-right" href="index.php?controller=products&action=showProduct&id=' . $prod['id_produit'] . '" title="Voir fiche détaillée">Voir la fiche détaillée</a>';
                                $msg .= "<br/>";
                                if($product->access_ModelMember_sessionExists()){
                                    $msg .= '<a class="btn btn-success btnSearch pull-right" href="index.php?controller=orders&action=addToCart&id='.$prod['id_produit'].'" title="Ajouter au panier">Ajouter au panier</a>';
                                }else{
                                    $msg .= '<a class="btn btn-success btnSearch pull-right" href="index.php?controller=members&action=connect" title="Connectez-vous pour l\'ajouter au panier">Connectez-vous pour l\'ajouter au panier</a>';
                                }                                
                            $msg .= '</div>';
                        $msg .= '</div>';
                    }
                }
            }else{
                $searchRes = $product->listProductsByDateSup($dateRecup);
                $counter = 0;
                
                foreach($searchRes as $prod){
                    if($prod['etat']!=0 && $product->checkProductDate($prod['date_arrivee'])){
                        $prod['date_arrivee'] = $product->changeDateToFrenchFormat($prod['date_arrivee']);
                        $prod['date_depart'] = $product->changeDateToFrenchFormat($prod['date_depart']);

                        $room = $product->access_ModelRoom_FindOne($prod['id_salle']);
                        
                        // Dans la ville et le mot clé, suppression 1 des majuscules 2 des espaces
                        $ville = str_replace(' ', '', strtolower($room['ville']));                                                
                        $key = str_replace(' ', '', strtolower($selKeyword));
                        
                        $searchKey = strpos( $ville, $key);
                        
                        if($searchKey !== false){
                            $msg .= '<div class="row rowSearch">';
                                $msg .= '<div class="picSearch">';
                                    $msg .= '<a href="index.php?controller=products&action=showProduct&id=' . $prod['id_produit'] . '" title="Voir fiche détaillée"><img src="' . $room['photo'] . '" height=150 /></a>';
                                $msg .= '</div>';
                                $msg .= '<div class="infoSearch">';
                                    $msg .= '<h3 class="titleSearch">'.$room['titre'] .'</h3>';                                  
                                    $msg .= '<p class="dateSearch">Du '. $prod['date_arrivee'].' au '.$prod['date_depart'] . '</p>';
                                    $msg .= '<p class="detailSearch">'.$prod['prix'].' € pour '. $room['capacite'] . ' personnes</p>';
                                $msg .= '</div>';
                                $msg .= '<div class="btnSearchDiv pull-right">';
                                    $msg .= '<a class="btn btn-success btnSearch pull-right" href="index.php?controller=products&action=showProduct&id=' . $prod['id_produit'] . '" title="Voir fiche détaillée">Voir la fiche détaillée</a>';
                                    $msg .= "<br/>";
                                    if($product->access_ModelMember_sessionExists()){
                                        $msg .= '<a class="btn btn-success btnSearch pull-right" href="index.php?controller=orders&action=addToCart&id='.$prod['id_produit'].'" title="Ajouter au panier">Ajouter au panier</a>';
                                    }else{
                                        $msg .= '<a class="btn btn-success btnSearch pull-right" href="index.php?controller=members&action=connect" title="Connectez-vous pour l\'ajouter au panier">Connectez-vous pour l\'ajouter au panier</a>';
                                    }                                
                                $msg .= '</div>';
                            $msg .= '</div>';
                            
                            $counter++;
                        }                                          
                    }
                }
                if($counter == 0){
                    $msg .= 'Aucun résultat trouvé';
                }                
            }                        
        }
        
        $msg .= '';
                
        include "views/products/searchProducts.php";
    }
    
}

