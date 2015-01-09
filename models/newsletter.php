<?php
include_once "models/result.php";

class Newsletter{
    
    protected $id_newsletter, $id_membre;
    
    public function getIdNewsletter(){
        return $this->id_newsletter;
    }
    
    public function getIdMembre(){
        return $this->id_membre;
    }
    
    //*************************************
    
    public function setIdMembre($id_membre){
            $this->id_membre = $id_membre;
    }
    
    //*************************************
    
    public function addNewsMember(){
        $db = Db::getInstance();
        $req = $db->query("INSERT INTO newsletter (id_membre) VALUES ('$this->id_membre')");
        return $req;
    }
    
    public function listSubscribedMembers(){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM newsletter");
        $row = $req->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    
    public function findSubscribedMember(){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM newsletter WHERE id_membre = '$this->id_membre'");
        return $req->fetch(PDO::FETCH_ASSOC);            
    }
    
    public function unsubscribe(){
        $db = Db::getInstance();
        $req = $db->query("DELETE FROM newsletter WHERE id_membre = '$this->id_membre'");
        return $req;
    }
       
    public function access_ModelMember_userAdmin(){
        include_once "models/member.php";
            
        $member = new Member;
        $resultat = $member->userAdmin();

        return $resultat;
    }
    
    public function access_ModelMember_sessionExists(){
        include_once "models/member.php";

        $member = new Member;
        $resultat = $member->sessionExists();

        return $resultat;
    }
    
    public function accessModelMember_listMembers(){
        include_once "models/member.php";
        
        $member = new Member();
        $resultat = $member->list_member();
        
        return $resultat;
    }
    
}

