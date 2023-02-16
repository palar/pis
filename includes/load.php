<?php

/**
 * These functions are needed to load PIS.
 *
 * @package PIS
 */

/**
 * Load the correct database class file.
 *
 * global $pisdb PIS Database Object
 */
function require_database() {
  global $pisdb;
  require_once( PIS_DIR . INCLUDES_DIR . '/database.class.php' );
  $pisdb = new Database( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
}

/**
 * Load the correct Parsedown class file.
 *
 * global $parsedown Parsedown Object
 */
function require_parsedown() {
  global $parsedown;
  require_once( PARSEDOWN_DIR . 'Parsedown.php' );
  $parsedown = new Parsedown();
}

/**
 * Load the correct Smarty class file.
 *
 * global $smarty Smarty Object
 */
function require_smarty() {
  global $smarty;
  require_once( SMARTY_DIR . 'Smarty.class.php' );
  $smarty = new Smarty();
}

/**
 * Load the correct Smarty_Template class file.
 *
 * global $tpl PIS Smarty_Template Object
 */
function require_smarty_template() {
  global $tpl;
  require_once( PIS_DIR . INCLUDES_DIR . '/smarty_template.class.php' );
  $tpl = new Smarty_Template();
}
