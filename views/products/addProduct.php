<?php
$title = 'Ajouter un produit';
ob_start();
if($product->access_ModelMember_sessionExists() && $product->access_ModelMember_userAdmin()){
?>
    <form method="post" class="form-horizontal" role="form"> 
        <div class="form-group">
            <label class="control-label col-sm-4" for="salles">Choisir une salle parmi la liste</label>
            <div class="col-sm-8">
                <select class="form-control" name="salles" id="salles">
                    <?php echo $optionsSalles ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="dateArrivee">Date d'arrivée (jj-mm-AAAA)</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="dateArrivee" id="dateArrivee">
            </div>
        </div>
        
        <div class="form-group">
            <label class="control-label col-sm-4" for="dateDepart">Date de départ (jj-mm-AAAA)</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="dateDepart" id="dateDepart">
            </div>
        </div>
        
        <div class="form-group">
            <label class="control-label col-sm-4" for="prix">Prix</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="prix" id="prix">
            </div>
        </div>
        
        <div class="form-group">
            <label class="control-label col-sm-4" for="etat">Etat(1 actif / 0 inactif)</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="etat" id="etat">
            </div>
        </div>
        
        <div class="form-group">
            <label class="control-label col-sm-4" for="remise">Attribution de remise parmi les codes promo existants</label>
            <div class="col-sm-8">
                <select class="form-control" name="remise" id="remise">
                    <?php echo $optionsRemise ?>
                </select>
            </div>
        </div>
            
        <div class="form-group">
            <div class="col-sm-12">
                <input class="btn btn-success pull-right" type="submit" name="submit" id="submit" value="Créer le produit">
            </div>
        </div>
    </form>
<?php
}
        echo $msg;
        $layout = ob_get_contents();
ob_clean();
include 'layouts/layout.php';

	
