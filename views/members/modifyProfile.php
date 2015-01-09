<?php
    $title = 'Modifier mes informations';    
    ob_start();
    echo $msg;
    
    if($member->sessionExists()){
?>
    <form method="post" class="form-horizontal" role="form"> 
        <div class="form-group">
            <label class="label-control col-sm-4" for="pseudo">Pseudo</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="pseudo" id="pseudo" value="<?php echo $modMember['pseudo']; ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="label-control col-sm-4" for="password">Password</label>
            <div class="col-sm-8">
                <input class="form-control" type="password" name="mdp" id="password" value="<?php echo $modMember['mdp']; ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="label-control col-sm-4" for="prenom">Prénom</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="prenom" id="prenom" value="<?php echo $modMember['prenom']; ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="label-control col-sm-4" for="nom">Nom</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="nom" id="nom" value="<?php echo $modMember['nom']; ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="label-control col-sm-4" for="email">Email</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="email" id="email" value="<?php echo $modMember['email']; ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="label-control col-sm-4" for="sexe">Sexe</label>
            <div class="col-sm-8">
                <select class="form-control"  name="sexe" id="sexe">
                    <option value="m" <?php if($modMember['sexe'] == 'm'){ echo 'selected'; } ?>>Homme</option>
                    <option value="f" <?php if($modMember['sexe'] == 'f'){ echo 'selected'; } ?>>Femme</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="label-control col-sm-4" for="adresse">Adresse</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="adresse" id="adresse" value="<?php echo $modMember['adresse']; ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="label-control col-sm-4" for="cp">Code Postal</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="cp" id="cp" value="<?php echo $modMember['cp']; ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="label-control col-sm-4" for="ville">Ville</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="ville" id="ville" value="<?php echo $modMember['ville']; ?>">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <input class="btn btn-success pull-right" class="form-control" type="submit" name="submit" value="Valider les changements">
            </div>
        </div>
    </form>

<?php  
    }
        foreach($errors as $error){
            echo $error;
        }
       
        $layout = ob_get_contents();
    ob_clean();
    include 'layouts/layout.php';
