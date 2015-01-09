<?php

include_once "models/result.php";

class Room{
    
    protected $id_salle, $pays, $ville, $adresse, $cp, $titre, $description, $photo, $capacite, $categorie;

    // ##########################################################
	
	public function getIdSalle()
	{
		return $this->id_salle;
	}
        
        public function getPays()
	{
		return $this->pays;
	}
        
       	public function getVille()
	{
		return $this->ville;
	}
        
        public function getAdresse()
	{
		return $this->adresse;
	}
        
        public function getCP()
	{
		return $this->CP;
	}
        
        public function getTitre()
	{
		return $this->titre;
	}
        
        public function getDescription()
	{
		return $this->description;
	}
        
        public function getPhoto()
	{
		return $this->photo;
	}
        
        public function getCapacite()
	{
		return $this->capacite;
	}
        
        public function getCategorie()
	{
		return $this->categorie;
	}
        
        // ##########################################################
        
        public function setIdSalle($id_salle)
        {
                $this->id_salle = $id_salle;
	}
       
        
        public function setPays($pays)
        {
                $this->pays = $pays;
	}
        
        public function setVille($ville)
        {
                $this->ville = $ville;
	}
        
        public function setAdresse($adresse)
	{
                $this->adresse = $adresse;
	}
        
        public function setCP($cp)
	{
                $this->cp = $cp;
	}
        
        public function setTitre($titre)
	{
                $this->titre = $titre;
	}
        
        public function setDescription($description)
	{
                $this->description = $description;
	}
        
        public function setPhoto($photo)
	{
                $this->photo = $photo;
	}
        
        public function setCapacite($capacite)
	{
                $this->capacite = $capacite;
	}
  
	public function setCategorie($categorie)
	{
		$this->categorie = $categorie;		
	}
        
        // ##########################################################
        
        public function checkAll(){
            $resultArray[] = $this->checkRoomNotInBDD();
            $resultArray[] = $this->checkPhotoUploaded();
            $resultArray[] = $this->checkPhotoExtension(); 
            $resultArray[] = $this->checkDescriptionLength();
            
            return $resultArray;
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
                
        public function checkPhotoReplace(){
            $resultArray[] = $this->checkPhotoExtension();
        }
        
        public function checkPhotoExtension(){
            $path = $_FILES['photo']['name'];
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $extension = strtolower($extension);
            
            $validExtensions = array("gif", "jpg", "jpeg", "png"); 
            
            // in_array cherche l'argument1 dans argument2 et renvoie un booleen
            $findExtension = in_array($extension, $validExtensions); 
            if($findExtension){
                return new Result(true);
            }else{
                return new Result(false,'<div class="alert alert-warning">Cette extension d\'image n\'est pas autorisée!</div>');
            }
        }
        
        public function checkPhotoUploaded(){
            if(!empty($_FILES['photo']['name'])){
                return new Result(true);
            }else{
                return new Result(false, '<div class="alert alert-warning">La photo n\'a pas été chargée!</div>');
            }
        }
        
        public function checkRoomNotInBDD(){
            $nombre = $this->searchRoomByTitle();
            if($nombre == 0){
                return new Result( true );
            }else{
                return new Result( false, '<div class="alert alert-warning">Cette salle existe déjà en base!</div>' );
            }
        }
                
        public function searchRoomByTitle(){
            $db = Db::getInstance();
            $req = $db->query("SELECT * FROM salle WHERE titre = '$this->titre'");
            return $req->rowCount();
        }
                
        public function checkDescriptionLength(){            
            $length = mb_strlen($this->getDescription(), 'UTF-8');             
            if($length <= 450){
                return new Result( true );
            }else{
                return new Result( false, '<div class="alert alert-warning">Descrition trop longue... '. $length .' caractères</div>' );
            }
        }
        
        public function addRoom(){
            $photo_bdd = "";                        
            $photo_bdd = "assets/images/" . $_FILES['photo']['name'];
            $photo_folder = $_SERVER['DOCUMENT_ROOT'] . "/assets/images/" . $_FILES['photo']['name'];
//            var_dump($photo_bdd);
//            var_dump($photo_folder);
//            var_dump($_FILES['photo']['tmp_name']);
            // Copie la photo
            copy($_FILES['photo']['tmp_name'], $photo_folder);
            
            $db = Db::getInstance();
            $req = $db->query("INSERT INTO salle (pays, ville, adresse, cp, titre, description, photo, capacite, categorie) VALUES ('$this->pays', '$this->ville', '$this->adresse', '$this->cp', '$this->titre', '$this->description', '$photo_bdd', '$this->capacite', '$this->categorie')");
            return $req;
        }
        
                
        public function replaceRoom(){
            $photo_bdd = "";
            
            // 2 cas: upload d'une photo par formulaire en FILE (type="file") ou recup de l'actuelle photo en POST (formulaire input type="hidden")
            if(!empty($_FILES['photo']['name'])){
                $photo_bdd = "assets/images/" . $_FILES['photo']['name'];
                $photo_folder = $_SERVER['DOCUMENT_ROOT'] . "/assets/images/" . $_FILES['photo']['name'];
//                 Copie le chemin final dans le chemin temporaire de la photo
                copy($_FILES['photo']['tmp_name'], $photo_folder);
            }else{
                $photoFile = 'actualPhoto';
                $photo_bdd = $_POST['actualPhoto'];
            }
            
            $db = Db::getInstance();
            $req = $db->query("REPLACE INTO salle (id_salle, pays, ville, adresse, cp, titre, description, photo, capacite, categorie) VALUES ('$this->id_salle', '$this->pays', '$this->ville', '$this->adresse', '$this->cp', '$this->titre', '$this->description', '$photo_bdd', '$this->capacite', '$this->categorie')");
            return $req;
        }
                
        public function listRooms(){
            $db = Db::getInstance();
            $req = $db->query("SELECT * FROM salle");
            $row = $req->fetchAll(PDO::FETCH_ASSOC);
            return $row;
        }
                
        public function findRoom(){
            $db = Db::getInstance();
            $req = $db->query("SELECT * FROM salle WHERE id_salle = '$this->id_salle'");
            return $req->fetch(PDO::FETCH_ASSOC);            
        }
        
        public function findRoomByCity(){
            $db = Db::getInstance();
            $req = $db->query("SELECT * FROM salle WHERE ville = '$this->ville'");
            return $req->fetchAll(PDO::FETCH_ASSOC);            
        }
        
        public function deleteRoom(){
            $db = Db::getInstance();
            $req = $db->query("DELETE FROM salle WHERE id_salle = '$this->id_salle'");
            return $req;
        }
        
        public function access_ModelProduct_List(){
            include "models/product.php";
            
            $product = new Product;
            $resultat = $product->listProducts();
            
            return $resultat;
        }
        
        public function access_ModelProduct_deleteAllProducts(){
            include "models/product.php";
            
            $product = new Product();
            $resultat = $product->deleteAllProducts();
            
            return $resultat;
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
        
        public function access_ModelComment_deleteAllCommentsByRoomId(){
            include_once "models/comment.php";
            
            $comment = new Comment();
            $resultat = $comment->deleteAllCommentsByRoomId();
            
            return $resultat;
        }
        
        public function access_ModelProduct_setToUnavailableByIdSalle(){
            include_once "models/product.php";
            
            $product = new Product();
            $resultat = $product->setToUnavailableByIdSalle();
            
            return $resultat;
        }
}

