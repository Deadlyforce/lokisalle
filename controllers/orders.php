<?php
include "models/order.php";

class Orders{
    
    public function showCart(){
        $msg = ""; 
        
        $order = new Order;
//        var_dump($_SESSION);
        if($order->access_ModelMember_sessionExists() || $order->access_ModelMember_userAdmin()){
            
            // RECUPERATION DU FORMULAIRE ET PAIEMENT *****************************************************************
            if(filter_has_var(INPUT_POST, 'payer')){ 
                
                $codePromo = filter_input(INPUT_POST, 'codePromo', FILTER_SANITIZE_STRING);

                $memberID = $_SESSION['user']['id_membre'];
                $totalTTC = $_SESSION['montantFinal'];
                
                // SI PROMOTION ON FAIT LA SOUSTRACTION DU TOTAL ******************************************************
                if(!empty($codePromo)){
                    $listPromos = $order->access_ModelPromo_listPromo();
                    foreach($listPromos as $promos){
                        if($codePromo == $promos['code_promo']){
                            $totalTTC = $totalTTC - $promos['reduction'];
                            $_SESSION['cart']['reduction'] = $promos['reduction'];
                        }
                    }
                }

                $order->setMontant($totalTTC);
                $order->setIdMembre($memberID); 

                // VERIFICATIONS CGV : 1) cgv doit être coché 2) Si code promo, il est soustrait du montant final
                if(isset($_POST['cgv'])){
                    $cgv = 1;
                }else{
                    $cgv = 0;
                }

                $resultArray = $order->checkCartConstraints($cgv);

                $msgError = "";                    
                for($i = 0; $i < count($resultArray); $i++){
                   if(!$resultArray[$i]->getPassed()){
                       $msgError .= $resultArray[$i]->getErrorMessage();
                       $msgError .= "<br/>";
                   }
                }
                
                // SI PAS D'ERREURS CGV
                if($msgError == ""){
                    // ENTREE EN BASE DE LA COMMANDE
                    $order->addOrder();

                    $newOrder = $order->selectOrder($_SESSION['user']['id_membre']);
                    $id_commande = $newOrder['id_commande'];
                    $order->setIdCommande($id_commande);

                    // Entrée des détails de commande en base, à savoir tous les produits du panier
                    for($i=0; $i<count($_SESSION['cart']['id_produit']); $i++){
                        $order->setIdProduit($_SESSION['cart']['id_produit'][$i]);
                        $order->addOrderDetails();

                        // Retrait des produits du catalogue -> passage en statut "0"
                        $order->access_ModelProduct_changeStatusToUnavailableProduct($_SESSION['cart']['id_produit'][$i]);

                    }
                    
//                    var_dump($_SESSION);
                    
                    // Envoi de l'email récapitulatif au client ***********************************************************
                    $to = $_SESSION['user']['email'];
                    $subject = 'Confirmation de commande';
                    $message = '';
                    $message .= '<p>Merci, votre numéro de commande est le :' . $id_commande . '</p>';
                    $message .= '<p>Récapitulatif de votre commande: </p>';
                    $message .= $this->orderSummary();

                    $type = 'HTML'; // or Plain
                    $charset = 'utf-8';
                    $mail = 'no-reply@' . str_replace('www.', '', $_SERVER['SERVER_NAME']);
                    $uniqid = md5(uniqid(time())); // Génère un identifiant unique basé sur l'heure courante

                    $headers  = 'From: '.$mail."\n";
                    $headers .= 'Reply-to: '.$mail."\n";
                    $headers .= 'Return-Path: '.$mail."\n";
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

                    // Vidage du panier et de la session après transaction
                    if(isset($_SESSION['cart'])){
                        unset($_SESSION['cart']);
                    }        

//                    $msg .= 'Votre panier est vide.<br/>';                    
                    $msg .= "Transaction effectuée avec succès, merci pour votre achat !";
                }else{
                    $msg .= $msgError;
                }
            }           
        
            // AFFICHAGE DU PANIER ********************************************************************************
            if(isset($_SESSION['cart']) && !empty($_SESSION['cart']['id_produit'])){      

            $msg .= '<table id="cartTable" class="table table-hover">';       
                $msg .= '<tr>';
                    $msg .= '<th>Produit</th>';
                    $msg .= '<th>Salle</th>';
                    $msg .= '<th>Photo</th>';
                    $msg .= '<th>Date d\'arrivee</th>';
                    $msg .= '<th>date de départ</th>';
                    $msg .= '<th>Prix HT</th>';
                    $msg .= '<th>TVA/20%</th>';
                    $msg .= '<th>Prix TTC (€)</th>';
                    $msg .= '<th>Action</th>';
                $msg .= '</tr>';
//var_dump($_SESSION['cart']);
                for($i=0; $i<count($_SESSION['cart']['id_produit']); $i++){
                    // Conversion des dates en dates Françaises et uniquement j/m/Y
                    $dateArrivee = new DateTime(''. $_SESSION['cart']['date_arrivee'][$i] .'');    
                    $dateDepart = new DateTime(''. $_SESSION['cart']['date_depart'][$i] .'');         
                    $_SESSION['cart']['date_arrivee'][$i] = $dateArrivee->format('d-m-Y');
                    $_SESSION['cart']['date_depart'][$i] = $dateDepart->format('d-m-Y');    

                    $msg .= '<tr>';
                        $msg .= '<td>'. $_SESSION['cart']['id_produit'][$i] .'</td>';
                        $msg .= '<td>'. $_SESSION['cart']['titre'][$i] .'</td>';                    
                        $msg .= '<td><img src="'. $_SESSION['cart']['photo'][$i] .'" width=100 /></td>';
                        $msg .= '<td>'. $_SESSION['cart']['date_arrivee'][$i] .'</td>';
                        $msg .= '<td>'. $_SESSION['cart']['date_depart'][$i] .'</td>';
                        $msg .= '<td>'. $_SESSION['cart']['prix'][$i] .'</td>';
                        $msg .= '<td>'. (($_SESSION['cart']['prix'][$i]*1.2)-$_SESSION['cart']['prix'][$i]) .'</td>';
                        $msg .= '<td>'. $_SESSION['cart']['prix'][$i]*1.2 .'</td>';
                        $msg .= '<td><a href="index.php?controller=orders&action=deleteFromCart&id=' . $_SESSION['cart']['id_produit'][$i] . '" title="Supprimer"><i class="fa fa-trash-o fa-2x"></i></a></td>';
                    $msg .= '</tr>';
                }

                $total = "";
                $totalTVA = "";
                $totalTTC = "";

                for($i=0; $i<count($_SESSION['cart']['prix']) ;$i++){                
                    $total += $_SESSION['cart']['prix'][$i];
                }
                for($j=0; $j<count($_SESSION['cart']['prix']) ;$j++){                
                    $totalTVA += (($_SESSION['cart']['prix'][$j]*1.2)-$_SESSION['cart']['prix'][$j]);
                }
                for($k=0; $k<count($_SESSION['cart']['prix']) ;$k++){                
                    $totalTTC += $_SESSION['cart']['prix'][$k]*1.2;
                }
                
                if(isset($_SESSION['cart']['reduction']) && $_SESSION['cart']['reduction'] != ''){
                    $_SESSION['montantFinal'] = $totalTTC - $_SESSION['cart']['reduction'];
                }else{
                    $_SESSION['montantFinal'] = $totalTTC;
                }

                $msg .= '<tr>';
                    $msg .= '<td></td>';
                    $msg .= '<td></td>';
                    $msg .= '<td></td>';
                    $msg .= '<td></td>';
                    $msg .= '<td>Montant: </td>';
                    $msg .= '<td>'. $total .'</td>';
                    $msg .= '<td>'. $totalTVA .'</td>';
                    $msg .= '<td>'. $totalTTC .'</td>';
                    $msg .= '<td><a href="index.php?controller=orders&action=emptyCart" title="Vider le panier">Vider</a></td>';
                $msg .= '</tr>';            
            $msg .= '</table>';

            $msg .= '<br/>';

            $msg .= '<form method="post" class="form-horizontal" role="form">';
                $msg .= '<div class="form-group">';
                    $msg .= '<div class="col-sm-6"></div>';
                    $msg .= '<label class="control-label col-sm-4" for="cgv">J\'accepte les conditions générales de vente (<a href="index.php?controller=orders&action=cgv">voir</a>) </label>';
                    $msg .= '<div class="col-sm-2">';
                        $msg .= '<input class="form-control" type="checkbox" id="cgv" name="cgv" value="1" />';
                    $msg .= '</div>';                
                $msg .= '</div>';
                
                $msg .= '<div class="form-group">';
                    $msg .= '<div class="col-sm-6"></div>';
                    $msg .= '<label class="control-label col-sm-4" for="codePromo">Utiliser un code promo</label>';
                    $msg .= '<div class="col-sm-2">';
                        $msg .= '<input class="form-control" type="text" id="codePromo" name="codePromo" />';
                    $msg .= '</div>';                    
                $msg .= '</div>';
                
                $msg .= '<div class="form-group">';
                    $msg .= '<div class="col-sm-4"></div>';
                    $msg .= '<div class="col-sm-8">';
                        $msg .= '<input class="btn btn-success pull-right" type="submit" id="payer" name="payer" value="Régler ma commande" />';
                    $msg .= '</div>';                    
                $msg .= '</div>';
            $msg .= '</form>';

            }else{
                $msg .= '<div class="alert alert-warning emptyCart">';
                    $msg .= 'Votre panier est vide';
                $msg .= '</div>';
            }

            $msg .= '<div class="panel panel-default">';
                $msg .= '<div class="panel-body">';
                    $msg .= 'Tous nos articles sont calculés avec le taux de TVA à 20%<br/>';
                    $msg .= 'Règlement: par chèque uniquement.<br/>';
                    $msg .= 'Nous attendons votre règlement par chèque à l\'adresse suivante: <br/>';
                    $msg .= 'Ma boutique - 1 rue Boswellia, 75000 Paris, France';
                $msg .= '</div>';
            $msg .= '</div>';
            
        }else{
            $msg .= 'Vous n\'avez pas le droit d\'accéder à cette page';
        }
        
        include 'views/orders/showCart.php';
    }
    
