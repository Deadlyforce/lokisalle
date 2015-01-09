<?php

include_once "models/result.php";

class Product{
    
    protected $id_produit, $date_arrivee, $date_depart, $id_salle, $id_promo, $prix, $etat;

    
    // ##########################################################
	
    public function getIdProduit(){
            return $this->id_produit;
    }

    public function getDateArrivee(){
            return $this->date_arrivee;
    }

    public function getDateDepart(){
            return $this->date_depart;
    }

    public function getIdSalle(){
            return $this->id_salle;
    }

    public function getIdPromo(){
            return $this->id_promo;
    }

    public function getPrix(){
            return $this->prix;
    }

    public function getEtat(){
            return $this->etat;
    }

    // ##########################################################

    public function setIdProduit($id_produit){
            $this->id_produit = $id_produit;
    }

    public function setDateArrivee($date_arrivee){
            $this->date_arrivee = $date_arrivee;
    }

    public function setDateDepart($date_depart){
            $this->date_depart = $date_depart;
    }

    public function setIdPromo($id_promo){
            $this->id_promo = $id_promo;
    }
    
    public function setIdSalle($id_salle){
            $this->id_salle = $id_salle;
    }

    public function setPrix($prix){
            $this->prix = $prix;
    }

    public function setEtat($etat){
            $this->etat = $etat;
    }

    // ##########################################################    
    
    public function checkAll($idSalle, $dateArrivee){
        $resultArray[] = $this->checkDateDepartSupDateArrivee();
        $resultArray[] = $this->checkDateArriveeSupDateJour();
        $resultArray[] = $this->checkDatesRoomAvailable($idSalle, $dateArrivee);
        return $resultArray;
    }
    
    public function modifyCheckAll($idSalle, $dateArrivee, $dateDepart, $id_produit){
        $resultArray[] = $this->checkDateDepartSupDateArrivee();
        $resultArray[] = $this->checkDateArriveeSupDateJour();
        $resultArray[] = $this->modifyCheckDatesRoomAvailable($idSalle, $dateArrivee, $dateDepart, $id_produit);
        return $resultArray;
    }
    
            public function checkDateDepartSupDateArrivee(){
                $dateArrivee = new DateTime('' . $this->date_arrivee . '');
                $dateDepart = new DateTime('' . $this->date_depart . '');

                if($dateDepart >= $dateArrivee){
                    return new Result( true );
                }else{
                    return new Result( false, "La date de départ doit être supérieure à la date d'arrivée !" );
                }
            }
            
            public function checkDateArriveeSupDateJour(){
                $date = new DateTime('' . $this->date_arrivee . '');
                $today = new DateTime();

                if($date >= $today){
                    return new Result( true );
                }else{
                    return new Result( false, "La date ne peut être inférieure à la date du jour !" );
                }
            }
    
            public function checkDatesRoomAvailable($idSalle, $dateArrivee){
                $prod = $this->findProduct($idSalle);
                if(!$prod || !isset($prod)){
                    return new Result(true);
                }else{
                    $prod['date_depart'] = new DateTime($prod['date_depart']);
                    $dateArrivee = new DateTime($dateArrivee);
                    
                    if($prod['date_depart']<$dateArrivee){
                        return new Result(true);
                    }else{
                        return new Result(false, "Cette salle n'est pas disponible à cette date ! (déjà dans autre produit)");
                    }
                }        
            }
    
            public function modifyCheckDatesRoomAvailable($idSalle, $dateArrivee, $dateDepart, $id_produit){
                $prod = $this->findExistingProducts($idSalle, $id_produit);
                
                if(isset($prod) && !empty($prod)){                    
                    $dateArrivee = new DateTime($dateArrivee);
                    $dateDepart = new DateTime($dateDepart);

                    foreach($prod as $prodFound){
                        $prodFound['date_depart'] = new DateTime($prodFound['date_depart']);
                        $prodFound['date_arrivee'] = new DateTime($prodFound['date_arrivee']);
                        
                        if(($dateArrivee < $prodFound['date_arrivee'] && $dateDepart < $prodFound['date_arrivee']) || $dateArrivee > $prodFound['date_depart']){                        
                            return new Result(true);                    
                        }else{                        
                            return new Result(false, 'Les dates de cette salle chevauchent le produit n° ' . $prodFound['id_produit'] . '<br/>Veuillez choisir d\'autres dates');
                        }
                    }
                }else{
                    return new Result(true);
                }                      
            }
            
            
    public function checkProductDate($date){
        $dateJour = new DateTime("now");
        $date = new DateTime($date);
        if($date>$dateJour){
            return true;
        }else{
            return false;
        }
    }
    
    public function checkMemberComments($memberId, $salleId){
        include_once "models/comment.php";
        $comment = new Comment;
        
        $resultat = $comment->checkCommentFromMember($memberId, $salleId);
        return $resultat;
    }
    
    
    public function changeDatesToUSFormat(){
        $dateArrivee = new DateTime('' . $this->date_arrivee . '');         
        $dateDepart = new DateTime('' . $this->date_depart . '');         
        
        $USdateArrivee = $dateArrivee->format('Y-m-d H:i:s');
        $USdateDepart = $dateDepart->format('Y-m-d H:i:s');
        
        $this->date_arrivee = $USdateArrivee;
        $this->date_depart = $USdateDepart;        
    }
    
