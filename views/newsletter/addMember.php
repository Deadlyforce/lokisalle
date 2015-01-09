<?php
$title = 'Newsletter';
ob_start();

if(!$memberSubscribed){
?>    
    <p>Je souhaite m'abonner à la newsletter et recevoir les actualités de Lokisalle</p>
    <a href="index.php?controller=newsletters&action=subscribe&id=<?php echo $_SESSION['user']['id_membre']; ?>" alt="Inscription newsletter">S'inscrire à la newsletter</a>
    
<?php
}else{
?>
    <p>Vous êtes actuellement abonné à la newsletter.</p>
    <a href="index.php?controller=newsletters&action=unsubscribe&id=<?php echo $_SESSION['user']['id_membre']; ?>" alt="Désinscription newsletter">Se désinscrire</a>
<?php
}
    echo $msg;
    $layout = ob_get_contents();
ob_clean();

include 'layouts/layout.php';



