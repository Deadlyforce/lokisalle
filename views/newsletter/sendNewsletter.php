<?php
include_once 'models/member.php';
$member = new Member();

$title = 'Envoyer la newsletter';

ob_start();
    echo $msg;
    if($member->userAdmin()){   
    ?>    
        <form method="post" class="form-horizontal" role="form">
            
            <div class="form-group">
                <label class="label-control col-sm-4" for="exp">Mail expéditeur : </label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" name="exp" id="exp" value="">
                </div>
            </div>
            
            <div class="form-group">
                <label class="label-control col-sm-4" for="subject">Sujet : </label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" name="subject" id="subject">
                </div>
            </div>
            
            <div class="form-group">
                <label class="label-control col-sm-4" for="message">Message : </label>
                <div class="col-sm-8">
                    <textarea class="form-control"  name="message" id="message" placeholder="Ma news"></textarea>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-12">
                    <input class="btn btn-success pull-right" type="submit" name="submit" value="Envoi de la newsletter aux membres" />
                </div>
            </div>
            
        </form>    
    <?php
        foreach($errors as $error){
            echo $error . '<br/>';
        }
    }else{        
        $msg .= 'Vous n\'avez pas l\'autorisation d\'accéder à cette page.';
    }
    
    $layout = ob_get_contents();
ob_clean();

include 'layouts/layout.php';


