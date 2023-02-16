<?php

/**
 * @package PIS
 */

/** Load the PIS environment. */
require_once( __DIR__ . '/pis-load.php' );

$alert = '';
$username = '';

if ( isset( $_POST['username'] ) ) {
  $username = post_variable( 'username' );
  unset( $_POST['username'] );

  if ( empty( $username ) ) {
    $alert = 'Please enter username.';
  } else {
    if ( isset( $_POST['password'] ) ) {
      $password = post_variable( 'password' );
      unset( $_POST['password'] );

      if ( empty( $password ) ) {
        $alert = 'Please enter password.';
      } else {
        if ( ! $user = $pisdb->get_row( "SELECT * FROM $pisdb->users WHERE user_username = '$username'" ) ) {
          $alert = 'No user found for this username/password';
        } else {
          if ( ! password_verify( $password, $user->user_password ) ) {
            $alert = 'Incorrect password';
          } else {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user->user_role;
            header( 'Location: ' . app_home() );
            // redirect( app_home() );
            die();
          }
        }
      }
    }
  }
}

// Values to be assigned to a specific Smarty template
$data = array(
  'app_title'    => app_title( 'Log In' ),
  'login_styles' => login_styles(),
  'login_scripts' => login_scripts(),
  'action_url'   => $_SERVER['PHP_SELF'],
  'alert'        => ! empty( $alert ) ? alert( 'danger', $alert ) : '',
  'username'     => $username
);

// view() is located in includes/smarty_template.class.php
$tpl->view( 'login', $data );
