<?php
include 'backoffice/header.php';
include 'backoffice/menuAdmin.php';
include 'backoffice/footer.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" /> 
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="public/css/bootstrap.css" />
    <link rel="stylesheet" href="public/css/font-awesome-4.1.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="public/jquery/owl.carousel.2.0.0-beta.2.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="public/jquery/owl.carousel.2.0.0-beta.2.4/assets/owl.theme.default.min.css" />
    <link rel="stylesheet" href="public/css/style.css" />
        
    <script type="text/javascript" src="public/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="public/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="public/jquery/owl.carousel.2.0.0-beta.2.4/owl.carousel.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    
</head>       
<body>
    <div class="superContainer">
        <div class="container">        
            <header>
                <?php echo $header; ?>
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
                <?php echo $layout; ?>        
            </section>
        </div>

        <footer>
            <?php echo $footer; ?>
        </footer> 
    </div>
</body>
</html>

<script type="text/javascript">
    //FONCTIONS DE GEOCODAGE

    var geocoder;
    var map;

    function initialize() {
      geocoder = new google.maps.Geocoder();
      var latlng = new google.maps.LatLng(-34.397, 150.644);
      var mapOptions = {
        zoom: 16,
        center: latlng
      };
      map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
    }

    function codeAddress() {
    /*On récupère la valeur de notre champ input name= address */
      var address = document.getElementById('address').value;
      geocoder.geocode( { 'address': address}, function(results, status) {
        if(status === google.maps.GeocoderStatus.OK){
          map.setCenter(results[0].geometry.location);
          var marker = new google.maps.Marker({
              map: map,
              position: results[0].geometry.location
          });
        } else {
          alert('Geocode was not successful for the following reason: ' + status);
        }
      });
    }
    
    // SCRIPT DE LANCEMENT DU GEOCODAGE
    window.onload = function(){
      initialize();
      codeAddress();
    };
    
</script>

<script type="text/javascript">
    $(document).ready(function(){
       $('a').tooltip(); 
    });
    
    $(document).ready(function(){
        $(".owl-carousel").owlCarousel();
    });
</script>

