<?php

/**
 * Theme helpers.
 */

namespace App;

add_action( 'gform_pre_submission_4', function ( $form ) {

    //WARRANTY FORM
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

    $state = rgpost('input_9');
    $location = getEmailLocation($state);
  
    $_POST['input_23'] = $location['location'];
    $_POST['input_24'] = $location['email'];

    //If within last 3 years then generate label and not a battery
    if($sn_year >= $curr_year - 3 && $part !== 'Battery') {
        $_POST['input_15'] = 'Yes';
        
        //Check for biz name but if empty set to NA
        $bus_name = rgpost('input_17');

        if(empty($bus_name)) {
            $bus_name = 'N/A';
        }

        //GET LOCATION INFO
        $state = rgpost('input_9');
        $args = array(
          'post_type' => 'location',
          'post_status' => 'publish',
          'posts_per_page' => '1',
          'meta_query' => array(
              array(
                  'key' => 'service_states',
                  'value' => '"'.$state.'"',
                  'compare' => 'LIKE'
              )
          )
        );

        $posts = new \WP_Query($args);

        //$id = $posts->posts[0]->ID;
        
        $location = [];
        while($posts->have_posts()): $posts->the_post();
        
            $id = get_the_ID();

            $location[] = [
                'title' => get_the_title(),
                'loc' => get_field('map', $id),
            ];

        endwhile;
        wp_reset_query();

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
                "name": "' . $location[0]['title'] .'",
                "address_line1": "'. $location[0]['loc']['name'] .'",
                "city_locality": "'. $location[0]['loc']['city'] .'",
                "state_province": "'. $location[0]['loc']['state_short'] .'",
                "postal_code": "'. $location[0]['loc']['post_code'] .'",
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
              'API-Key: KBFAfraPG1DgQHoY1cskCMed+Tjw+vyEjpSXT6R4ZRE',
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
  // REPAIR FORM
  $sn = rgpost( 'input_2' );
  $part = getPart($sn);

  $_POST['input_13'] = $part;

  $state = rgpost('input_9');
  $location = getEmailLocation($state);

  $_POST['input_17'] = $location['location'];
  $_POST['input_18'] = $location['email'];
});

add_action( 'gform_pre_submission_6', function ( $form ) {
  // REPAIR FORM
  $sn = rgpost( 'input_2' );
  $part = getPart($sn);

  $_POST['input_13'] = $part;

  $state = rgpost('input_9');
  $location = getEmailLocation($state);

  $_POST['input_17'] = $location['location'];
  $_POST['input_18'] = $location['email'];
});

add_action( 'gform_pre_submission_2', function ( $form ) {
  //SERVICE FORM
  //Get serial number and part name
  $sn = rgpost( 'input_1' );
  $part = getPart($sn);

  $_POST['input_14'] = $part;

  $state = rgpost('input_8');
  $location = getEmailLocation($state);

  $_POST['input_17'] = $location['location'];
  $_POST['input_18'] = $location['email'];

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

function getEmailLocation($state) {

  $args = array(
      'post_type' => 'location',
      'post_status' => 'publish',
      'posts_per_page' => '1',
      'meta_query' => array(
          array(
              'key' => 'service_states',
              'value' => '"'.$state.'"',
              'compare' => 'LIKE'
          )
      )
  );

  $posts = new \WP_Query($args);

  //$id = $posts->posts[0]->ID;
  
  $data = [];
  while($posts->have_posts()): $posts->the_post();
  
      $id = get_the_ID();

      $data[] = [
          'title' => get_the_title(),
          'loc' => get_field('map', $id),
          'phone' => get_field('phone', $id),
          'email' => get_field('email', $id),
      ];

  endwhile;
  wp_reset_query();

  $return = [];

  $return['location'] = '<span style="font-weight: 400;">' . addslashes($data[0]['title']) . '</span><br>'
            . '<span style="font-weight: 400;">' . $data[0]['loc']['name'] . '</span><br>'
            . '<span style="font-weight: 400;">' . $data[0]['loc']['city'] . ' ' . $data[0]['loc']['state_short'] . ', ' .  $data[0]['loc']['post_code'] . '</span><br>'
            . '<br>'
            . (($data[0]['phone']) ? '<span style="font-weight: 400;">Email: </span><a href="tel:' . $data[0]['phone'] . '" style="color: #3498db; text-decoration: underline;"><span style="font-weight: 400;">' . $data[0]['phone'] . '</span></a><br><br>' : '')
            . (($data[0]['email']) ? '<span style="font-weight: 400;">Email: </span><a href="mailto:' . $data[0]['email'] . '" style="color: #3498db; text-decoration: underline;"><span style="font-weight: 400;">' . $data[0]['email'] . '</span></a><br><br>' : '');

  $return['email'] = $data[0]['email'];



  return $return;
}