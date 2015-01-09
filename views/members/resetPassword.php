<?php
$title = 'Entrer un nouveau mot de passe';    
ob_start();
if($msgError == ''){
?>    
    <form method="post" action="" class="form-horizontal" role="form">
        <div class="form-group">
            <label class="label-control col-sm-4" for="email">Votre email</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="email" name="email" placeholder="Votre email" />
            </div>
        </div>
        
        <div class="form-group">
            <label class="label-control col-sm-4" for="password">Votre nouveau mot de passe</label>
            <div class="col-sm-8">
                <input class="form-control" type="password" id="password" name="password" placeholder="Nouveau mot de passe" />
            </div>
        </div>        
        
        <div class="form-group">
            <div class="col-sm-12">
                <input class="btn btn-success pull-right" type="submit" id="send" name="send" value="Envoyer" />
            </div>
        </div>
    </form>
<?php
}
    foreach($errors as $error){
        echo $error . '<br/>';
    }
    echo $msg;     
    $layout = ob_get_contents();
ob_clean();
include 'layouts/layout.php';