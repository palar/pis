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

// Set the parameters.
$action = get( 'action', 'edit' );
$type = 'patient';
if ( ! empty( get( 'checkup' ) ) ) {
  $type = 'checkup';
}
$previous = get( 'previous' );
$next = get( 'next' );
$id = get( $type );
$print = get( 'print' );

// Alert
$alert = '';

// 
$is_invalid = '';

// Values to be assigned to a specific Smarty template
$data = array(
  'receptionist'  => receptionist(),
  'physician'     => physician()
);

// Check to see if the requested action matches any of the following cases.
switch ( $action ) {
  case 'edit' :
  case 'print' :
    switch ( $type ) {
      case 'patient' :
        //
        $page_name = 'Editing Patient';
        $save_button_text = 'Update';

        // Fired when "Save changes" button is pressed.
        if ( isset( $_POST['save_changes'] ) ) {
          unset( $_POST['save_changes'] );

          // Save changes to the currently edited patient if first name and last name are not empty.
          if ( ! empty( $_POST['patient_first_name'] ) || ! empty( $_POST['patient_last_name'] ) ) {
            $old_patient_status = '';
            if ( $patient = $pisdb->get_row( "SELECT * FROM $pisdb->patients WHERE patient_id = $id" ) ) {
              $old_patient_status = $patient->patient_status;
              $patient_status = $patient->patient_status;
              if ( 'trash' !== $patient_status ) {
                $patient_status = 'published';
              }
            }

            $patient_first_name             = $pisdb->real_escape_string( $_POST['patient_first_name'] );
            $patient_last_name              = $pisdb->real_escape_string( $_POST['patient_last_name'] );
            $patient_middle_name            = $pisdb->real_escape_string( $_POST['patient_middle_name'] );
            $patient_sex                    = $pisdb->real_escape_string( $_POST['patient_sex'] );
            $patient_birthdate              = $pisdb->real_escape_string( $_POST['patient_birthdate'] );
            $patient_civil_status           = $pisdb->real_escape_string( $_POST['patient_civil_status'] );
            $patient_street_address         = $pisdb->real_escape_string( $_POST['patient_street_address'] );
            $patient_town_or_city           = $pisdb->real_escape_string( $_POST['patient_town_or_city'] );
            $patient_province_or_state      = $pisdb->real_escape_string( $_POST['patient_province_or_state'] );
            $patient_postal_code            = $pisdb->real_escape_string( $_POST['patient_postal_code'] );
            $patient_country                = $pisdb->real_escape_string( $_POST['patient_country'] );
            $patient_phone                  = $pisdb->real_escape_string( $_POST['patient_phone'] );
            $patient_emergency_first_name   = $pisdb->real_escape_string( $_POST['patient_emergency_first_name'] );
            $patient_emergency_last_name    = $pisdb->real_escape_string( $_POST['patient_emergency_last_name'] );
            $patient_emergency_phone        = $pisdb->real_escape_string( $_POST['patient_emergency_phone'] );
            $patient_emergency_relationship = $pisdb->real_escape_string( $_POST['patient_emergency_relationship'] );

            // New patient
            if ( $patient_date = $pisdb->get_row( "SELECT patient_date FROM $pisdb->patients WHERE patient_id = $id" ) ) {
              if ( 'published' === $patient_status && NULL === $patient_date->patient_date ) {
                $new_patient = true;
              }
            }

            if ( $pisdb->query( "UPDATE $pisdb->patients SET patient_first_name = '$patient_first_name', patient_last_name = '$patient_last_name', patient_middle_name = '$patient_middle_name', patient_sex = '$patient_sex', patient_date_of_birth = '$patient_birthdate', patient_civil_status = '$patient_civil_status', patient_street_address = '$patient_street_address', patient_town_or_city = '$patient_town_or_city', patient_province_or_state = '$patient_province_or_state', patient_postal_code = '$patient_postal_code', patient_country = '$patient_country', patient_phone = '$patient_phone', patient_emergency_first_name = '$patient_emergency_first_name', patient_emergency_last_name = '$patient_emergency_last_name', patient_emergency_phone = '$patient_emergency_phone', patient_emergency_relationship = '$patient_emergency_relationship', patient_status = '$patient_status'" . ( isset( $new_patient ) && true === $new_patient ? ", patient_date = " . now() . ", patient_date_gmt = " . now( 'UTC' ) : "" ) . ( 'published' === $patient_status ? ", patient_update = " . now() . ", patient_update_gmt = " . now( 'UTC' ) : "" ) . " WHERE patient_id = $id" ) ) {
              if ( $checkup = $pisdb->get_row( "SELECT * FROM $pisdb->checkups WHERE checkup_patient_id = $id AND checkup_status != 'published' ORDER BY checkup_id DESC LIMIT 1" ) ) {
                $checkup_status = $checkup->checkup_status;
                if ( 'auto-draft' === $checkup_status ) {
                  $checkup_status = 'draft';
                }

                if ( ! empty( $checkup_status ) && receptionist() ) {
                  $checkup_symptoms         = $pisdb->real_escape_string( $_POST['checkup_symptoms'] );
                  $checkup_blood_pressure   = $pisdb->real_escape_string( $_POST['checkup_blood_pressure'] );
                  $checkup_body_temperature = $pisdb->real_escape_string( $_POST['checkup_body_temperature'] );
                  $checkup_weight           = $pisdb->real_escape_string( $_POST['checkup_weight'] );
                  $checkup_height           = $pisdb->real_escape_string( $_POST['checkup_height'] );
                  $checkup_pulse_rate       = $pisdb->real_escape_string( $_POST['checkup_pulse_rate'] );
                  $checkup_blood_type       = $pisdb->real_escape_string( $_POST['checkup_blood_type'] );

                  $checkup_id = $checkup->checkup_id;
                  $pisdb->query( "UPDATE $pisdb->checkups SET checkup_symptoms = '$checkup_symptoms', checkup_blood_pressure = '$checkup_blood_pressure', checkup_body_temperature = '$checkup_body_temperature', checkup_weight = '$checkup_weight', checkup_height = '$checkup_height', checkup_pulse_rate = '$checkup_pulse_rate', checkup_blood_type = '$checkup_blood_type', checkup_status = '$checkup_status' WHERE checkup_id = $checkup_id" );
                }
              }

              $alert = 'The patient was updated.';
              if ( 'auto-draft' === $old_patient_status ) {
                $alert = 'The new patient was added.';
              }

              // Success/error alert message.
              $alert = $pisdb->alert(
                alert( 'success', $alert ),
                alert( 'danger', 'Something went wrong.' )
              );
            }

            if ( 'auto-draft' === $patient_status ) {
              $alert = alert( 'warning', 'Update failed. Try again.' );
              $is_invalid = 'is-invalid';
            }
          } else {
            $alert = alert( 'warning', 'The patient was not added. Try again.' );
            $is_invalid = 'is-invalid';
            // $save_button_text = 'Add new patient';
          }
        }

        // Get the updated patient values then display on the current page.
        if ( $patient = $pisdb->get_row( "SELECT * FROM $pisdb->patients WHERE patient_id = $id" ) ) {
          if ( 'auto-draft' !== $patient->patient_status ) {
            $page_name = 'Edit Patient';
          } else {
            $page_name = ( $_SESSION['page_name'] ) ? $_SESSION['page_name'] : $page_name;
            $save_button_text = ( $_SESSION['save_button_text'] ) ? $_SESSION['save_button_text'] : $save_button_text;
          }

          $checked_up_before = false;
          if ( $checkup = $pisdb->get_row( "SELECT checkup_id FROM $pisdb->checkups WHERE checkup_patient_id = $id AND checkup_status != 'auto-draft' ORDER BY checkup_id DESC LIMIT 1" ) ) {
            $checked_up_before = true;
          }

          $incomplete_checkup = false;
          if ( $pisdb->get_row( "SELECT checkup_id FROM $pisdb->checkups WHERE checkup_patient_id = $id AND checkup_status = 'draft' ORDER BY checkup_id DESC LIMIT 1" ) ) {
            $incomplete_checkup = true;
          }

          $data = array_merge( $data, array(
            'app_title' => app_title( $page_name ),
            'page_title' => page_title( back_button( $previous ) . '' . $page_name ),
              'patient'      => array(
                'id'                     => $patient->patient_id,
                'first_name'             => $patient->patient_first_name,
                'last_name'              => $patient->patient_last_name,
                'middle_name'            => $patient->patient_middle_name,
                'sex_options'            => sex_options( $patient->patient_sex ),
                'birthdate'              => date_format( date_create( $patient->patient_date_of_birth ), "Y-m-d" ),
                'civil_status_options'   => civil_status_options( $patient->patient_civil_status ),
                'street_address'         => $patient->patient_street_address,
                'town_or_city'           => $patient->patient_town_or_city,
                'province_or_state'      => $patient->patient_province_or_state,
                'postal_code'            => $patient->patient_postal_code,
                'country'                => $patient->patient_country,
                'phone'                  => $patient->patient_phone,
                'emergency_first_name'   => $patient->patient_emergency_first_name,
                'emergency_last_name'    => $patient->patient_emergency_last_name,
                'emergency_phone'        => $patient->patient_emergency_phone,
                'emergency_relationship' => $patient->patient_emergency_relationship,
                'status'                 => $patient->patient_status,
                'checked_up_before'      => $checked_up_before,
                'incomplete_checkup'     => $incomplete_checkup
              ),
              'checkup'    => array(
                'symptoms'         => '',
                'blood_pressure'   => '',
                'body_temperature' => '',
                'weight'           => '',
                'height'           => '',
                'pulse_rate'       => '',
                'blood_type'       => ''
              ),
              'action_url' => app_home() . "post.php?action=edit&patient=$id&previous=" . urlencode( $previous ),
              'alert'        => $alert,
              'is_invalid'   => $is_invalid,
              'trash_link' => app_home() . "post.php?action=trash&patient=$id&next=" . urlencode( $previous ),
              'check_up_link' => app_home() . "add.php?type=checkup&patient=1&previous=" . urlencode( $_SERVER['REQUEST_URI'] ),
              'save_button_text' => $save_button_text
            )
          );

          $checkup_status = 'auto-draft';
          if ( 'auto-draft' !== $patient->patient_status ) {
            $checkup_status = 'draft';
          }
          if ( $checkup = $pisdb->get_row( "SELECT * FROM $pisdb->checkups WHERE checkup_patient_id = $id AND checkup_status = '$checkup_status' ORDER BY checkup_id DESC LIMIT 1" ) ) {
            $data = array_merge( $data, array(
                'checkup'    => array(
                  'symptoms'         => $checkup->checkup_symptoms,
                  'blood_pressure'   => $checkup->checkup_blood_pressure,
                  'body_temperature' => $checkup->checkup_body_temperature,
                  'weight'           => $checkup->checkup_weight,
                  'height'           => $checkup->checkup_height,
                  'pulse_rate'       => $checkup->checkup_pulse_rate,
                  'blood_type'       => $checkup->checkup_blood_type,
                  'status'           => $checkup->checkup_status
                )
              )
            );
          }
        }

        // Smarty template
        $template = 'post';

        break;
      case 'checkup' :
        $checkup = $pisdb->get_row( "SELECT * FROM $pisdb->checkups WHERE checkup_id = $id" );
        $checkup_patient_id = $checkup->checkup_patient_id;

        // Fired when "Save changes" button is pressed.
        if ( isset( $_POST['save_changes'] ) ) {
          unset( $_POST['save_changes'] );

          // Save changes to the currently edited checkup if either symptoms or blood pressure is not empty.
          $old_checkup_status = '';
          if ( $checkup = $pisdb->get_row( "SELECT * FROM $pisdb->checkups WHERE checkup_id = $id" ) ) {
            $old_checkup_status = $checkup->checkup_status;
            $checkup_status = $checkup->checkup_status;
            if ( 'auto-draft' === $checkup_status ) {
              $checkup_status = 'draft';
            }
            if ( physician() ) {
              $checkup_status = 'published';
            }
          }

          $checkup_symptoms         = $pisdb->real_escape_string( $_POST['checkup_symptoms'] );
          $checkup_blood_pressure   = $pisdb->real_escape_string( $_POST['checkup_blood_pressure'] );
          $checkup_body_temperature = $pisdb->real_escape_string( $_POST['checkup_body_temperature'] );
          $checkup_weight           = $pisdb->real_escape_string( $_POST['checkup_weight'] );
          $checkup_height           = $pisdb->real_escape_string( $_POST['checkup_height'] );
          $checkup_pulse_rate       = $pisdb->real_escape_string( $_POST['checkup_pulse_rate'] );
          $checkup_blood_type       = $pisdb->real_escape_string( $_POST['checkup_blood_type'] );
          $checkup_medications      = $pisdb->real_escape_string( $_POST['checkup_medications'] );
          $checkup_findings         = $pisdb->real_escape_string( $_POST['checkup_findings'] );

          // New checkup
          if ( $checkup_date = $pisdb->get_row( "SELECT checkup_date FROM $pisdb->checkups WHERE checkup_id = $id" ) ) {
            if ( 'published' === $checkup_status && NULL === $checkup_date->checkup_date ) {
              $new_checkup = true;
            }
          }

          if ( $pisdb->query( "UPDATE $pisdb->checkups SET checkup_symptoms = '$checkup_symptoms', checkup_blood_pressure = '$checkup_blood_pressure', checkup_body_temperature = '$checkup_body_temperature', checkup_weight = '$checkup_weight', checkup_height = '$checkup_height', checkup_pulse_rate = '$checkup_pulse_rate', checkup_blood_type = '$checkup_blood_type', checkup_medications = '$checkup_medications', checkup_findings = '$checkup_findings', checkup_status = '$checkup_status'" . ( isset( $new_checkup ) && true === $new_checkup ? ", checkup_date = " . now() . ", checkup_date_gmt = " . now( 'UTC' ) : "" ) . ( 'published' === $checkup_status ? ", checkup_update = " . now() . ", checkup_update_gmt = " . now( 'UTC' ) : "" ) . " WHERE checkup_id = $id" ) ) {

            $alert = 'The checkup was updated.';
            switch ( $old_checkup_status ) {
              case 'auto-draft' :
                $alert = 'The patient was added to checkups.';
                break;
              case 'draft' :
                if ( physician() ) {
                  $alert = 'You\'re done checking up patient.';
                }
                break;
            }
            // Success/error alert message.
            $alert = $pisdb->alert(
              alert( 'success', $alert ),
              alert( 'danger', 'Something went wrong.' )
            );
          }

          if ( 'auto-draft' === $checkup_status ) {
            $alert = alert( 'warning', 'Update failed. Try again.' );
            $is_invalid = 'is-invalid';
          }
        }

        // 
        $patient = $pisdb->get_row( "SELECT patient_id, patient_first_name, patient_last_name, patient_middle_name FROM $pisdb->patients WHERE patient_id = $checkup_patient_id" );
        $checkup = $pisdb->get_row( "SELECT * FROM $pisdb->checkups WHERE checkup_id = $id" );

        $page_name = 'Check Up';
        if ( receptionist() ) {
          $page_name = 'Edit Checkup';
        }
        if ( 'published' === $checkup->checkup_status ) {
          $page_name = 'Checkup Details';
        }
        $save_button_text = 'Update';
        if ( 'draft' === $checkup->checkup_status && physician() ) {
          $save_button_text = 'Done';
        }
        $data = array_merge( $data, array(
            'app_title' => app_title( $page_name ),
            'page_title' => page_title( back_button( $previous ) . $page_name ),
            'patient' => array(
              'id'   => $checkup_patient_id,
              'first_name' => $patient->patient_first_name,
              'middle_name' => $patient->patient_middle_name,
              'last_name' => $patient->patient_last_name,
              'name' => $patient->patient_last_name . ', ' . $patient->patient_first_name . ' ' . middle_initial( $patient->patient_middle_name)
            ),
            'action'     => $action,
            'checkup'    => array(
              'id'               => $checkup->checkup_id,
              'patient_id'       => $checkup->checkup_patient_id,
              'date'             => date_format( date_create( $checkup->checkup_date ), "F j, Y \a\\t g:i A" ),
              'symptoms'         => $checkup->checkup_symptoms,
              'blood_pressure'   => $checkup->checkup_blood_pressure,
              'body_temperature' => $checkup->checkup_body_temperature,
              'weight'           => $checkup->checkup_weight,
              'height'           => $checkup->checkup_height,
              'pulse_rate'       => $checkup->checkup_pulse_rate,
              'blood_type'       => $checkup->checkup_blood_type,
              'medications'      => $checkup->checkup_medications,
              'findings'         => $checkup->checkup_findings,
              'status'           => $checkup->checkup_status
            ),
            'action_url' => app_home() . "post.php?action=edit&checkup=$id&previous=" . urlencode( $previous ),
            'alert'        => $alert,
            'post_inline_styles' => post_inline_styles(),
            'trash_link' => app_home() . "post.php?action=trash&checkup=$id&next=" . urlencode( $previous ),
            'print_link' => app_home() . "post.php?action=print&checkup=$id&previous=" . urlencode( $_SERVER['REQUEST_URI'] ),
            'print_link_all' => app_home() . "post.php?action=print&checkup=$id&print=all&previous=" . urlencode( $_SERVER['REQUEST_URI'] ),
            'save_button_text' => $save_button_text
          )
        );

        // Smarty template
        $template = 'post-checkup';
        if ( 'print' === $action ) {
          $template = $template . "-$action";
          $data = array_merge( $data, array(
              'app_title' => app_title( 'Print' ),
              'page_title' => page_title( back_button( $previous ) . 'Print' ),
              'print_inline_scripts' => print_inline_scripts(),
              'print' => $print,
              'medications' => $parsedown->text( $checkup->checkup_medications ),
              'findings' => $parsedown->text( $checkup->checkup_findings )
            )
          );
        }

        break;
    }

    break;
  case 'delete' :
  case 'discard' :
    // Deleting/discarding a patient.
    if ( isset( $_GET['patient'] ) ) {
      $pisdb->query( "DELETE FROM $pisdb->patients WHERE patient_id = $id" );

      // Success/error alert message to be displayed on admin/edit.php
      $_SESSION['alert'] = $pisdb->alert(
        alert( 'success', 'The patient was deleted permanently.' ),
        alert( 'danger', 'Something went wrong.' )
      );
    }

    // Deleting/discarding a checkup.
    if ( isset( $_GET['checkup'] ) ) {
      $pisdb->query( "DELETE FROM $pisdb->checkups WHERE checkup_id = $id" );

      // Success/error alert message to be displayed on admin/edit.php
      $_SESSION['alert'] = $pisdb->alert(
        alert( 'success', 'The checkup was deleted permanently.' ),
        alert( 'danger', 'Something went wrong.' )
      );
    }
    // header( 'Location: ' . $next );
    redirect( $next );
    die();
    break;
  case 'trash' :
    // Trashing a patient.
    if ( isset( $_GET['patient'] ) ) {
      $pisdb->query( "UPDATE $pisdb->patients SET patient_status = 'trash' WHERE patient_id = $id" );

      // Success/error alert message to be displayed on admin/edit.php
      $_SESSION['alert'] = $pisdb->alert(
        alert( 'success', 'The patient was moved to the trash.' ),
        alert( 'danger', 'Something went wrong.' )
      );
    }

    // Trashing a checkup.
    if ( isset( $_GET['checkup'] ) ) {
      $pisdb->query( "UPDATE $pisdb->checkups SET checkup_status = 'trash' WHERE checkup_id = $id" );

      // Success/error alert message to be displayed on admin/edit.php
      $_SESSION['alert'] = $pisdb->alert(
        alert( 'success', 'The checkup was moved to the trash.' ),
        alert( 'danger', 'Something went wrong.' )
      );
    }
    // header( 'Location: ' . $next );
    redirect( $next );
    die();
    break;
  case 'restore' :
    // Restoring a patient.
    if ( isset( $_GET['patient'] ) ) {
      $pisdb->query( "UPDATE $pisdb->patients SET patient_status = 'published' WHERE patient_id = $id" );

      // Success/error alert message to be displayed on admin/edit.php
      $_SESSION['alert'] = $pisdb->alert(
        alert( 'success', 'The patient was restored.' ),
        alert( 'danger', 'Something went wrong.' )
      );
    }

    // Restoring a checkup.
    if ( isset( $_GET['checkup'] ) ) {
      $pisdb->query( "UPDATE $pisdb->checkups SET checkup_status = 'draft' WHERE checkup_id = $id" );

      // Success/error alert message to be displayed on admin/edit.php
      $_SESSION['alert'] = $pisdb->alert(
        alert( 'success', 'The checkup was restored.' ),
        alert( 'danger', 'Something went wrong.' )
      );
    }
    // header( 'Location: ' . $next );
    redirect( $next );
    die();
    break;
  default :
    // header( 'Location: ' . app_home() );
    redirect( app_home() );
    die();
    break;
}

// view() is located in includes/smarty_template.class.php
$tpl->view( $template, $data );
