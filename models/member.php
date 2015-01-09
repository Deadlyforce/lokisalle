<?php
include_once "models/result.php";

class Member
{
	protected $id_membre, $pseudo, $mdp, $nom, $prenom, $email, $sexe, $ville, $cp, $adresse, $statut;
	
        
	// ##########################################################
	
	public function getId(){
		return $this->id_membre;
	}
        
        public function getPseudo(){
		return $this->pseudo;
	}
        
       	public function getMdp(){
		return $this->mdp;
	}
        
        public function getNom(){
		return $this->nom;
	}
        
        public function getPrenom(){
		return $this->prenom;
	}
        
        public function getEmail(){
		return $this->email;
	}
        
        public function getSexe(){
		return $this->sexe;
	}
        
        public function getVille(){
		return $this->ville;
	}
        
        public function getCp(){
		return $this->cp;
	}
        
        public function getAdresse(){
		return $this->adresse;
	}
        
        public function getStatut(){
		return $this->statut;
	}
	
        // ##########################################################
        
        public function setId($id_membre)
        {
                $this->id_membre = $id_membre;
	}
        
        public function setPseudo($pseudo)
        {
                $this->pseudo = $pseudo;
	}
        
        public function setMdp($mdp)
        {
                $this->mdp = $mdp;
	}
        
        public function setEmail($email)
	{
                $this->email = $email;
	}
        
        public function setSexe($sexe)
	{
                $this->sexe = $sexe;
	}
        
        public function setVille($ville)
	{
                $this->ville = $ville;
	}
        
        public function setCp($cp)
	{
                $this->cp = $cp;
	}
        
        public function setAdresse($adresse)
	{
                $this->adresse = $adresse;
	}
        
        public function setStatut($statut)
	{
                $this->statut = $statut;
	}
  
	public function setPrenom($prenom)
	{
		$this->prenom = $prenom;		
	}
        
        public function setNom($nom)
	{
		$this->nom = $nom;		
	}
        
       	// ##########################################################
        
        // Cette méthode stocke un faisceau de résultats sous forme d'objets contenant un passed = true ou un false + message d'erreur
	public function checkAllConstraint(){
            
            $resultArray[] = $this->checkLengPrenom();
            $resultArray[] = $this->checkLengNom();
            $resultArray[] = $this->checkExistPseudo();
                        
            return $resultArray;
        }
        
	public function checkLengthNames(){
            
            $resultArray[] = $this->checkLengPrenom();
            $resultArray[] = $this->checkLengNom();            
                        
            return $resultArray;
        }
        
               
        public function checkLengPrenom(){
            
		$nbr = strlen($this->prenom);
		if($nbr > 3){
                    return new Result( true );
		}
		else{
                    return new Result( false, "Votre prénom doit faire plus de 3 caractères!" );
		}		
	}
        
        public function checkLengNom()
	{
		$nbr = strlen($this->nom);
		if($nbr > 3){
                    return new Result( true );
		}
		else{
                    return new Result( false, "Votre nom doit faire plus de 3 caractères!" );
		}		
	}
        
        public function checkExistPseudo()
	{
		$nbr = $this->search_member();                                
		if ($nbr == 0){
                    return new Result( true );
		}
		else{
                    return new Result( false, "Ce pseudo est déjà pris!" );
		}
	}
        
        //----------------------------- CONNEXION -------------------------------
        
        public function checkConnect()
	{
                $resultat = $this->checkConnectPseudo();
                $resultArray[] = $resultat;
                if($resultat->getPassed()){
                    $resultArray[] = $this->checkConnectPassword();
                }
                return $resultArray;        
	}
        
        // Cette méthode fait l'inverse de checkExistPseudo
        // Retourne 'Erreur de pseudo' si 0 pseudo trouvé en base
        public function checkConnectPseudo()
	{
            $nbr = $this->search_member();                                
            if ($nbr != 0){
                return new Result( true );
            }else{
                return new Result( false, "Erreur de pseudo!" );
            }
	}
        
        public function checkConnectPassword()
        {
            $found_member = $this->find_member();
                if( $found_member['mdp'] == $this->mdp ){
                    return new Result(true);
                }else{
                    return new Result(false, "Erreur de mot de passe!");
                }
        }
        
        public function checkEmail(){
            $resultat = $this->searchMemberByEmail();
            if($resultat == 0 || $resultat == ''){
                $resultArray[] = new Result(false, 'Cet email n\'existe pas.');
            }else{
                $resultArray[] = new Result(true);
            }
            return $resultArray;
        }
        
        public function checkMdp(){
            $resultat = $this->searchMemberByMdp();
            if($resultat == 0 || $resultat == ''){
                $resultArray[] = new Result(false, 'Ce mot de passe n\'existe pas.');
            }else{
                $resultArray[] = new Result(true);
            }            
            return $resultArray;
        }
                   
