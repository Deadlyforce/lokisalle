<?php
    $title = 'Top 5 des salles les mieux notées';    
    ob_start();
    
        echo $msg;        
        $layout = ob_get_contents();
    
    ob_clean();
    include 'layouts/layout.php';

