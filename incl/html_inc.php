<?php
function printHeader(){
    $ret_val = "<!doctype html>

<html lang=\"en\">
<head>
  <meta charset=\"utf-8\">

  <title>iTrack Map</title>
  <meta name=\"description\" content=\"API test to track vehicles\">
  <meta name=\"author\" content=\"Hugo van Schalkwyk\">

  <script type=\"text/javascript\" src=\"https://cdn.jsdelivr.net/jquery/latest/jquery.min.js\"></script>
  <script type=\"text/javascript\" src=\"https://cdn.jsdelivr.net/momentjs/latest/moment.min.js\"></script>
  <script type=\"text/javascript\" src=\"https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js\"></script>
  
  <link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css\" />
  <link rel=\"stylesheet\" type=\"text/css\" href=\"css\styles.css\" />
  
  <script type=\"text/javascript\">
      function showDate(divID){
       document.getElementById(divID).style.display='inline';   
      }
      
      function submitForm(divID){
        document.getElementById(divID).submit();
      }
  </script>

</head>
";

    return $ret_val;
    
}

function printError($error_message){
    if($error_message !== ""){
        $ret_val = "<h3 class='error'>";
        $ret_val .= $error_message;
        $ret_val .= "</h3>";
        return $ret_val;
    }
}

function printSearchForm($decoded){
    $ret_val = "";
    
    $ret_val .= "<form method='post' id='search_unit' >";
    $ret_val .= printUnitList($decoded);
    $ret_val .= "<br>";
    $ret_val .= printDateRange();
    $ret_val .= "<br>";
    $ret_val .= "<input type='submit' value='Submit'>";
    $ret_val .= "</form>";
    $ret_val .= "<a href='/Gitlab/php_test/iTrackMap.php?N=1'>Reset</a>";
    
    
    return $ret_val;
}

function printUnitList($decoded){
    $ret_val = "";
    $ret_val .= "<label for='unit_list_set'>Select a vehicle: </label>";
    
    
    $ret_val .= "<select name='unit_list' id='unit_list_set' onchange=\"showDate('daterange')\" >";
    $ret_val .= "<option value=''> - select -</option>";   
    foreach($decoded as $car){
        $ret_val .= "<option value='";
        $ret_val .=  $car->UID;
        $ret_val .=  "' > [";
        $ret_val .= $car->Registration;
        $ret_val .= "] ";
        $ret_val .= $car->Name;
        $ret_val .= "</option>";
    }
    $ret_val .= "</select>";
    
    
    return $ret_val;    

    }
    
function printDateRange(){
    //find details of the date picker here: 
    //http://www.daterangepicker.com/    
    $ret_val = "";

    $ret_val .= "<input type='text' id='daterange' name='daterange' style='width: 300px; display:none' autocomplete='off'   />";
    $ret_val .= "<script>
$(function() {
  $('input[name=\"daterange\"]').daterangepicker({
    timePicker: true,
    startDate: moment().startOf('hour').subtract(24, 'hour'), 
    endDate: moment().startOf('hour'),
    locale: {
      format: 'YYYY/MM/DD hh:mm:ss '
    }
  });
});
</script>";
    
    return $ret_val;
    }

function getColor($color_count){
    //this function will return the next number in the list up to 10 and start 
    //again
    $color_array = array("#4B0082", "#3F6826", "#38B0DE", "#330000", "#55141C", "#5EDA9E", "#660000", "#6959CD", "#7B3F00", "#800000");
    $new_count = $color_count % 10 ;
    return $color_array[$new_count];
}    
    
function printTrips($trips_array){
    
    $ret_val = "";
    if($trips_array !==[]){
        $color_count = 0;
        $ret_val .= "
<table>
<tr>
<th></th>
<th>Start</th>
<th>End</th>
<th>Time</th>
<th>Distance</th>
</tr>

";
        foreach ($trips_array as $trip) {
            $ret_val .= "<tr>";
            $ret_val .= "<td class='white' style='background-color:".getColor($color_count).";'>".$color_count."</td>";
            $ret_val .= "<td>".$trip->Start."</td>";
            $ret_val .= "<td>".$trip->End."</td>";
            $ret_val .= "<td>".$trip->Time."</td>";
            $ret_val .= "<td>".$trip->Distance."</td>";
            $ret_val .= "</tr>";

            $color_count += 1;
        }
        
        $ret_val .= "</table>";
        return $ret_val;
    }
}    
 
function drawTrips($trips_array){
    $ret_val = "";
    $color_count = 0;
    
    $ret_val .= "
    // Define a symbol using a predefined path (an arrow)
    // supplied by the Google Maps JavaScript API.
    var lineSymbol = {
      path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
    };
    ";
    
    foreach($trips_array as $key => $trip){
        $ret_val .= "var tripCoordinates".$key." = [";
        $ret_val .= "{lat: ".$trip->StartPosition->Lat.", lng: ".$trip->StartPosition->Lon."},";
        $ret_val .= "{lat: ".$trip->EndPosition->Lat.", lng: ".$trip->EndPosition->Lon."}";
        $ret_val .= "];";
        
        $ret_val .= "var tripPath".$key." = new google.maps.Polyline({
            path: tripCoordinates".$key.",
            icons: [{
                icon: lineSymbol,
                offset: '100%'
              }],
            geodesic: true,
            strokeColor: '".getColor($color_count)."',
            strokeOpacity: 1.0,
            strokeWeight: 2
          });";
        
        $ret_val .= "
    var infoWindow = new google.maps.InfoWindow();
    google.maps.event.addListener(tripPath".$key.", 'mouseover', function (e) {
        infoWindow.setContent('Trip ".$key."');
        var latLng = e.latLng;
        infoWindow.setPosition(latLng);
        infoWindow.open(map);
    });";
        
        $color_count += 1;
        $ret_val .= "tripPath".$key.".setMap(map);";
    }
    return $ret_val;
}

function printTripsMap($trips_array){
    $ret_val = "";
    $ret_val .= "
    <div style='width:500px; height:500px;'>    
    <div id=\"map\"></div>
    <script>
      var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -34, lng: 19},
          zoom: 8
        });";
    $ret_val .= drawTrips($trips_array);
    $ret_val .= "
    }
    </script>
    <script src=\"https://maps.googleapis.com/maps/api/js?key=GOOGLE_APIKEY\" 
    async defer></script>";
    
    return $ret_val;
}

function printFooter(){
        $ret_val = "
    <script type=\"text/javascript\" src=\"js/base.js\"></script>
</html>";

    return $ret_val;
  
    }
?>




