<?php

include_once "models/result.php";

class Promotion{
    protected $id_promo, $code_promo, $reduction;
    private $connection;
    
   // ##########################################################
    
    public function getIdPromo()
    {
	return $this->id_promo;
    }
        
    public function getCodePromo()
    {
	return $this->code_promo;
    }
        
    public function getReduction()
    {
        return $this->reduction;
    }
    
    // ##########################################################
        
    public function setIdPromo($id_promo)
    {
        $this->id_promo = $id_promo;
    }
        
    public function setCodePromo($code_promo)
    {
        $this->code_promo = $code_promo;
    }
        
    public function setReduction($reduction)
    {
        $this->reduction = $reduction;
    }
    
    // VERIFICATIONS
    
    public function checkAll(){
        $resultArray[] = $this->checkPromoExists();
                               
        return $resultArray;
    }
    
    public function checkPromoExists(){
        $nbr = $this->searchCode();
        if ($nbr != 0){
            return new Result( false, "Code promo déjà en base !" );
	}else{
            return new Result( true );
	}
    }
    
    public function searchCode(){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM promotion WHERE code_promo = '$this->code_promo'");
        return $req->rowCount();
    }  
    
    public function addPromo(){
        $db = Db::getInstance();
        $req = $db->query("INSERT INTO promotion (code_promo, reduction) VALUES ('$this->code_promo', '$this->reduction')");
        return $req;
    }  
    
    public function listPromo(){
        $db = Db::getInstance();
        $req = $db->query('SELECT * FROM promotion');
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findPromo(){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM promotion WHERE id_promo = '$this->id_promo'");
        return $req->fetch(PDO::FETCH_ASSOC);
    }
    
    public function deletePromo(){
        $db = Db::getInstance();
        $req = $db->query("DELETE FROM promotion WHERE id_promo = '$this->id_promo'");
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
    
    

}  

