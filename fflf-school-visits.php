<?php
/*
Plugin Name: FFLF School Visits
*/

//GET wp-json/fflf-school-visits/visits
function get_unclaimed_visits(){
    $return_arr = array();
    $row_array['eventId'] = 1;
    $row_array['schoolName'] = 'dummy school 1';
    $row_array['className'] = 'dummy class 1';
    $row_array['grade'] = '5-6';
    $row_array['startDate'] = '2018-10-27 10:00';
    $row_array['endDate'] = '2018-10-27 15:00';
    $row_array['schoolAddress'] = '123 Main Street';
    $row_array['schoolCity'] = 'Indianapolis';
    $row_array['schoolState'] = 'IN';
    $row_array['schoolZip'] = '46203'; 

    array_push($return_arr, $row_array);

    return $return_arr;
    //return json_encode($return_arr);
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'fflf-school-visits', '/visits', array(
      'methods' => 'GET',
      'callback' => 'get_unclaimed_visits',
    ) );
  } );
?>