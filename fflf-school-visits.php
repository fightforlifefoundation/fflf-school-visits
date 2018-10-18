<?php
/*
Plugin Name: FFLF School Visits
*/

//GET wp-json/fflf-school-visits/visits
function get_unclaimed_visits(){
    $return_arr = array();
    
    array_push($return_arr, array(
        'eventId' => 1,
        'schoolName' => 'dummy school 1',
        'className' => 'dummy class 1',
        'grade' => '5-6',
        'startDate' => '2018-10-27 10:00',
        'endDate' => '2018-10-27 15:00',
        'schoolAddress' => '123 Main Street',
        'schoolCity' => 'Indianapolis',
        'schoolState' => 'IN',
        'schoolZip' => '46203' 
    ));

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