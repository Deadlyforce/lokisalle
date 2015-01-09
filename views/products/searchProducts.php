<?php
$title = 'Rechercher un produit';
ob_start();
?> 
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-10 searchTitle">
            <p>Recherche d'une location de salle pour réservation</p>
        </div>        
    </div>

    <div class="row">       
        <form method="post" class="form-horizontal" role="form">            
            <div class="form-group">
                <div class="col-sm-2"></div>
                <label class="label-control col-sm-2" for="day">jour</label>
                <div class="col-sm-4">
                    <select class="form-control" name="day" id="day">
                        <?php
                        for($i=1;$i<=31;$i++){
                            if($day == $i){
                                $sel = 'selected';
                            }else{
                                $sel = '';
                            }
                            echo '<option value="'.$i.'" '.$sel.'>'.$i.'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-4"></div>
            </div>

            <div class="form-group">
                <div class="col-sm-2"></div>
                <label class="label-control col-sm-2" for="month">Mois</label>
                <div class="col-sm-4">
                    <select class="form-control" name="month" id="month">
                        <option value="01" <?php if($month == 01){echo 'selected';} ?>>Janvier</option>
                        <option value="02" <?php if($month == 02){echo 'selected';} ?>>Février</option>
                        <option value="03" <?php if($month == 03){echo 'selected';} ?>>Mars</option>
                        <option value="04" <?php if($month == 04){echo 'selected';} ?>>Avril</option>
                        <option value="05" <?php if($month == 05){echo 'selected';} ?>>Mai</option>
                        <option value="06" <?php if($month == 06){echo 'selected';} ?>>Juin</option>
                        <option value="07" <?php if($month == 07){echo 'selected';} ?>>Juillet</option>
                        <option value="08" <?php if($month == 08){echo 'selected';} ?>>Août</option>
                        <option value="09" <?php if($month == 09){echo 'selected';} ?>>Septembre</option>
                        <option value="10" <?php if($month == 10){echo 'selected';} ?>>Octobre</option>
                        <option value="11" <?php if($month == 11){echo 'selected';} ?>>Novembre</option>
                        <option value="12" <?php if($month == 12){echo 'selected';} ?>>Décembre</option>
                    </select>
                </div>
                <div class="col-sm-4"></div>
            </div>

            <div class="form-group">
                <div class="col-sm-2"></div>
                <label class="label-control col-sm-2" for="year">Année</label>
                <div class="col-sm-4">
                    <select class="form-control" name="year" id="year">
                        <?php
                        $yearOption = $year;                
                        for($j=0; $j<3; $j++){
                            if($j==0){
                                $sel = 'selected';
                            }else{
                                $sel = '';
                            }
                            $yearAff = $yearOption+$j;
                            echo '<option value="'.$yearAff.'" '.$sel.'>' . $yearAff . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-4"></div>
            </div>

            <div class="form-group">
                <div class="col-sm-2"></div>
                <label class="label-control col-sm-2" for="keyword">Mots clefs</label>
                <div class="col-sm-4">
                    <input class="form-control" type="text" name="keyword" id="keyword" placeholder="Ex: Paris">                
                </div>
                <div class="col-sm-4"></div>
            </div>

            <div class="form-group">
                <div class="col-sm-8">
                    <input class="btn btn-success pull-right" type="submit" name="submit" value="Recherche">
                </div>
                <div class="col-sm-4"></div>
            </div>
        </form>
    </div>
<?php
        echo $msg;
        $layout = ob_get_contents();
    ob_clean();
    include 'layouts/layout.php';

