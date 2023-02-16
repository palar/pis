<?php

/**
 * @package PIS
 */

/** Load the PIS environment. */
require_once( __DIR__ . '/pis-load.php' );

if ( ! logged_in() ) {
  // header( 'Location: ' . app_home() );
  redirect( app_home() );
  die();
}

// Values to be assigned to a specific Smarty template
$data = array(
  'app_title'        => app_title( 'Trash' ),
  'page_title'       => page_title( 'Trash' ),
  'page_description' => '',
  'patients'         => array(),
  'checkups'         => array()
);

// Patients
if ( $patients = $pisdb->get_rows( "SELECT patient_id, patient_first_name, patient_middle_name, patient_last_name FROM $pisdb->patients WHERE patient_status = 'trash' ORDER BY patient_id ASC" ) ) {
  foreach ( $patients as $patient ) {
    $incomplete_checkup = false;
    if ( $pisdb->get_row( "SELECT checkup_id FROM $pisdb->checkups WHERE checkup_patient_id = " . $patient['patient_id'] . " AND checkup_status = 'draft' ORDER BY checkup_id DESC LIMIT 1" ) ) {
      $incomplete_checkup = true;
    }

    array_push( $data['patients'], array(
        'name'        => $patient['patient_last_name'] . ', ' . $patient['patient_first_name'] . ' ' . middle_initial( $patient['patient_middle_name'] ),
        'restore_url' => app_home() . "post.php?action=restore&patient=" . $patient['patient_id'] . '&next=' . urlencode( $_SERVER['REQUEST_URI'] ),
        'delete_url'  => app_home() . "post.php?action=delete&patient=" . $patient['patient_id'] . '&next=' . urlencode( $_SERVER['REQUEST_URI'] )
      )
    );
  }
}

// Checkups
if ( $checkups = $pisdb->get_rows( "SELECT * FROM $pisdb->checkups WHERE checkup_status = 'trash' ORDER BY checkup_id ASC" ) ) {
  foreach ( $checkups as $checkup ) {
    $patient = $pisdb->get_row( "SELECT patient_last_name, patient_first_name, patient_middle_name FROM $pisdb->patients WHERE patient_id = " . $checkup['checkup_patient_id'] );

    array_push( $data['checkups'], array(
        'name'        => $patient->patient_last_name . ', ' . $patient->patient_first_name . ' ' . middle_initial( $patient->patient_middle_name ),
        'restore_url' => app_home() . "post.php?action=restore&checkup=" . $checkup['checkup_id'] . '&next=' . urlencode( $_SERVER['REQUEST_URI'] ),
        'delete_url'  => app_home() . "post.php?action=delete&checkup=" . $checkup['checkup_id'] . '&next=' . urlencode( $_SERVER['REQUEST_URI'] )
      )
    );
  }
}

// Success/alert message from admin/post.php
if ( isset( $_SESSION['alert'] ) ) {
  // Values to be assigned to a specific Smarty template
  $data['alert'] = $_SESSION['alert'];
  unset( $_SESSION['alert'] );
}

// view() is located in includes/smarty_template.class.php
$tpl->view( 'trash', $data );
