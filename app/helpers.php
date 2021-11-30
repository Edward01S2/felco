<?php

/**
 * Theme helpers.
 */

namespace App;

add_action( 'gform_pre_submission_4', function ( $form ) {

 
    // Get the date field value.
    $sn_value      = rgpost( 'input_2' );
    $sn_year = substr($sn_value, 0, 2);
    
    $year = date('Y');
    $curr_year = substr($year, 2, 2);

    $part = rgpost('input_1'); //Find what part selected

    //Set warranty to valid if serial is within and battery
    if($sn_year >= $curr_year - 3 && $part == 'Battery') {
      $_POST['input_15'] = 'Yes';
    }

    //If within last 3 years then generate label and not a battery
    if($sn_year >= $curr_year - 3 && $part !== 'Battery') {
        $_POST['input_15'] = 'Yes';
        
        //Check for biz name but if empty set to NA
        $bus_name = rgpost('input_17');

        if(empty($bus_name)) {
            $bus_name = 'N/A';
        }

        //Create curl call and generate link for label.
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.shipengine.com/v1/labels',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
            "shipment": {
              "service_code": "ups_ground",
              "ship_to": {
                "name": "Ambergs Inc",
                "address_line1": "3164 Whitney Rd.",
                "city_locality": "Stanley",
                "state_province": "NY",
                "postal_code": "14561",
                "country_code": "US",
              },
              "ship_from": {
                "name": "' . rgpost('input_4') . ' ' . rgpost('input_5')  .'",
                "company_name": "'. $bus_name .'",
                "phone": "' . rgpost('input_13') . '",
                "address_line1": "' . rgpost('input_6') . '",
                "city_locality": "' . rgpost('input_7') . '",
                "state_province": "' . rgpost('input_9') . '",
                "postal_code": "' . rgpost('input_10') . '",
                "country_code": "US",
              },
              "packages": [
                {
                  "weight": {
                    "value": 20,
                    "unit": "ounce"
                  }
                }
              ]
            }
          }',
            CURLOPT_HTTPHEADER => array(
              'Host: api.shipengine.com',
              'API-Key: TEST_R9DUc7DLQr+hVpAHtgXkcPlxA0+tWYLHqvgMyxcnwkQ',
              'Content-Type: application/json'
            ),
          ));
          
          $response = curl_exec($curl);
          
          curl_close($curl);
          $decode = json_decode($response, true);
          $_POST['input_18'] = $response;
          $_POST['input_16'] = $decode['label_download']['pdf'];
          echo $response;
    }
    
} );

add_action( 'gform_pre_submission_5', function ( $form ) {
  $sn = rgpost( 'input_3' );

  //$sn_part = substr($sn, 2, 2);
  $part = getPart($sn);

  $_POST['input_14'] = $part;

});

function getPart($serial) {
  $sn_part = strtolower(substr($serial, 2, 2));
  //$part = '';

  switch($sn_part) {
    case 'aa':
      return 'F820';
    case 'ba':  
      return 'F801';
  }
}