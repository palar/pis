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

$users = array(
  'receptionist' => array(
    'password' => 'abcde2023',
    'role'     => 'receptionist'
  ),
  'physician' => array(
    'password' => 'abcde2023',
    'role'     => 'physician'
  ),
  'r' => array(
    'password' => 'r',
    'role'     => 'receptionist'
  ),
  'p' => array(
    'password' => 'p',
    'role'     => 'physician'
  )
);

$account_id = 1;
foreach ( $users as $username => $details ) {
  $password = password_hash( $details['password'], PASSWORD_BCRYPT );
  $role = $details['role'];
  $pisdb->query( "INSERT INTO $pisdb->users (user_id, user_username, user_password, user_role) VALUES ($account_id, '$username', '$password', '$role')" );
  $account_id++;
}

header( 'Location: /' );
