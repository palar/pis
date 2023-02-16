<?php

/**
 * Used to include the PIS procedural and class library.
 *
 * @package PIS
 */

/**
 * Stores the location of the directory of admin.
 */
define( 'ADMIN_DIR', 'admin' );

/**
 * Stores the location of the directory of assets.
 */
define( 'ASSETS_DIR', 'assets' );

/**
 * Stores the location of the directory of functions and classes.
 */
define( 'INCLUDES_DIR', 'includes' );

/**
 * Sets the time zone.
 */
if ( defined( 'TIMEZONE' ) ) {
  date_default_timezone_set( TIMEZONE );
}

// Include files required for initialization.
require( PIS_DIR . INCLUDES_DIR . '/load.php' );

if ( $conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD ) ) {
  if ( $conn->query( "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET " . DB_CHARSET . " COLLATE " . DB_COLLATE ) ) {
    $conn->close();

    if ( $conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME ) ) {
      if ( 0 == $conn->query( "SHOW TABLES FROM " . DB_NAME )->num_rows ) {
        $conn->close();
        header( 'Location: /install.php' );
      }
    }
  }
}

// Include the PIS Database class.
require_database();

// Include the Parsedown class.
require_parsedown();

// Include the Smarty class.
require_smarty();

// Include the Smarty_Template class.
require_smarty_template();

// Load early PIS files.
require( PIS_DIR . INCLUDES_DIR . '/functions.php' );

// Start session.
session_start();
