<?php
include 'incl/curl_inc.php';
include 'incl/html_inc.php';
$error_message = "";

if($_SERVER['REQUEST_METHOD'] == 'GET'){
   if(isset($_GET['N'])){
       if(isset($trips_array)){ unset($trips_array);}
       if(isset($date_array)){ unset($date_array);}
       if(isset($decoded)){ unset($decoded);}
   }
}

$decoded = getUnitList();
$trips_array = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['unit_list']) && $_POST['unit_list'] !== ""){
        $uid = $_POST['unit_list'];
        if(isset($_POST['daterange'])  && $_POST['daterange'] !== ""){
            $date_array = explode(" - ", $_POST['daterange']);
            if(count($date_array)==2){
                $trips_array = getTripList( $_POST['unit_list'], $date_array[0], $date_array[1] );
                if($trips_array === []) $error_message = "No trips where made in that time";
            }
        }else{
            $error_message = "Please select a daterange";   
        }     
    }else{
        $error_message = "Please select a vehicle";
    }
}

?>

<?php echo printHeader(); ?>

<body>
    <section>
        <?php echo printError($error_message); ?>
        <?php echo printSearchForm($decoded); ?>
    </section>
    <section>
        <?php echo printTripsMap($trips_array); ?>
    </section>
    <aside>
        <?php echo printTrips($trips_array); ?>
    </aside>
</body>
<?php echo printFooter(); ?>
