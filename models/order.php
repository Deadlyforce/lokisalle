<?php

include_once "models/result.php";

class Order{
    
    protected $id_commande, $montant, $id_membre, $id_produit, $date;
//    private $connection;
    
    
    // ##########################################################
    
    public function getIdCommande(){
        return $this->id_commande;
    }
    
    public function getMontant(){
        return $this->montant;
    }
    
    public function getIdMembre(){
        return $this->id_membre;
    }
    
    public function getDate(){
        return $this->date;
    }
    
    public function getIdProduit(){
        return $this->id_produit;
    }
    
    // ##########################################################
        
    public function setIdCommande($id_commande){
            $this->id_commande = $id_commande;
    }

    public function setMontant($montant){
            $this->montant = $montant;
    }

    public function setIdMembre($id_membre){
            $this->id_membre = $id_membre;
    }

    public function setDate($date){
            $this->date = $date;
    }
    
    public function setIdProduit($id_produit){
            $this->id_produit = $id_produit;
    }
    
    // ##########################################################
    
    public function checkAllConstraint($id_product){
        $resultArray[] = $this->checkProductInSession($id_product);
        return $resultArray;
    }
    
    public function checkProductInSession($id_product){
              
        $exists = array_search($id_product, $_SESSION['cart']['id_produit']); 
        if(!is_int($exists)){
            return new Result( true );
        }
        else{
            return new Result( false, '<div class="alert alert-warning">Vous avez déjà réservé ce produit !</div>' );
        }
    }
    
    public function checkCartConstraints($cgv){
            
        $resultArray[] = $this->checkCGV($cgv);
        
        return $resultArray;
    }
    
    public function checkCGV($cgv){
        if(isset($cgv) && $cgv == 1){
            return new Result( true );
        }
        else{
            return new Result( false, '<div class="alert alert-warning">Vous devez accepter les conditions générales de vente pour payer</div>' );
        }
    }
    
    public function access_ModelProduct_findProduct($id){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM produit WHERE id_produit = '$id'");
        return $req->fetch(PDO::FETCH_ASSOC);
    }
    
    public function access_ModelProduct_FindProductByIdProd($id){
        
        include_once "models/product.php";
        
        $product = new Product();
        $resultat = $product->findProductByIdProd($id);        
        return $resultat;
    }
    
    public function access_ModelProduct_FindProductByIdProdAndOrder($id, $col, $sens){
        
        include_once "models/product.php";
        
        $product = new Product();
        $resultat = $product->findProductByIdProdAndOrder($id, $col, $sens);        
        return $resultat;
    }
    
    public function access_ModelRoom_FindRoom($id){
        
        include_once "models/room.php";
        
        $room = new Room;
        $room->setIdSalle($id);
        $resultat = $room->findRoom();
        return $resultat;
    }
    
    public function access_ModelProduct_changeStatusToUnavailableProduct($id){
        
        include_once "models/product.php";
        
        $product = new Product;
        $product->changeStatusToUnavailableProduct($id);        
    }
    
    public function access_ModelProduct_changeStatusToAvailableProduct($id){
        
        include_once "models/product.php";
        
        $product = new Product;
        $product->changeStatusToAvailableProduct($id);        
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
    
    public function access_ModelMember_findMember($idMember){
        include_once "models/member.php";

        $member = new Member;
        $resultat = $member->retrieveMember($idMember);

        return $resultat;
    }
    
    public function createCart(){
        // Si une session "cart" n'existe pas encore je la crée
        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = array();
            $_SESSION['cart']['id_produit'] = array();
            $_SESSION['cart']['titre'] = array();
            $_SESSION['cart']['date_arrivee'] = array();
            $_SESSION['cart']['date_depart'] = array();
            $_SESSION['cart']['prix'] = array();
        }
        return true;
    }
    
    public function addOrder(){
        $db = Db::getInstance();
        $req = $db->query("INSERT INTO commande (montant, id_membre, date) VALUES ('$this->montant', '$this->id_membre', NOW())");
        return $req;
    }
    
    public function addOrderDetails(){
        $db = Db::getInstance();
        $req = $db->query("INSERT INTO details_commande (id_commande, id_produit) VALUES ('$this->id_commande', '$this->id_produit')");
        return $req;
    }
    
    public function selectOrderDetails($idCommande){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM details_commande WHERE id_commande = '$idCommande'");
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectOrder($memberID){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM commande WHERE id_membre = '$memberID'");
        return $req->fetch(PDO::FETCH_ASSOC);
    }
    
    public function selectOrders($memberID){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM commande WHERE id_membre = '$memberID'");
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findOrder($id){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM commande WHERE id_commande = '$id'");
        return $req->fetch(PDO::FETCH_ASSOC);
    }  
        
    public function selectAll(){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM commande");
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectAllOrder($header, $dir){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM commande ORDER BY " . $header . " ". $dir ."");
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function access_ModelPromo_listPromo(){
        include_once 'models/promotion.php';
        
        $promo = new Promotion;
        $resultat = $promo->listPromo();
        return $resultat;
    }
    
}

