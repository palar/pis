<?php

/**
 * Bootstrap file for loading the config.php file. The
 * config.php file will then load the settings.php file,
 * which will then set up the PIS environment.
 *
 * @package PIS
 */

/** Define PIS_DIR as this file's directory */
define( 'PIS_DIR', __DIR__ . '/' );

/** The config file resides in PIS_DIR */
require_once( PIS_DIR . 'pis-config.php' );