    private function orderSummary(){
        if(isset($_SESSION['cart']) && !empty($_SESSION['cart']['id_produit'])){
            $table = '';
            $table .= '<table style="border-collapse: collapse;">';       
                $table .= '<tr>';
                    $table .= '<th style="border-bottom: 1px solid #aaa;padding: 5px 15px 5px 15px;">salle</th>';
                    $table .= '<th style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;">Photo</th>';                    
                    $table .= '<th style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;">date_arrivee</th>';
                    $table .= '<th style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;">date_depart</th>';
                    $table .= '<th style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;">Prix HT</th>';
                    $table .= '<th style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;">TVA/20%</th>';
                    $table .= '<th style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;">Prix TTC (€)</th>';                
                $table .= '</tr>';

                for($i=0; $i<count($_SESSION['cart']['id_produit']); $i++){

                    // Conversion des dates en dates Françaises et uniquement j/m/Y
                    $dateArrivee = new DateTime(''. $_SESSION['cart']['date_arrivee'][$i] .'');    
                    $dateDepart = new DateTime(''. $_SESSION['cart']['date_depart'][$i] .'');         
                    $_SESSION['cart']['date_arrivee'][$i] = $dateArrivee->format('d-m-Y');
                    $_SESSION['cart']['date_depart'][$i] = $dateDepart->format('d-m-Y');    

                    $table .= '<tr>';
                        $table .= '<td style="border-bottom: 1px solid #aaa;padding: 5px 15px 5px 15px;">'. $_SESSION['cart']['titre'][$i] .'</td>';
                        $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;"><img src="http://www.lokisalle.normanwebdev.com/'. $_SESSION['cart']['photo'][$i] .'" width=100 /></td>';
                        $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;">'. $_SESSION['cart']['date_arrivee'][$i] .'</td>';
                        $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;">'. $_SESSION['cart']['date_depart'][$i] .'</td>';
                        $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;">'. $_SESSION['cart']['prix'][$i] .'</td>';
                        $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;">'. (($_SESSION['cart']['prix'][$i]*1.2)-$_SESSION['cart']['prix'][$i]) .'</td>';
                        $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;">'. $_SESSION['cart']['prix'][$i]*1.2 .'</td>';                
                    $table .= '</tr>';
                }

                $total = "";
                $totalTVA = "";
                $totalTTC = "";

                for($i=0; $i<count($_SESSION['cart']['prix']) ;$i++){                
                    $total += $_SESSION['cart']['prix'][$i];
                }
                for($j=0; $j<count($_SESSION['cart']['prix']) ;$j++){                
                    $totalTVA += (($_SESSION['cart']['prix'][$j]*1.2)-$_SESSION['cart']['prix'][$j]);
                }
                for($k=0; $k<count($_SESSION['cart']['prix']) ;$k++){                
                    $totalTTC += $_SESSION['cart']['prix'][$k]*1.2;
                }            
                
//                if(isset($_SESSION['cart']['reduction']) && $_SESSION['cart']['reduction'] != ''){
//                    $_SESSION['montantFinal'] = $totalTTC - $_SESSION['cart']['reduction'];
//                }else{
//                    $_SESSION['montantFinal'] = $totalTTC;
//                }         
                
                $table .= '<tr>';
                    $table .= '<td style="border-bottom: 1px solid #aaa;padding: 5px 15px 5px 15px;"></td>';
                    $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;"></td>';
                    $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;"></td>';
                    $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;">Montant: </td>';
                    $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;">'. $total .'</td>';
                    $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;">'. $totalTVA .'</td>';
                    $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;">'. $totalTTC .'</td>';                
                $table .= '</tr>';            
                $table .= '<tr>';
                    $table .= '<td style="border-bottom: 1px solid #aaa;padding: 5px 15px 5px 15px;"></td>';
                    $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;"></td>';
                    $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;"></td>';
                    $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;"></td>';
                    $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;"></td>';
                    $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;">Reduction</td>';
                    $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;">'. $_SESSION['cart']['reduction'] .'</td>';                
                $table .= '</tr>';            
                $table .= '<tr>';
                    $table .= '<td style="border-bottom: 1px solid #aaa;padding: 5px 15px 5px 15px;"></td>';
                    $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;"></td>';
                    $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;"></td>';
                    $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;"></td>';
                    $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;"></td>';
                    $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;">Total</td>';
                    $table .= '<td style="border-bottom: 1px solid #aaa; text-align: center;padding: 5px 15px 5px 15px;">'. ($totalTTC - $_SESSION['cart']['reduction']) .'</td>';                
                $table .= '</tr>';            
            $table .= '</table>';
            
            return $table;
        }
    }
    
