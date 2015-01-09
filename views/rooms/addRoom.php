<?php
    $title = 'Enregistrer une nouvelle salle';
  
    ob_start();
    if($room->access_ModelMember_sessionExists() && $room->access_ModelMember_userAdmin()){
?>
        <form method="post" enctype="multipart/form-data" class="form-horizontal" role="form"> 
            <div class="form-group">
                <label class="label-control col-sm-4" for="titre">Titre de la salle</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" name="titre" id="titre">
                </div>
            </div>
            
            <div class="form-group">
                <label class="label-control col-sm-4" for="photo">Photo (600x338 gif, jpg, png)</label>
                <div class="col-sm-8">
                    <input class="form-control" type="file" name="photo" id="photo">
                </div>
            </div>
            
            <div class="form-group">
                <label class="label-control col-sm-4"  for="categorie">Catégorie</label>
                <div class="col-sm-8">
                    <select class="form-control" name="categorie">
                        <option value="reunion" selected>Réunion</option>
                        <option value="conference">Conférence</option>
                        <option value="concert">Concert</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="label-control col-sm-4"  for="capacite">Capacité</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" name="capacite" id="capacite">
                </div>
            </div>
            
            <div class="form-group">
                <label class="label-control col-sm-4"  for="description">Description</label>
                <div class="col-sm-8">
                    <textarea class="form-control" name="description" id="description">Description de la salle...</textarea>
                </div>
            </div>
            
            <div class="form-group">
                <label class="label-control col-sm-4"  for="adresse">Adresse</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" name="adresse" id="adresse">
                </div>
            </div>
            
            <div class="form-group">
                <label class="label-control col-sm-4"  for="cp">Code Postal</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" name="cp" id="cp">
                </div>
            </div>
            
            <div class="form-group">
                <label class="label-control col-sm-4"  for="ville">Ville</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" name="ville" id="ville">
                </div>
            </div>
            
            <div class="form-group">
                <label class="label-control col-sm-4"  for="pays">Pays</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" name="pays" id="pays">
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-12">
                    <input class="btn btn-success pull-right" type="submit" name="submit" value="Enregistrer">
                </div>
            </div>
        </form>
<?php
    }
        echo $msg;
        foreach($errors as $error){
            echo $error;
        }
        $layout = ob_get_contents();
ob_clean();
include 'layouts/layout.php';


