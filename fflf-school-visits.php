<?php
/*
Plugin Name: FFLF School Visits
*/

global $fflf_sv_db_version;
$fflf_sv_db_version='0.1';

function fflf_sv_install() {
    global $wpdb;
    global $fflf_sv_db_version;

    $table_name= $wpdb->prefix . "fflfSchoolVisits";

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        eventId INT NOT NULL AUTO_INCREMENT,
        schoolName VARCHAR(1024) NOT NULL,
        className VARCHAR(1024) NOT NULL,
        grade VARCHAR(10) NOT NULL,
        startDate DATETIME NOT NULL,
        endDate DATETIME NOT NULL,
        schoolAddress VARCHAR(1024) NOT NULL,
        schoolCity VARCHAR(1024) NOT NULL,
        schoolState VARCHAR(2) NOT NULL,
        schoolZip VARCHAR(20) NOT NULL,
        claimed TINYINT(1) NOT NULL DEFAULT 0,
        sponsorCompany VARCHAR(1024) NULL,
        sponsorFirstName VARCHAR(1024) NULL,
        sponsorLastName VARCHAR(1024) NULL,
        sponsorEmail VARCHAR(1024) NULL,
        sponsorPhone VARCHAR(50) NULL,  
        sponsorClaimDate DATETIME NULL,      
        PRIMARY KEY  (eventId)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    add_option('fflf_sv_db_version', $fflf_sv_db_version );
}

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

    array_push($return_arr, array(
        'eventId' => 2,
        'schoolName' => 'dummy school 1',
        'className' => 'dummy class 2',
        'grade' => '4',
        'startDate' => '2018-10-27 10:00',
        'endDate' => '2018-10-27 15:00',
        'schoolAddress' => '123 Main Street',
        'schoolCity' => 'Indianapolis',
        'schoolState' => 'IN',
        'schoolZip' => '46203' 
    ));

    array_push($return_arr, array(
        'eventId' => 3,
        'schoolName' => 'dummy school 1',
        'className' => 'dummy class 2',
        'grade' => '4',
        'startDate' => '2018-10-29 10:00',
        'endDate' => '2018-10-29 15:00',
        'schoolAddress' => '123 Main Street',
        'schoolCity' => 'Indianapolis',
        'schoolState' => 'IN',
        'schoolZip' => '46203' 
    ));

    return $return_arr;
    //return json_encode($return_arr);
}

function fflf_sv_update_db_check(){
    global $fflf_sv_db_version;
    if ( get_site_option( 'fflf_sv_db_version' ) != $fflf_sv_db_version ) {
        fflf_sv_install();
    }
}

add_action( 'plugins_loaded', 'fflf_sv_update_db_check' );

add_action( 'rest_api_init', function () {
    register_rest_route( 'fflf-school-visits', '/visits', array(
      'methods' => 'GET',
      'callback' => 'get_unclaimed_visits',
    ) );
  } );
?>