    public function deleteFromCart(){
        
        $msg= "";
        $id = $_GET['id'];
        
        $position = array_search($id, $_SESSION['cart']['id_produit']);
                            
        if(isset($position)){
                array_splice($_SESSION['cart']['id_produit'], $position, 1);
                array_splice($_SESSION['cart']['titre'], $position, 1);
                array_splice($_SESSION['cart']['date_arrivee'], $position, 1);
                array_splice($_SESSION['cart']['date_depart'], $position, 1);
                array_splice($_SESSION['cart']['prix'], $position, 1);           
                array_splice($_SESSION['cart']['photo'], $position, 1);           
        }
        
               
        $msg .= '<div class="alert alert-success">Produit supprimé</div>';
        
        include 'views/orders/deleteFromCart.php';
    }
    
    public function addToCart(){
        
        $id = $_GET['id'];
        $msg = "";
        $msgError = "";
        
        $order = new Order;
        if($order->access_ModelMember_sessionExists()){        
            //Si une session 'Cart' existe déjà, checker si le produit est déjà dans la SESSION
            if(isset($_SESSION['cart'])){
                $resultArray = $order->checkAllConstraint($id);

                // Je récupère la valeur de $passed pour chaque entrée du tableau (true or false)
                // Si false, je récupère également le message d'erreur correspondant
                for($i = 0; $i < count($resultArray); $i++){
                    if(!$resultArray[$i]->getPassed()){
                        $msgError .= $resultArray[$i]->getErrorMessage();
                        $msgError .= "<br/>";
                    }
                }
            }else{
                $order->createCart();
            }


    //         Si pas d'erreur, je remplis ma variable session avec le produit
            if($msgError == ""){
                //Recup du produit et de la salle en base 
                $product = $order->access_ModelProduct_findProduct($id);
                $room = $order->access_ModelRoom_findRoom($product['id_salle']);

                $_SESSION['cart']['id_produit'][] = $product['id_produit'];
                $_SESSION['cart']['titre'][] = $room['titre'];
                $_SESSION['cart']['date_arrivee'][] = $product['date_arrivee'];
                $_SESSION['cart']['date_depart'][] = $product['date_depart'];
                $_SESSION['cart']['prix'][] = $product['prix']; 
                
                $_SESSION['cart']['photo'][] = $room['photo'];

                $msg = '<div class="alert alert-success">Produit ajouté au panier</div>';
            }else{
                $msg .= $msgError;
            }
        }else{            
            $msg .= 'Vous n\'avez pas le droit d\'accéder à cette page';        
        }
                
        include 'views/orders/addToCart.php';        
    }
    
