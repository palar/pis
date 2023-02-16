<?php

/**
 * Front to the PIS application.
 *
 * @package PIS
 */

/** Load the PIS environment. */
require_once( __DIR__ . '/pis-load.php' );

if ( 'log_out' === get( 'action' ) ) {
  session_destroy();
  redirect( app_home() );
  die();
}

if ( ! logged_in() ) {
  // Values to be assigned to a specific Smarty template
  $data = array(
    'app_title'    => app_title( 'Log In' ),
    'login_styles' => login_styles(),
    'login_scripts' => login_scripts(),
    'action_url'   => '/login.php'
  );

  // Smarty template
  $template = 'login';
} else {
  // Values to be assigned to a specific Smarty template
  $data = array(
    'app_title'    => app_title( 'Home' ),
    'page_title'   => page_title( 'Home' ),
    'page_content' => '<p class="lead">Hi there! You’re logged in as the <strong>' . $_SESSION['role'] . '</strong>.</p>'
  );

  // Smarty template
  $template = 'home';
}

// view() is located in includes/smarty_template.class.php
$tpl->view( $template, $data );
