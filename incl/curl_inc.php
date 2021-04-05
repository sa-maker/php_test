<?php

function getUnitList(){
    
    $service_url = 'http://testserver'; // this will fail
    $curl = curl_init($service_url);
    
    $curl_post_data = array(
            'auth' => 'auth_code' // this will fail
    );

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);

    $curl_response = curl_exec($curl);
    
    curl_close($curl);
    //there is no error status sent from the API. IF there was we would have to 
    //catch it
    $decoded = json_decode($curl_response);
    return $decoded;
}

function getTripList( $uid, $from, $to ){
    
    $service_url = 'http://testserver/test/trips'; // this will fail
    $curl = curl_init($service_url);
    
    $curl_post_data = array(
        'auth' => 'auth_code', // this will fail
        'uid' => $uid,
        'from' => $from,
        'to' => $to    
    );

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);

    $curl_response = curl_exec($curl);
    
    curl_close($curl);
    
    //there is no error status sent from the API. IF there was we would have to 
    //catch it
    $decoded = json_decode($curl_response);
    return $decoded;
}

?>

