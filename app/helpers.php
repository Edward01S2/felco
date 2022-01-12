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

    $part_name = getPart($sn_value);
    $_POST['input_19'] = $part_name;

    if($part == 'Handpiece') {
      $weight = 40;
    }
    elseif($part == 'Backpack') {
      $weight = 56;
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
                    "value": '. $weight .',
                    "unit": "ounce"
                  }
                }
              ]
            }
          }',
            CURLOPT_HTTPHEADER => array(
              'Host: api.shipengine.com',
              'API-Key: TEST_OmLETxBpn1VJtunKSLrfcPc7kGwxeKKvaJnMgK+MJdE',
              'Content-Type: application/json'
            ),
          ));
          
          $response = curl_exec($curl);
          
          curl_close($curl);
          $decode = json_decode($response, true);
          $_POST['input_18'] = $response;
          $_POST['input_16'] = $decode['label_download']['pdf'];
          //echo $response;
    }
    
} );

add_action( 'gform_pre_submission_5', function ( $form ) {
  $sn = rgpost( 'input_3' );

  //$sn_part = substr($sn, 2, 2);
  $part = getPart($sn);

  $_POST['input_14'] = $part;

});

add_action( 'gform_pre_submission_3', function ( $form ) {
  $sn = rgpost( 'input_2' );
  $part = getPart($sn);

  $_POST['input_13'] = $part;
});

add_action( 'gform_pre_submission_2', function ( $form ) {
  //Get serial number and part name
  $sn = rgpost( 'input_1' );
  $part = getPart($sn);

  $_POST['input_14'] = $part;

  //Get service level
  $level = rgpost('input_2');

  $check = ['F802', 'F801', 'F812', 'F801G', 'F801M', 'F802G', 'F811'];
  $check2 = ['F822', 'F820'];

  //$_POST['input_15'] = $level;

  if($level == 'Basic') {

    if(in_array($part, $check)) {
      $price = '60.00';
      $_POST['input_15'] = $price;
    }

    if(in_array($part, $check2)) {
      $price = '70.00';
      $_POST['input_15'] = $price;
    }
  }
  elseif($level == 'Regular') {

    if(in_array($part, $check)) {
      $price = '100.00';
      $_POST['input_15'] = $price;
    }

    if(in_array($part, $check2)) {
      $price = '130.00';
      $_POST['input_15'] = $price;
    }

  }
  elseif($level == 'Premium') {

    if(in_array($part, $check)) {
      $price = '150.00';
      $_POST['input_15'] = $price;
    }

    if(in_array($part, $check2)) {
      $price = '200.00';
      $_POST['input_15'] = $price;
    }

  }
});

function getPart($serial) {
  $sn_part = strtolower(substr($serial, 2, 2));
  //$part = '';

  switch($sn_part) {
    case 'aa':
      return 'F820';
    case 'ba':  
      return 'F801';
    case 'ca':
      return 'F801G';
    case 'da':
      return 'None';
    case 'fa':
      return 'F820';
    case 'ga':
      return 'F811';
    case 'ma':
      return 'F801M';
    case 'ah':
      return 'F820';
    case 'bh':
      return 'F801';
    case 'gh':
      return 'F811';
    case 'oh':
      return 'F802';
    case 'ph':
      return 'F802G';
    case 'qh':
      return 'F822';
    case 'rh':
      return 'F812';
    case 'ae':
      return 'F880';
    case 'oe':
      return 'F882';
    case 'ab':
      return '880/192';
    case 'bb':
      return '880/193';
    case 'cb':
      return '880/194';
    case 'db':
      return '880/195';
    default:
      return 'NA';
  }
}