    public function emptyCart(){
        $msg = "";        
        
        if(isset($_SESSION['cart'])){
            unset($_SESSION['cart']);
        }        
        
        $msg .= '<div class="alert alert-success">Votre panier est vide !</div>';
        
        include 'views/orders/emptyCart.php';
    }
    
    public function cgv(){
        $msg = "";
        
                
        include 'views/orders/cgv.php';
    }
    
    public function mentions(){
        
        $msg = '';
        
        $msg .= '<div class="alert alert-danger">ATTENTION : LOKISALLE EST UN SITE FICTIF.<br/> Les information sur les salles, les tarifs, et la réservation des salles sont fictifs.</div>';
        $msg .= '<p>Il a été réalisé en Mai 2014 dans le cadre de l\'atelier PHP prévu lors de la formation de développeur web orienté objet de l’institut IFOCOP pour la session DIWOO 06.</p>';
        $msg .= '<p>Site réalisé par : Norman Rosenstech</p><br/>';
        $msg .= '<p>Article 1. Propriété intellectuelle :<br/><br/>
L’ensemble de ce site relève des législations Françaises et Internationales sur les droits d’auteur et la propriété intellectuelle.
Tous les droits de reproduction sont réservés pour les textes et les photographies de ce site.
La reproduction de tout ou partie de ce site sur un support électronique ou autre quel qu’il soit, est formellement interdite sauf autorisation écrite de l’auteur, conformément à l’article L 122-4 du Code de la Propriété intellectuelle. Crédit photos : Tous droits réservés.</p>';
        $msg .= '<p>Article 2. Conditions de modération des commentaires :<br><br>
                    Les commentaires sur les salles sont modérés a posteriori.<br/>
                    Le non respect de l’une des règles suivantes entraîne la suppression du commentaire concerné :<br/>
                    >> Commentaires diffamatoires, racistes, pornographiques, pédophiles, incitant à délit, crimes ou suicides,<br/>
                    >> Commentaires reproduisant une correspondance privée sans l’accord des personnes concernées,<br/>
                    >> Commentaires agressifs ou vulgaires,<br/>
                    >> Commentaires visant uniquement à mettre en ligne un lien vers un site extérieur (spam de commentaires ou de trackback/pingback).</p>';
            
        
        include 'views/orders/mentions.php';
    }
    
