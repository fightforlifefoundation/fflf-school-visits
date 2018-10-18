<?php
/*
Plugin Name: FFLF School Visits
*/

global $fflf_sv_db_version;
$fflf_sv_db_version='0.1';

global $wpdb;
global $table_name;
$table_name = $wpdb->prefix . "fflfSchoolVisits";

function fflf_sv_install() {
    global $wpdb;
    global $fflf_sv_db_version;
    global $table_name;   

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
    global $wpdb, $table_name;

    $return_arr = array();
    $unclaimedVisits = $wpdb->get_results("
        SELECT *
        FROM $table_name
        WHERE claimed=0;");
    foreach($unclaimedVisits as $visit){
        array_push($return_arr, array(
            'eventId' => $visit->eventId
        ));
    }

    return $return_arr;
    //return json_encode($return_arr);
}

function create_unclaimed_visit(){
    global $wpdb, $table_name;
    $schoolName = 'dummy school name';
    $className = 'dummy class name';
    $grade = '3-4';
    $startDate = '2018-11-01 10:00';
    $endDate = '2018-11-01 15:00';
    $schoolAddress = '123 main street';
    $schoolCity = 'indianapolis';
    $schoolState='IN';
    $schoolZip='46203';

    $wpdb->insert(
        $table_name,
        array('schoolName' => $schoolName,
            'className' => $className,
            'grade'=>$grade,
            'startDate'=>$startDate,
            'endDate'=>$endDate,
            'schoolAddress'=>$schoolAddress,
            'schoolCity'=>$schoolCity,
            'schoolState'=>$schoolState,
            'schoolZip'=>$schoolZip),
        array(
            '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'
        )
    );
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
    ));
    register_rest_route( 'fflf-school-visits', '/visits', array(
        'methods' => 'POST',
        'callback' => 'create_unclaimed_visit',
    ));
  } );
?>