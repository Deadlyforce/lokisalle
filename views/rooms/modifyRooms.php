<?php
$title = 'Modifier une salle';
ob_start();
echo $msg;

if($room->access_ModelMember_sessionExists() && $room->access_ModelMember_userAdmin()){
?>    
    <form method="post" enctype="multipart/form-data" class="form-horizontal" role="form"> 
        <div class="form-group">
            <label class="label-control col-sm-4" for="titre">Titre</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="titre" id="titre" value="<?php echo $modRoom['titre']; ?>">
            </div>
        </div>
        
        <div class="form-group">
            <?php 
                echo '<label class="label-control col-sm-4">Photo actuelle: </label>';
                echo '<div class="col-sm-8"><img src="' . $modRoom['photo'] . '" width=200 /></div>';
                echo '<input type="hidden" name="actualPhoto" value="' . $modRoom['photo'] . '" />';
            ?>
        </div>    
        
        <div class="form-group">
            <label class="label-control col-sm-4" for="photo">Nouvelle photo</label>
            <div class="col-sm-8">
                <input class="form-control" type="file" name="photo" id="photo">
            </div>
        </div>
            
        <div class="form-group">
            <label class="label-control col-sm-4" for="categorie">Catégorie</label>
            <div class="col-sm-8">
                <select class="form-control" name="categorie" id="categorie">
                    <option value="reunion" <?php if($modRoom == 'reunion'){echo 'selected';} ?>>Réunion</option>
                    <option value="conference" <?php if($modRoom == 'conference'){echo 'selected';} ?>>Conférence</option>
                    <option value="concert" <?php if($modRoom == 'concert'){echo 'selected';} ?>>Concert</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="label-control col-sm-4" for="capacite">Capacité</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="capacite" id="capacite" value="<?php echo $modRoom['capacite']; ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="label-control col-sm-4" for="description">Description</label>
            <div class="col-sm-8">
                <textarea class="form-control" name="description" id="description"><?php echo $modRoom['description']; ?></textarea>
            </div>
        </div>
        
        <div class="form-group">
            <label class="label-control col-sm-4" for="adresse">Adresse</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="adresse" id="adresse" value="<?php echo $modRoom['adresse']; ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="label-control col-sm-4" for="cp">Code Postal</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="cp" id="cp" value="<?php echo $modRoom['cp']; ?>">
            </div>
        </div>
            
        <div class="form-group">
            <label class="label-control col-sm-4" for="ville">Ville</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="ville" id="ville" value="<?php echo $modRoom['ville']; ?>">
            </div>
        </div>
            
        <div class="form-group">    
            <label class="label-control col-sm-4" for="pays">Pays</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="pays" id="pays" value="<?php echo $modRoom['pays']; ?>">
            </div>
        </div>
            
        <div class="form-group"> 
            <div class="col-sm-12"> 
                <input class="btn btn-success pull-right" type="submit" name="submit" value="Envoyer">
            </div>
        </div>
    </form>
<?php
}        
        $layout = ob_get_contents();
ob_clean();
include 'layouts/layout.php';
	


