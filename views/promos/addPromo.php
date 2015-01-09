<?php
    $title = 'Ajouter un code promo';    
    ob_start();
    if($promotion->access_ModelMember_sessionExists() || $promotion->access_ModelMember_userAdmin()){
?>
        <form method="post"> 
            <label for="code_promo">Code Promo</label>
            <input type="text" name="code_promo" id="code_promo"><br/>

            <label for="reduction">Réduction</label>
            <input type="text" name="reduction" id="reduction"><br/>

            <input type="submit" name="submit" value="Enregistrer"><br/>
        </form>
<?php
    }
        echo $msg;
        $layout = ob_get_contents();
ob_clean();
include 'layouts/layout.php';




