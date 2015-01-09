<?php
    $title = 'S\'incrire sur Lokisalle';    
    ob_start();
    echo $msg;
?>
    <form method="post" class="form-horizontal" role="form"> 
        <div class="form-group">
            <label class="label-control col-sm-4" for="pseudo">Pseudo</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="pseudo" id="pseudo">
            </div>
        </div>
        
        <div class="form-group">
            <label class="label-control col-sm-4" for="password">Password</label>
            <div class="col-sm-8">
                <input class="form-control" type="password" name="mdp" id="password">
            </div>
        </div>
        
        <div class="form-group">
            <label class="label-control col-sm-4" for="prenom">Prénom</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="prenom" id="prenom">
            </div>
        </div>

        <div class="form-group">
            <label class="label-control col-sm-4" for="nom">Nom</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="nom" id="nom">
            </div>
        </div>

        <div class="form-group">
            <label class="label-control col-sm-4" for="email">Email</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="email" id="email">
            </div>
        </div>

        <div class="form-group">
            <label class="label-control col-sm-4" for="sexe">Sexe</label>
            <div class="col-sm-8">
                <select class="form-control"  name="sexe" id="sexe">
                    <option value="m" selected>Homme</option>
                    <option value="f">Femme</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="label-control col-sm-4" for="adresse">Adresse</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="adresse" id="adresse">
            </div>
        </div>

        <div class="form-group">
            <label class="label-control col-sm-4" for="cp">Code Postal</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="cp" id="cp">
            </div>
        </div>

        <div class="form-group">
            <label class="label-control col-sm-4" for="ville">Ville</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="ville" id="ville">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <input class="btn btn-success pull-right" class="form-control" type="submit" name="submit" value="Je m'inscris">
            </div>
        </div>
    </form>

<?php  
        foreach($errors as $error){
            echo $error;
        }
       
        $layout = ob_get_contents();
    ob_clean();
    include 'layouts/layout.php';
