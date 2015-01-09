<?php

include_once "models/result.php";

class Comment{
    
    protected $id_avis, $id_membre, $id_salle, $commentaire, $note, $date;
    private $connection;
        
    
    // ##########################################################
    
    public function getIdAvis()
    {
            return $this->id_avis;
    }

    public function getIdMembre()
    {
            return $this->id_membre;
    }

    public function getIdSalle()
    {
            return $this->id_salle;
    }

    public function getComment()
    {
            return $this->commentaire;
    }

    public function getNote()
    {
            return $this->note;
    }

    public function getDate()
    {
            return $this->date;
    }
    
    // ##########################################################
        
    public function setIdAvis($id_avis)
    {
            $this->id_avis = $id_avis;
    }

    public function setIdMembre($id_membre)
    {
            $this->id_membre = $id_membre;
    }

    public function setIdSalle($id_salle)
    {
            $this->id_salle = $id_salle;
    }

    public function setComment($commentaire)
    {
            $this->commentaire = $commentaire;
    }

    public function setNote($note)
    {
            $this->note = $note;
    }

    public function setDate($date)
    {
            $this->date = $date;
    }
 
    // ##########################################################
    

    
    public function listComments(){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM avis ORDER BY id_salle, date");
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function avgComments(){
        $db = Db::getInstance();
        $req = $db->query("SELECT id_salle, AVG(note) FROM avis GROUP BY id_salle");
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }        
    
    public function deleteComment(){
        $db = Db::getInstance();
        $req = $db->query("DELETE FROM avis WHERE id_avis = '$this->id_avis'");
        return $req;
    }
    
    public function deleteAllCommentsByMemberId(){
        $db = Db::getInstance();
        $req = $db->query("DELETE FROM avis WHERE id_membre = '$this->id_membre'");
        return $req;
    }
    
    public function deleteAllCommentsByRoomId(){
        $id_salle = intval($this->id_salle);
        $db = Db::getInstance();
        $req = $db->query("DELETE FROM avis WHERE id_salle = '$id_salle'");
        return $req;
    }
    
    public function roomComments($id){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM avis WHERE id_salle='$id'");
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }  
    
    public function addComment(){
        $db = Db::getInstance();
        $req = $db->query("INSERT INTO avis (id_membre, id_salle, commentaire, note, date) VALUES ('$this->id_membre', '$this->id_salle', '$this->commentaire', '$this->note', '$this->date')");
        return $req;
    }
    
    public function checkCommentFromMember($memberId, $salleId){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM avis WHERE id_salle='$salleId' AND id_membre='$memberId'");
        return $req->fetchAll(PDO::FETCH_ASSOC);
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
    
    public function access_ModelRoom_findRoom($idSalle){
        include_once "models/room.php";
        
        $room = new Room();
        $room->setIdSalle($idSalle);
        $resultat = $room->findRoom();

        return $resultat;
    }
}

