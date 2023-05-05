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

// Get the requested page.
$patient_id = get( 'patient' );
$type = get( 'type', 'patient' );

// Default status
$default_status = 'auto-draft';

// Values to be assigned to a specific Smarty template
$data = array(
  'is_invalid'    => '',
  'receptionist'  => receptionist(),
  'physician'     => physician()
);

$page_name = '';
$save_button_text = '';

// Check to see if the requested page matches any of the following cases.
switch ( $type ) {
  case 'patient' :
    $new_patient_id = 1;
    $new_checkup_id = 1;

    // Cleanup the table from automatically-inserted drafts.
    $pisdb->query( "DELETE FROM $pisdb->patients WHERE patient_status = '$default_status'" );
    $pisdb->query( "DELETE FROM $pisdb->checkups WHERE checkup_status = '$default_status'" );

    // Get the last patient's ID.
    if ( $last_patient_id = $pisdb->get_row( "SELECT patient_id FROM $pisdb->patients ORDER BY patient_id DESC LIMIT 1" ) ) {
      $last_patient_id = $last_patient_id->patient_id;
    }

    // Get the last checkup's ID.
    if ( $checkup_id = $pisdb->get_row( "SELECT checkup_id FROM $pisdb->checkups ORDER BY checkup_id DESC LIMIT 1" ) ) {
      $checkup_id = $checkup_id->checkup_id;
    }

    // Increment the last ID and order values by one.
    $new_patient_id = ( ! isset( $last_patient_id ) ) ? $new_patient_id : $last_patient_id + 1;
    $new_checkup_id = ( ! isset( $checkup_id ) ) ? $new_checkup_id : $checkup_id + 1;

    // Automatically insert draft with new ID and order values.
    $pisdb->query( "ALTER TABLE $pisdb->patients AUTO_INCREMENT = $new_patient_id" );
    $pisdb->query( "INSERT INTO $pisdb->patients (patient_id, patient_status) VALUES ($new_patient_id, '$default_status')" );
    $pisdb->query( "ALTER TABLE $pisdb->checkups AUTO_INCREMENT = $new_checkup_id" );
    $pisdb->query( "INSERT INTO $pisdb->checkups (checkup_id, checkup_patient_id, checkup_status) VALUES ($new_checkup_id, $new_patient_id, '$default_status')" );

    // Values to be assigned to a specific Smarty template
    $page_name = 'Add New Patient';
    $save_button_text = 'Add';
    $data = array_merge( $data, array(
        'app_title' => app_title( $page_name ),
        'page_title' => page_title( $page_name ),
        'patient'    => array(
          'id'                     => $new_patient_id,
          'first_name'             => '',
          'last_name'              => '',
          'middle_name'            => '',
          'sex_options'            => sex_options(),
          'birthdate'              => date( 'Y-m-d' ),
          'civil_status_options'   => civil_status_options(),
          'street_address'         => '',
          'town_or_city'           => '',
          'province_or_state'      => '',
          'postal_code'            => '',
          'country'                => '',
          'phone'                  => '',
          'emergency_first_name'   => '',
          'emergency_last_name'    => '',
          'emergency_phone'        => '',
          'emergency_relationship' => '',
          'status'                 => $default_status,
          'returning_patient'      => false
        ),
        'checkup'    => array(
          'symptoms'         => '',
          'blood_pressure'   => '',
          'body_temperature' => '',
          'weight'           => '',
          'height'           => '',
          'pulse_rate'       => '',
          'blood_type'       => '',
          'status'           => $default_status
        ),
        'action_url' => app_home() . "post.php?action=edit&patient=$new_patient_id&previous=" . urlencode( $_SERVER['REQUEST_URI'] ),
        'trash_link' => '',
        'save_button_text' => $save_button_text
      )
    );

    // Smarty template
    $template = "post";
    break;
  case 'checkup' :
    if ( empty( $patient_id ) ) {
      // header( 'Location: ' . app_home() );
      redirect( app_home() );
      die();
    }

    $previous = isset( $_GET['previous'] ) ? $_GET['previous'] : '';
    $new_checkup_id = 1;

    // Cleanup the table from automatically-inserted drafts.
    $pisdb->query( "DELETE FROM $pisdb->checkups WHERE checkup_status = '$default_status'" );

    // Get the last checkup's ID.
    if ( $checkup_id = $pisdb->get_row( "SELECT checkup_id FROM $pisdb->checkups ORDER BY checkup_id DESC LIMIT 1" ) ) {
      $checkup_id = $checkup_id->checkup_id;
    }

    // Increment the last ID and order values by one.
    $new_checkup_id = ( ! isset( $checkup_id ) ) ? $new_checkup_id : $checkup_id + 1;

    // Automatically insert draft with new ID and order values.
    $pisdb->query( "ALTER TABLE $pisdb->checkups AUTO_INCREMENT = $new_checkup_id" );
    $pisdb->query( "INSERT INTO $pisdb->checkups (checkup_id, checkup_patient_id, checkup_status) VALUES ($new_checkup_id, $patient_id, '$default_status')" );

    // 
    $patient = $pisdb->get_row( "SELECT patient_first_name, patient_last_name, patient_middle_name FROM $pisdb->patients WHERE patient_id = $patient_id" );

    // Values to be assigned to a specific Smarty template
    $page_name = 'Check Up';
    $save_button_text = 'Add to Checkups';

    // Get the last checkup's blood type.
    if ( $checkup_blood_type = $pisdb->get_row( "SELECT checkup_blood_type FROM $pisdb->checkups WHERE checkup_patient_id = $patient_id ORDER BY checkup_patient_id ASC LIMIT 1" ) ) {
      $checkup_blood_type = $checkup_blood_type->checkup_blood_type;
    }

    $data = array_merge( $data, array(
        'app_title' => app_title( $page_name ),
        'page_title' => page_title( back_button( $previous ) . $page_name ),
        'action'     => '',
        'patient'    => array(
          'id'          => $patient_id,
          'first_name'  => $patient->patient_first_name,
          'middle_name' => $patient->patient_middle_name,
          'last_name'   => $patient->patient_last_name,
          'name' => $patient->patient_last_name . ', ' . $patient->patient_first_name . ' ' . middle_initial( $patient->patient_middle_name)
        ),
        'checkup'    => array(
          'id'               => $new_checkup_id,
          'patient_id'       => $patient_id,
          'symptoms'         => '',
          'blood_pressure'   => '',
          'body_temperature' => '',
          'weight'           => '',
          'height'           => '',
          'pulse_rate'       => '',
          'blood_type'       => $checkup_blood_type,
          'medications'      => '',
          'findings'         => '',
          'status'           => $default_status
        ),
        'action_url' => app_home() . "post.php?action=edit&checkup=$new_checkup_id" . ( ! empty( $previous ) ? '&previous=' . urlencode( $previous ) : '' ),
        'trash_link' => '',
        'print_button' => '',
        'save_button_text' => $save_button_text
      )
    );

    // Smarty template
    $template = "post-checkup";
    break;
  default :
    redirect( app_home() );
    die();
    break;
}

//
$_SESSION['page_name'] = $page_name;
$_SESSION['save_button_text'] = $save_button_text;

// Success/alert message from admin/post.php
if ( isset( $_SESSION['alert'] ) ) {
  // Values to be assigned to a specific Smarty template
  $data['alert'] = $_SESSION['alert'];
  unset( $_SESSION['alert'] );
}

// view() is located in includes/smarty_template.class.php
$tpl->view( $template, $data );
