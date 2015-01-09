<?php

include "models/promotion.php";

class Promotions{
    
    public function addPromo(){
        
        $msg = "";
        // Creation et remplissage d'un nouvel objet promotion
        $promotion = new Promotion;
        
        if($promotion->access_ModelMember_sessionExists() || $promotion->access_ModelMember_userAdmin()){
            if($_POST){            
                $code_promo = htmlentities($_POST['code_promo'], ENT_QUOTES, "utf-8");
                $reduction = htmlentities($_POST['reduction'], ENT_QUOTES, "utf-8");



                $promotion->setCodePromo($code_promo);
                $promotion->setReduction($reduction);

                // VERIFICATIONS

                $resultArray = $promotion->checkAll();

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
                    $promotion->addPromo();
                    $msg = '<p class="validation">Le code a bien été enregistré</p>';
                }else{
                    $msg .= $msgError;
                }          
            }
        }else{
           $msg .= 'Vous n\'avez pas le droit d\'accéder à cette page'; 
        }	
        
        include "views/promos/addPromo.php";
    }
    
    public function listPromo(){
        $msg = '';
        $table = "";
        $promotion = new Promotion;
        
        if($promotion->access_ModelMember_sessionExists() || $promotion->access_ModelMember_userAdmin()){
            $list = $promotion->listPromo();
        
            $table .= '<table id="promo" class="table table-hover">';
                $table .= '<tr>';
                    $table .= '<th>Id Promo</th>';
                    $table .= '<th class="icon">Code promo</th>';
                    $table .= '<th class="icon">Reduction</th>';
                    $table .= '<th class="icon">Supprimer</th>';
                $table .= '</tr>';

                foreach($list as $valeur){
                    $table .= '<tr>';
                        $table .= '<td>' . $valeur['id_promo'] . '</td>';
                        $table .= '<td class="icon">' . $valeur['code_promo'] . '</td>';
                        $table .= '<td class="icon">' . $valeur['reduction'] . '</td>';
                        $table .= '<td class="icon"><a href="index.php?controller=promotions&action=deletePromo&id=' . $valeur['id_promo'] . '" title="Supprimer"><i class="fa fa-trash-o fa-2x"></i></a></td>';
                    $table .= '</tr>';
                }
            $table .= '</table>'; 
        }else{
            $msg .= 'Vous n\'avez pas le droit d\'accéder à cette page';
        }              
        
        include "views/promos/listPromo.php";
    }
    
    public function deletePromo(){
        
        $msg="";
        $id = $_GET['id'];
        
        $promotion = new Promotion;
        
        if($promotion->access_ModelMember_sessionExists() || $promotion->access_ModelMember_userAdmin()){
            
            $promotion->setIdPromo($id);
        
            // GERER LES CONSEQUENCES D'UNE SUPPRESSION SUR LES PRODUITS QUI ONT CE CODE PROMO
            $delPromo = $promotion->deletePromo();

            $msg .= 'Code promo supprimé !';
        }else{
            $msg .= 'Vous n\'avez pas le droit d\'accéder à cette page';
        }        
        
        include "views/promos/deletePromo.php";
    }
    
}
