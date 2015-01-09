<?php

include "models/comment.php";

class Comments{
    
    public function addComment($id_membre, $id_salle){
        $msg = "";
        if($_POST){
            $comment = htmlentities($_POST['comment'], ENT_QUOTES, "utf-8");
            $note = htmlentities($_POST['note'], ENT_QUOTES, "utf-8");
            
            $dateNow = new DateTime("now");
            $date = $dateNow->format('Y-m-d H:i:s');
            
            $commentaire = new Comment;
            
            $commentaire->setIdMembre($id_membre);
            $commentaire->setIdSalle($id_salle);
            $commentaire->setComment($comment);
            $commentaire->setNote($note);            
            $commentaire->setDate($date);
                                  
            //Vérifications si besoin avant entrée en base
            $msgError = "";
            
            //Entrée en base
            if($msgError == ""){
                $commentaire->addComment();
            }else{
                $msg .= $msgError;
            }
        }
    }
    
    public function listComments(){
        
        $msg = "";
        $table = "";
        $comment = new Comment;
        
        if($comment->access_ModelMember_sessionExists() || $comment->access_ModelMember_userAdmin()){
            $list = $comment->listComments();
        
            $table .= '<table id="members" class="table table-hover">';
                $table .= '<tr>';
                    $table .= '<th>Id Avis</th>';
                    $table .= '<th>Id Membre</th>';
                    $table .= '<th>Id Salle</th>';
                    $table .= '<th>Commentaire</th>';
                    $table .= '<th class="icon">Note/20</th>';
                    $table .= '<th class="icon">Date</th>';
                    $table .= '<th class="icon">Supprimer</th>';
                $table .= '</tr>';

                foreach($list as $valeur){
                    $date = new DateTime($valeur['date']);
                    $valeur['date'] = $date->format('d-m-Y H:i');
                    
                    $table .= '<tr>';
                        $table .= '<td>' . $valeur['id_avis'] . '</td>';
                        $table .= '<td>' . $valeur['id_membre'] . '</td>';
                        $table .= '<td>' . $valeur['id_salle'] . '</td>';
                        $table .= '<td>' . $valeur['commentaire'] . '</td>';
                        $table .= '<td class="icon">' . $valeur['note'] . '</td>';
                        $table .= '<td class="icon">' . $valeur['date'] . '</td>';
                        $table .= '<td class="icon"><a href="index.php?controller=comments&action=deleteComment&id=' . $valeur['id_avis'] . '" title="Supprimer"><i class="fa fa-trash-o fa-2x"></i></a></td>';
                    $table .= '</tr>';
                }
                $table .= '</table>';
        }else{
           $msg .= 'Vous n\'avez pas le droit d\'accéder à cette page.<br/>'; 
        }
        
                
                
        include "views/comments/listComments.php";
    }
        
    public function deleteComment(){
        
        $msg="";
        $id = $_GET['id'];
        
        $comment = new Comment;
        $comment->setIdAvis($id);
        
        $delComment = $comment->deleteComment();
        
        $msg .= '<div class="alert alert-success">';
            $msg .= 'Avis supprimé !';
        $msg .= '</div>';
        
        include "views/comments/deleteComment.php";
    }
      
    public function top5note(){
        $msg = '';
        
        $comment = new Comment();
        
        if($comment->access_ModelMember_sessionExists() && $comment->access_ModelMember_userAdmin()){
            
            $list = $comment->avgComments();           
            
            foreach($list as $key=>$valeur){
                $note[$key] = $valeur['AVG(note)'];
            }
            
            array_multisort($note, SORT_DESC, $list);   
            
            $msg .= '<table class="table table-hover">';
                $msg .= '<tr>';
                    $msg .= '<th>id_salle</th>';
                    $msg .= '<th>photo</th>';
                    $msg .= '<th>Nom</th>';
                    $msg .= '<th>Note moyenne</th>';
                $msg .= '</tr>';
                
                if(count($list)<5){
                    $limit = count($list);
                }else{
                    $limit = 5;
                }
                
                for($i=0; $i<$limit; $i++){                
                    $foundRoom = $comment->access_ModelRoom_findRoom($list[$i]['id_salle']);
                    $list[$i]['AVG(note)'] = number_format($list[$i]['AVG(note)'], 1);
                    
                    // AFFICHAGE DES RESULTATS
                                        
                    $msg .= '<tr>';
                        $msg .= '<td>'. $list[$i]['id_salle'] .'</td>';
                        $msg .= '<td><img src="' . $foundRoom['photo'] . '" width=150 /></td>';
                        $msg .= '<td>' . $foundRoom['titre'] . '</td>';
                        $msg .= '<td>'. $list[$i]['AVG(note)'] . '</td>';
                    $msg .= '</tr>';

                }
            $msg .= '</table>';
            
        }else{
            $msg .= 'Vous n\'avez pas le droit d\'accéder à cette page.<br/>';
        } 
        
        include 'views/stats/top5note.php';
    }
        
}
    


