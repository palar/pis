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

$page = get( 'page' );
$alert = '';

// Check to see if the requested page matches any of the following cases.
switch ( $page ) {
  case 'account' :
    $username = $_SESSION['username'];

    // Values to be assigned to a specific Smarty template
    $data = array(
      'app_title'    => app_title( 'Account' ),
      'page_title'    => page_title( 'Account' ),
      'username' => $username,
      'role' => $_SESSION['role'],
      'user_current_password' => '',
      'user_new_password' => ''
    );

    // Fired when "Save changes" button is pressed.
    if ( isset( $_POST['save_changes'] ) ) {
      unset( $_POST['save_changes'] );

      // Save changes
      if ( ! empty( $_POST['user_current_password'] ) ) {
        $current = $_POST['user_current_password'];
        if ( $user = $pisdb->get_row( "SELECT * FROM $pisdb->users WHERE user_username = '$username'" ) ) {
          if ( ! password_verify( $current, $user->user_password ) ) {
            $alert = alert( 'warning', 'The current password you\'ve entered is incorrect. Try again.' );
          } else {
            $new_password = $_POST['user_new_password'];
            $new_password_confirm = $_POST['user_new_password_confirm'];
            if ( empty( $new_password ) ) {
              $data['user_current_password'] = $current;
              $alert = alert( 'warning', 'Please type new password.' );
            } else {
              if ( empty( $new_password_confirm ) ) {
                $data['user_current_password'] = $current;
                $data['user_new_password'] = $new_password;
                $alert = alert( 'warning', 'Please re-type new password.' );
              } else {
                if ( 0 !== strcmp( $new_password, $new_password_confirm ) ) {
                  $alert = alert( 'warning', 'New password and re-typed new password did not match. Try again' );
                } else {
                  $new_password = password_hash( $new_password, PASSWORD_BCRYPT );
                  $pisdb->query( "UPDATE $pisdb->users SET user_password = '$new_password' WHERE user_username = '$username'" );
                  $alert = alert( 'success', 'Your password was changed.' );
                }
              }
            }
          }
        }
        $_SESSION['alert'] = $alert;
      }

      // Display the success/error alert message on the same page.
      if ( isset( $_SESSION['alert'] ) ) {
        // Values to be assigned to a specific Smarty template
        $data['alert'] = $_SESSION['alert'];
        unset( $_SESSION['alert'] );
      }
    }

    // Smarty template
    $template = 'settings-account';
    break;
  default :
    // Values to be assigned to a specific Smarty template
    $data = array(
      'app_title'    => app_title( 'Settings' ),
      'page_title'    => page_title( 'Settings' ),
      'settings'      => array()
    );

    if ( $settings = $pisdb->get_rows( "SELECT setting_name, setting_title, setting_value FROM $pisdb->settings" ) ) {
      foreach ( $settings as $setting ) {
        array_push( $data['settings'], array(
            'name'  => $setting['setting_name'],
            'title' => $setting['setting_title'],
            'value' => $setting['setting_value']
          )
        );
      }
    }

    // Display the success/error alert message on the same page.
    if ( isset( $_SESSION['alert'] ) ) {
      // Values to be assigned to a specific Smarty template
      $data['alert'] = $_SESSION['alert'];
      unset( $_SESSION['alert'] );
    }

    // Called after the "Save changes" button is pressed.
    if ( isset( $_POST['save_changes'] ) ) {
      unset( $_POST['save_changes'] );

      // Update settings with new values.
      foreach ( $_POST as $setting_name => $setting_value ) {
        $setting_name = $pisdb->real_escape_string( $setting_name );
        $setting_value = $pisdb->real_escape_string( $setting_value );
        update_setting( $setting_name, $setting_value );
      }

      // Success/error alert message.
      $alert = $pisdb->alert(
        alert( 'success', 'The settings were updated.' ),
        alert( 'danger', 'Something went wrong.' )
      );

      $_SESSION['alert'] = $alert;
      // header( 'Location: ' . app_home() . 'settings.php' );
      redirect( app_home() . 'settings.php' );
      die();
    }

    // Smarty template
    $template = 'settings';
    break;
}

// view() is located in includes/smarty_template.class.php
$tpl->view( $template, $data );
