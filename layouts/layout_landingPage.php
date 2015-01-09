<?php
include 'backoffice/headerLP.php';
include 'backoffice/menuAdmin.php';
include 'backoffice/footer.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" /> 
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="public/css/bootstrap.css" />
    <link rel="stylesheet" href="public/css/style.css" />
    <link rel="stylesheet" href="public/css/styleLP.css" />
</head>
<body>
    <div class="superContainer">
        <div class="container">        
            <header>
                <?php echo $headerLP; ?>
            </header>
        </div>   	

        <?php echo $menuAdmin; ?>            

        <div class="subtitle">
            <div class="container"> 
                <p><?php echo $title; ?></p>  
            </div>
        </div>

        <div class="container"> 
            <section>          
                <?php
                echo $layout; 
                ?>        
            </section>
        </div> 

        <footer>
            <?php echo $footer; ?>
        </footer> 
    </div>
</body>
</html>

