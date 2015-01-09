<?php
$title = 'Produit modifié!';
ob_start();
if($product->access_ModelMember_sessionExists() || $product->access_ModelMember_userAdmin()){

    echo $msg;
}   
    $layout = ob_get_contents();
ob_clean();
include 'layouts/layout.php';



