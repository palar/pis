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

$type = get( 'type', 'patient' );
$search_query = get( 'search_query' );
$page = get( 'page' );
$patient_id = get( 'patient' );
$previous = get( 'previous' );

// Values to be assigned to a specific Smarty template
$data = array(
  'search_query' => $search_query
);

// Check to see if the requested type matches any of the following cases.
switch ( $type ) {
  case 'patient' :
    // Values to be assigned to a specific Smarty template
    $data = array_merge( $data, array(
        'app_title'        => app_title( 'Patients' ),
        'page_title'       => page_title( 'Patients' ),
        'page_description' => '<p class="lead"></p>',
        'patients'         => array(),
        'add_new_patient'  => app_home() . 'add.php'
      )
    );

    $query = '';
    if ( ! empty( $search_query ) ) {
      $query = "patient_first_name LIKE '%$search_query%' OR patient_middle_name LIKE '%$search_query%' OR patient_last_name LIKE '%$search_query%' AND ";
      $data['search_query'] = $search_query;
    }

    // $patients_per_page = get_setting( 'patients_per_page' );
    $patients_per_page = '';

    if ( $patients = $pisdb->get_rows( "SELECT * FROM $pisdb->patients WHERE " . $query . "patient_status = 'published' ORDER BY patient_id ASC" . ( ! empty( $patients_per_page ) ? ' LIMIT ' . intval( $patients_per_page ) : '' ) ) ) {
      foreach ( $patients as $patient ) {
        $incomplete_checkup = false;
        if ( $pisdb->get_row( "SELECT checkup_id FROM $pisdb->checkups WHERE checkup_patient_id = " . $patient['patient_id'] . " AND checkup_status = 'draft' ORDER BY checkup_id DESC LIMIT 1" ) ) {
          $incomplete_checkup = true;
        }

        $last_checked_up = '';
        if ( $checked_up = $pisdb->get_row( "SELECT checkup_date FROM $pisdb->checkups WHERE checkup_patient_id = " . $patient['patient_id'] . " AND checkup_status = 'published' ORDER BY checkup_id DESC LIMIT 1" ) ) {
          $last_checked_up = $checked_up->checkup_date;
        }

        array_push( $data['patients'], array(
            'name'               => $patient['patient_last_name'] . ', ' . $patient['patient_first_name'] . ' ' . middle_initial( $patient['patient_middle_name'] ),
            'last_checked_up'    => $last_checked_up ? date_format( date_create( $last_checked_up ), "F j, Y" ) : '',
            'added_on'           => date_format( date_create( $patient['patient_date'] ), "F j, Y" ),
            'edit_url'           => app_home() . "post.php?action=edit&$type=" . $patient['patient_id'] . "&previous=" . urlencode( $_SERVER['REQUEST_URI'] ),
            'check_up_url'       => app_home() . "add.php?type=checkup&$type=" . $patient['patient_id'] . "&previous=" . urlencode( $_SERVER['REQUEST_URI'] ),
            'incomplete_checkup' => $incomplete_checkup,
            'history_url'        => app_home() . "edit.php?type=checkup&page=history&patient=" . $patient['patient_id'] . "&previous=" . urlencode( $_SERVER['REQUEST_URI'] ),
          )
        );
      }
    }

    // Smarty template
    $template = "edit";
    break;
  case 'checkup' :
    if ( 'history' === $page ) {
      if ( ! empty( $patient_id ) ) {
        $patient = $pisdb->get_row( "SELECT patient_last_name, patient_first_name, patient_middle_name FROM $pisdb->patients WHERE patient_id = $patient_id" );

        // Values to be assigned to a specific Smarty template
        $data = array_merge( $data, array(
            'app_title'        => app_title( 'History &raquo; ' . $patient->patient_first_name . ' ' . middle_initial( $patient->patient_middle_name ) . ' ' . $patient->patient_last_name ),
            'page_title'       => page_title( back_button( $previous ) . 'History' ),
            'name'             => $patient->patient_first_name . ' ' . middle_initial( $patient->patient_middle_name ) . ' ' . $patient->patient_last_name,
            'page_description' => '<p class="lead">This is the medical history of <strong>' . $patient->patient_first_name . ' ' . middle_initial( $patient->patient_middle_name ) . ' ' . $patient->patient_last_name . '</strong>.</p>',
            'checkups'         => array()
          )
        );

        if ( $checkups = $pisdb->get_rows( "SELECT * FROM $pisdb->checkups WHERE checkup_status = 'published' AND checkup_patient_id = $patient_id ORDER BY checkup_date DESC" ) ) {
          foreach ( $checkups as $checkup ) {
            array_push( $data['checkups'], array(
                'id'          => $checkup['checkup_id'],
                'checkup_url' => app_home() . 'post.php?action=edit&checkup=' . $checkup['checkup_id'] . '&previous=' . urlencode( $_SERVER['REQUEST_URI'] ),
                'date'        => date_format( date_create( $checkup['checkup_date'] ), "F j, Y" )
              )
            );
          }
        }

        // Smarty template
        $template = "edit-$page-patient";
      } else {
        // Values to be assigned to a specific Smarty template
        $data = array_merge( $data, array(
            'app_title'        => app_title( 'History' ),
            'page_title'       => page_title( 'History' ),
            'page_description' => '<p class="lead">Medical history of all patients</p>',
            'checkups'         => array()
          )
        );

        $history = [];
        if ( $checkups = $pisdb->get_rows( "SELECT * FROM $pisdb->checkups WHERE checkup_status = 'published' ORDER BY checkup_date DESC" ) ) {
          foreach ( $checkups as $checkup ) {
            $patient = $pisdb->get_row( "SELECT patient_last_name, patient_first_name, patient_middle_name FROM $pisdb->patients WHERE patient_id = " . $checkup['checkup_patient_id'] );
            $date = date_format( date_create( $checkup['checkup_date'] ), "F j, Y" );

            if ( ! array_key_exists( $date, $history ) ) {
              $history[$date] = [];
            }

            $history[$date][] = array(
                'name'        => $patient->patient_last_name . ', ' . $patient->patient_first_name . ' ' . middle_initial( $patient->patient_middle_name ),
                'history_url' => app_home() . "edit.php?type=$type&page=history&patient=" . $checkup['checkup_patient_id'] . "&previous=" . urlencode( $_SERVER['REQUEST_URI'] ),
                'time'        => date_format( date_create( $checkup['checkup_date'] ), "g:i A" ),
                'checkup_url' => app_home() . "post.php?action=edit&$type=" . $checkup['checkup_id'] . "&previous=" . urlencode( $_SERVER['REQUEST_URI'] )
              );
          }
          array_push( $data['checkups'], $history );
        }

        // Smarty template
        $template = "edit-history";
      }
    } else {
      // Values to be assigned to a specific Smarty template
      $data = array_merge( $data, array(
          'app_title'        => app_title( 'Checkups' ),
          'page_title'       => page_title( 'Checkups' ),
          'page_description' => '<p class="lead">The following patients are awaiting checkups.</p>',
          'checkups'         => array()
        )
      );

      if ( $checkups = $pisdb->get_rows( "SELECT * FROM $pisdb->checkups WHERE checkup_status = 'draft' ORDER BY checkup_id ASC" ) ) {
        foreach ( $checkups as $checkup ) {
          $patient = $pisdb->get_row( "SELECT patient_last_name, patient_first_name, patient_middle_name FROM $pisdb->patients WHERE patient_id = " . $checkup['checkup_patient_id'] );

          $last_checked_up = '';
          if ( $checked_up = $pisdb->get_row( "SELECT checkup_date FROM $pisdb->checkups WHERE checkup_patient_id = " . $checkup['checkup_patient_id'] . " AND checkup_status = 'published' ORDER BY checkup_date DESC LIMIT 1" ) ) {
            $last_checked_up = $checked_up->checkup_date;
          }

          array_push( $data['checkups'], array(
              'name'            => $patient->patient_last_name . ', ' . $patient->patient_first_name . ' ' . middle_initial( $patient->patient_middle_name ),
              'last_checked_up' => $last_checked_up ? date_format( date_create( $last_checked_up ), "F j, Y" ) : '',
              'checkup_url'     => app_home() . "post.php?action=edit&$type=" . $checkup['checkup_id'] . "&previous=" . urlencode( $_SERVER['REQUEST_URI'] ),
              'edit_url'        => app_home() . "post.php?action=edit&patient=" . $checkup['checkup_patient_id'] . "&previous=" . urlencode( $_SERVER['REQUEST_URI'] ),
              'history_url'     => app_home() . "edit.php?type=$type&page=history&patient=" . $checkup['checkup_patient_id'] . "&previous=" . urlencode( $_SERVER['REQUEST_URI'] )
            )
          );
        }
      }

      // Smarty template
      $template = "edit-$type";
    }

    break;
  default :
    redirect( app_home() );
    die();
    break;
}

// Success/alert message from admin/post.php
if ( isset( $_SESSION['alert'] ) ) {
  // Values to be assigned to a specific Smarty template
  $data['alert'] = $_SESSION['alert'];
  unset( $_SESSION['alert'] );
}

// view() is located in includes/smarty_template.class.php
$tpl->view( $template, $data );