    public function changeDatetoFrenchFormat($date){
        $newDate = new DateTime(''. $date .'');
        $resultat = $newDate->format('d-m-Y');
        return $resultat;
    }
    
    public function addProduct(){
        $db = Db::getInstance();
        $req = $db->query("INSERT INTO produit (date_arrivee, date_depart, id_salle, id_promo, prix, etat) VALUES ('$this->date_arrivee', '$this->date_depart', '$this->id_salle', '$this->id_promo', '$this->prix', '$this->etat')");
        return $req;
    } 
    
    public function replaceProduct(){
        $db = Db::getInstance();
        $req = $db->query("REPLACE INTO produit (id_produit, date_arrivee, date_depart, id_salle, id_promo, prix, etat) VALUES ('$this->id_produit', '$this->date_arrivee', '$this->date_depart', '$this->id_salle', '$this->id_promo', '$this->prix', '$this->etat')");
        return $req;
    } 
    
    public function access_ModelRoom_List(){
        
        include_once "models/room.php";
        
        $room = new Room;
        $resultat = $room->listRooms();
        return $resultat;
    }
    
    public function access_ModelRoom_FindOne($id){
        
        include_once "models/room.php";       
        $room = new Room();
        
        $room->setIdSalle($id);
        $resultat = $room->findRoom();
        return $resultat;
    }
    
    public function access_ModelRoom_findByCity($ville){
        
        include_once "models/room.php";       
        $room = new Room();
        
        $room->setVille($ville);
        $resultat = $room->findRoomByCity();
        return $resultat;
    }
    
    public function access_ModelPromotion_List(){
        
        include_once "models/promotion.php";
        
        $promo = new Promotion;
        $resultat = $promo->listPromo();
        return $resultat;
    }
    
    public function access_ModelPromotion_FindOne($id){
        
        include_once "models/promotion.php";
        
        $promo = new Promotion;
        $promo->setIdPromo($id);
        $resultat = $promo->findPromo();
        return $resultat;
    }
    
    public function listProducts(){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM produit");
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }  
    
    public function listProductsByDateSup($userDate){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM produit WHERE date_arrivee >= '$userDate'");
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }    
               
    public function listProductsOrder($header, $dir){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM produit ORDER BY " . $header . " ". $dir ."");
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
               
    public function findProduct($idProd){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM produit WHERE id_produit = '$idProd'");
        return $req->fetch(PDO::FETCH_ASSOC);        
    }
    
    public function findProductByIdProd($idprod){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM produit WHERE id_produit = '$idprod'");
        return $req->fetch(PDO::FETCH_ASSOC);        
    }
    
    public function findSuggestions($date_arrivee){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM produit WHERE date_arrivee >= '$date_arrivee'");
        return $req->fetchAll(PDO::FETCH_ASSOC);        
    }
    
        
    public function findProductByIdProdAndOrder($idprod, $col, $sens){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM produit WHERE id_produit = '$idprod' ORDER BY ".$col." ".$sens."");
        return $req->fetch(PDO::FETCH_ASSOC);        
    }        
               
    public function findExistingProducts($idSalle, $id_produit){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM produit WHERE id_produit != '$id_produit' AND id_salle = '$idSalle'");
        return $req->fetchAll(PDO::FETCH_ASSOC);        
    }        
    
    public function deleteProduct(){
        $db = Db::getInstance();
        $req = $db->query("DELETE FROM produit WHERE id_produit = '$this->id_produit'");
        return $req;
    } 
    
    public function deleteAllProducts(){
        $db = Db::getInstance();
        $req = $db->query("DELETE FROM produit WHERE id_salle = '$this->id_salle'");
        return $req;
    } 
    
    
    public function changeStatusToUnavailableProduct($id){
        $db = Db::getInstance();
        $req = $db->query("UPDATE produit SET etat=0 WHERE id_produit = '$id'");
        return $req;
    }  
    
    public function changeStatusToAvailableProduct($id){
        $db = Db::getInstance();
        $req = $db->query("UPDATE produit SET etat=1 WHERE id_produit = '$id'");
        return $req;
    }
    
    public function setToUnavailableByIdSalle(){
        $db = Db::getInstance();
        $req = $db->query("UPDATE produit SET etat=0 WHERE id_salle = '$this->id_salle'");
        return $req;
    }     
    
    public function access_ModelMember_sessionExists(){
        include_once "models/member.php";

        $member = new Member;
        $resultat = $member->sessionExists();

        return $resultat;
    }
    
    public function access_ModelMember_userAdmin(){
        include_once "models/member.php";

        $member = new Member;
        $resultat = $member->userAdmin();

        return $resultat;
    }
        
    public function access_ModelComment_roomComments($id_salle){
        include_once "models/comment.php";
        
        $comment = new Comment;
        $resultat = $comment->roomComments($id_salle);
        return $resultat;
    }
    
    public function access_ModelMember_retrieveMember($id_member){
        include_once "models/member.php";
        
        $member = new Member;
        $resultat = $member->retrieveMember($id_member);
        return $resultat;
    }
    
    public function getCoordinates($address){
        
        $address = str_replace(" ", "+", $address); 
        $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=$address";
        $response = file_get_contents($url);
        $json = json_decode($response,TRUE); //generate array object from the response from the web
        return ($json['results'][0]['geometry']['location']['lat'].",".$json['results'][0]['geometry']['location']['lng']);
    }
}
