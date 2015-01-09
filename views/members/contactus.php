<?php
include_once 'models/member.php';
$member = new Member();

$title = 'Nous contacter';
ob_start();
    ?>
    <form method='post' class="form-horizontal" role="form">
        <?php       
        if($member->sessionExists()){
        ?>
        <div class="form-group">
            <label class="label-control col-sm-4" for="exp">Expéditeur : </label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="exp" id="exp" value="<?php echo $_SESSION['user']['email']; ?>">
            </div>
        </div>
        <?php
        }else{
        ?>
        <div class="form-group">
            <label class="label-control col-sm-4" for="exp">Expéditeur : </label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="exp" id="exp" placeholder="Votre email">
            </div>
        </div>
        <?php
        }
        ?>
        <div class="form-group">
            <label class="label-control col-sm-4" for="subject">Sujet : </label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="subject" id="subject">
            </div>
        </div>
        
        <div class="form-group">
            <label class="label-control col-sm-4" for="message">Message : </label>
            <div class="col-sm-8">
                <textarea class="form-control" name="message" id="message" placeholder="Contacter l'administrateur du site"></textarea>
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-sm-12">
                <input class="btn btn-success pull-right" type="submit" name="submit" value="Envoyer">
            </div>
        </div>
    </form>    
    <?php
    foreach($errors as $error){
        echo $error . '<br/>';
    }
    echo $msg;
    $layout = ob_get_contents();
ob_clean();

include 'layouts/layout.php';