        // Méthode qui vérifie si l'utilisateur est connecté
        public function userConnected(){
            if(!isset($_SESSION['user'])){
                return false;
            }
            return true;
        }
         
        public function userAdmin(){
            if(isset($_SESSION['user']) && $_SESSION['user']['statut'] == 1){
                return true;
            }
            return false;
        }

        public function openSession(){             
            $found_member = $this->find_member();            
            foreach($found_member as $indice => $valeur){
                if($indice != 'mdp'){
                    $_SESSION['user'][$indice] = $valeur;
                }
            }
        }
         
        public function sessionExists(){
            if(!isset($_SESSION['user'])){
               return false; 
            }
            else{
                return true;
            }
        }
   
        public function replacePassword(){           
            
            $db = Db::getInstance();
            $req = $db->query("UPDATE membre SET mdp= '$this->mdp' WHERE email = '$this->email'");
            return $req;
        } 
         
        public function search_member(){
            $db = Db::getInstance();
            $req = $db->query("SELECT * FROM membre WHERE pseudo = '$this->pseudo'");
            return $req->rowCount();            
        }
        
        public function searchMemberByEmail(){
            $db = Db::getInstance();
            $req = $db->query("SELECT * FROM membre WHERE email = '$this->email'");
            return $req->rowCount();            
        }
        
        public function searchMemberByMdp(){
            $db = Db::getInstance();
            $req = $db->query("SELECT * FROM membre WHERE mdp = '$this->mdp'");
            return $req->rowCount();            
        }
        
        public function find_member(){
            $db = Db::getInstance();
            $req = $db->query("SELECT * FROM membre WHERE pseudo = '$this->pseudo'");
            return $req->fetch(PDO::FETCH_ASSOC);            
        }
        
        public function retrieveMember($id_member){
            $db = Db::getInstance();
            $req = $db->query("SELECT * FROM membre WHERE id_membre = '$id_member'");
            return $req->fetch(PDO::FETCH_ASSOC);            
        }
        
        public function list_member(){
            $db = Db::getInstance();
            $req = $db->query("SELECT * FROM membre");
            $row = $req->fetchAll(PDO::FETCH_ASSOC); 
            return $row;
        }            
               
        public function add_member(){
            $db = Db::getInstance();
            $req = $db->query("INSERT INTO membre (pseudo, mdp, nom, prenom, email, sexe, ville, cp, adresse, statut) VALUES ('$this->pseudo', '$this->mdp', '$this->nom', '$this->prenom', '$this->email', '$this->sexe', '$this->ville', '$this->cp', '$this->adresse', 0)");
            return $req;            
        }
      
        public function modifyMember(){                        
            $db = Db::getInstance();
            $req = $db->query("UPDATE membre SET pseudo='$this->pseudo', mdp='$this->mdp', nom='$this->nom', prenom='$this->prenom', email='$this->email', sexe='$this->sexe', ville='$this->ville', cp='$this->cp', adresse='$this->adresse' WHERE id_membre = '$this->id_membre'");
            return $req;
        }
        
        public function del_member(){
            $db = Db::getInstance();
            $req = $db->query("DELETE FROM membre WHERE id_membre = '$this->id_membre'");
            return $req;            
        }
        
        public function addAdmin(){
            $db = Db::getInstance();
            $req = $db->query("INSERT INTO membre (pseudo, mdp, nom, prenom, email, sexe, ville, cp, adresse, statut) VALUES ('$this->pseudo', '$this->mdp', '$this->nom', '$this->prenom', '$this->email', '$this->sexe', '$this->ville', '$this->cp', '$this->adresse', '$this->statut')");
            return $req;            
        }
        
        public function randomPassword(){
            $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $password = array();
            $alphabetLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 8; $i++) {
                $n = rand(0, $alphabetLength);
                $password[] = $alphabet[$n];
            }
            $password = implode($password);  //turn the array into a string
            
            return $password; 
        }
        
        public function access_modelNewsletter_unsubscribe(){
            include 'models/newsletter.php';
            
            $newsletter = new Newsletter();
            $newsletter->setIdMembre($this->id_membre);
            $resultat = $newsletter->unsubscribe();
            
            return $resultat;
        }
        
        public function access_modelComment_deleteAllCommentsByMemberId(){
            include 'models/comment.php';
            
            $comment = new Comment();
            $comment->setIdMembre($this->id_membre);
            $resultat = $comment->deleteAllCommentsByMemberId();
            
            return $resultat;
        }
        
        public function access_modelOrder_findOrdersById(){
            include 'models/order.php';
            
            $order = new Order();            
            $resultat = $order->selectOrders($this->id_membre);
            
            return $resultat;
        }
         
}