    public function manageOrders(){
        $msg='';
        
        // Choix de la colonne
        if(isset($_GET['sort']) && !empty($_GET['sort'])){
            $sort = $_GET['sort'];
        }else{
            $sort = 'montant';
        }
        
        // Condition pour l'ordre des colonnes
        if(isset($_GET['switch']) && !empty($_GET['switch'])){
            $switch = $_GET['switch'];
        }else{
            $switch = 'SORT_ASC';
        }
        
        $orders = new Order();
        
        if($orders->access_ModelMember_sessionExists() || $orders->access_ModelMember_userAdmin()){

            $selection = $orders->selectAll();

            $CA = '';
            
            // MODIFICATION DU TABLEAU POUR array_multisort (cf doc)
            foreach ($selection as $key => $row) {
                $id_commande[$key]  = $row['id_commande'];
                $montant[$key]  = $row['montant'];
                $id_membre[$key] = $row['id_membre'];
                $date[$key] = $row['date'];                
            }
            
            // TRI
            if($switch == 'SORT_ASC'){ 
                if($sort == 'montant'){
                    array_multisort($montant, SORT_ASC, $selection);
                    $switch = 'SORT_DESC';
//                }elseif($sort == 'date_depart'){
//                    array_multisort($date_depart, SORT_ASC, $list);
//                    $switch = 'SORT_DESC';
//                }elseif($sort == 'prix'){
//                    array_multisort($prix, SORT_ASC, $list);
//                    $switch = 'SORT_DESC';
                }
            }else{
                if($sort == 'montant'){
                    array_multisort($montant, SORT_DESC, $selection);
                    $switch = 'SORT_ASC';
//                }elseif($sort == 'date_depart'){
//                    array_multisort($date_depart, SORT_DESC, $list);
//                    $switch = 'SORT_ASC';
//                }elseif($sort == 'prix'){
//                    array_multisort($prix, SORT_DESC, $list);
//                    $switch = 'SORT_ASC';
                }
            }

            // FIN DES CONDITIONS SUR $order

            $msg .= '<table class="table table-hover">';
            $msg .= '<tr>';
                $msg .= '<th>id_commande</th>';
                $msg .= '<th>id_membre</th>';
                $msg .= '<th><a href="index.php?controller=orders&action=manageOrders&sort=montant&switch='.$switch.'" title="Montant">montant</a></th>';
            $msg .= '</tr>';        
            foreach($selection as $valeur){            
                $msg .= '<tr>'; 
                    $msg .= '<td><a href="index.php?controller=orders&action=manageOrders&idcom='.$valeur['id_commande'].'&col=default" title="ID Commande">'. $valeur['id_commande'] .'</a></td>'; 
                    $msg .= '<td>'. $valeur['id_membre'] .'</td>'; 
                    $msg .= '<td>'. $valeur['montant'] .'</td>'; 
                $msg .= '</tr>';
                $CA += $valeur['montant'];
            }
            $msg .= '</table>';        

            $msg .= 'Le chiffre d\'affaires (CA) de notre société est de: ' . $CA . '€<br/>';
            $msg .= '<br/>';

            // AFFICHAGE DU DETAIL COMMANDE SI CLIC SUR UN id_commande
            $idcom = filter_input(INPUT_GET, 'idcom');

            if(isset($idcom)){          

                $comm = $orders->findOrder($idcom);
                $member = $orders->access_ModelMember_findMember($comm['id_membre']);
                
                
                // CAS OU LE MEMBRE A ETE EFFACÉ = CONSERVATION COMMANDE MAIS DONNER UN NOM DE MEMBRE
                if($member == '' || !isset($member)){
                    $member['pseudo'] = 'Membre supprimé';
                }
                
                $resultDetail = $orders->selectOrderDetails($idcom);
                
                // AFFICHAGE DE LA COMMANDE CORRESPONDANTE
                $msg .= '<table class="table table-hover">';
                    $msg .= '<tr>';
                        $msg .= '<th>id_commande</th>';
                        $msg .= '<th>prix</th>';
                        $msg .= '<th>date</th>';
                        $msg .= '<th>id_membre</th>';
                        $msg .= '<th>pseudo</th>';
                        $msg .= '<th>id_produit</th>';
                        $msg .= '<th>id_salle</th>';
                        $msg .= '<th>ville</th>';
                    $msg .= '</tr>';

                    foreach($resultDetail as $orderDetail){                   

                        $resultProd = $orders->access_ModelProduct_FindProductByIdProd($orderDetail['id_produit']);
                        $resultRoom = $orders->access_ModelRoom_FindRoom($resultProd['id_salle']);                        
    
                                                                     
                        // Conversion des dates en dates Françaises et uniquement j/m/Y
                        $date = new DateTime(''. $comm['date'] .'');                           
                        $date = $date->format('d-m-Y');                    

                        $msg .= '<tr>';
                            $msg .= '<td>'.$idcom.'</td>';
                            $msg .= '<td>'.$resultProd['prix'].'</td>';
                            $msg .= '<td>'.$date.'</td>';
                            $msg .= '<td>'.$comm['id_membre'].'</td>';
                            $msg .= '<td>'.$member['pseudo'].'</td>';
                            $msg .= '<td>'.$orderDetail['id_produit'].'</td>';
                            $msg .= '<td>'.$resultProd['id_salle'].'</td>';
                            $msg .= '<td>'.$resultRoom['ville'].'</td>';
                        $msg .= '</tr>';
                    }                

                $msg .= '</table>';
            }
        }else{
            $msg .= 'Vous n\'avez pas le droit d\'accéder à cette page';
        }
              
        include 'views/orders/manageOrders.php';
    }
    
    public function sitemap(){
        
        $msg = '';
        
        
        
        include 'views/orders/sitemap.php';
    }
}

