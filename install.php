<?php

/**
 * @package PIS
 */

/** Load the PIS environment. */
require_once( __DIR__ . '/pis-load.php' );

$pisdb->create_tables();

// Default settings
default_settings( array(
    'app_title'       => 'Beltran-Malinao Medical Clinic',
    'app_description' => "Patient Information System for Beltran-Malinao Medical Clinic",
    'app_name'        => 'Beltran-Malinao Medical Clinic',
    'physician_name'  => 'Dr. Ma. Dollyne Beltran-Malinao, MD',
    'street_address'  => '393M+FMF, Rizal St. corner Colon St Guiwan',
    'town'            => 'Palompon',
    'province'        => 'Leyte',
    'zip_code'        => '6538',
    'country'         => '',
    'phone'           => '(053) 555 0124'
  )
);

$default_users = array(
  'receptionist' => array(
    'password' => '$2y$10$u.soxSGV.eeoaeznBLudXux8ANYsehHUxUc98NlNzwyPsdGtikgMi',
    'role'     => 'receptionist'
  ),
  'physician' => array(
    'password' => '$2y$10$u.soxSGV.eeoaeznBLudXux8ANYsehHUxUc98NlNzwyPsdGtikgMi',
    'role'     => 'physician'
  ),
  'r' => array(
    'password' => '$2y$10$5NaQkk1whj490abUP35yqOhKHJvDoJUvFz96lMTxHft1ndf/D6yO6',
    'role'     => 'receptionist'
  ),
  'p' => array(
    'password' => '$2y$10$mUU.iYfbG.VIxGiEAPHOXuJkJyQHwW7Y01sKhUeal.a4lASVw1Jvq',
    'role'     => 'physician'
  )
);

$account_id = 1;
foreach ( $default_users as $username => $details ) {
  $password = $details['password'];
  $role = $details['role'];
  $pisdb->query( "INSERT INTO $pisdb->users (user_id, user_username, user_password, user_role) VALUES ($account_id, '$username', '$password', '$role')" );
  $account_id++;
}

header( 'Location: /' );
