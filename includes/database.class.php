<?php

/**
 * PIS Database Class
 *
 * @package PIS
 * @subpackage Database
 */

/**
 * PIS Database Access Abstraction Object
 *
 * @package PIS
 * @subpackage Database
 */
class Database extends mysqli {
  /**
   * Connects to the database server and selects a database
   *
   * @param string $dbhost MySQL database host
   * @param string $dbuser MySQL database user
   * @param string $dbpassword MySQL database password
   * @param string $dbname MySQL database host
   */
  function __construct( $dbhost, $dbuser, $dbpassword, $dbname ) {
    parent::__construct( $dbhost, $dbuser, $dbpassword, $dbname );
    $this->patients = 'pis_patients';
    $this->checkups = 'pis_checkups';
    $this->settings = 'pis_settings';
    $this->users = 'pis_users';

    // Create tables
    // $this->create_tables();
  }

  /**
   * Get a single row.
   *
   * @param string $query
   * @return object
   */
  function get_row( $query ) {
    $row = $this->query( $query );
    $row = $row->fetch_object();

    return $row;
  }

  /**
   * Get multiple rows.
   *
   * @param string $query
   * @return array
   */
  function get_rows( $query ) {
    $rows = $this->query( $query );
    $new_rows = array();

    while ( $row = $rows->fetch_assoc() ) {
      $new_rows[] = $row;
    }

    return $new_rows;
  }

  /**
   * Order.
   *
   * @param string $order_by
   * @param string $order
   * @return string
   */
  function order( $order_by = '', $order = 'asc' ) {
    if ( empty( $order_by ) ) {
      return;
    }

    $order_by = ' ORDER BY ' . $order_by;

    switch ( $order ) {
      case 'asc' :
        $order_by .= ' ASC';
        break;
      case 'desc' :
        $order_by .= ' DESC';
        break;
      case 'false' :
        $order_by = '';
        break;
    }

    return $order_by;
  }

  /**
   * Show success/error message.
   *
   * @param string $success
   * @param string $error
   * @return string
   */
  function alert( $success = '', $error = '' ) {
    $alert = $error;

    if ( ! $this->error ) {
      $alert = $success;
    }

    return $alert;
  }

  /**
   * Create the patients table.
   */
  function create_patients_table() {
    $this->query( "CREATE TABLE IF NOT EXISTS $this->patients ( patient_id bigint(20) NOT NULL AUTO_INCREMENT, patient_date datetime, patient_date_gmt datetime, patient_first_name tinytext, patient_last_name tinytext, patient_middle_name tinytext, patient_status varchar(20), patient_sex varchar(20), patient_date_of_birth varchar(20), patient_civil_status varchar(20), patient_street_address tinytext, patient_town_or_city tinytext, patient_province_or_state tinytext, patient_postal_code tinytext, patient_country tinytext, patient_phone tinytext, patient_emergency_first_name tinytext, patient_emergency_last_name tinytext, patient_emergency_phone tinytext, patient_emergency_relationship tinytext, patient_update datetime, patient_update_gmt datetime, PRIMARY KEY (patient_id) ) ENGINE=InnoDB DEFAULT CHARSET=" . DB_CHARSET . " COLLATE=" . DB_COLLATE );
  }

  /**
   * Create the checkups table.
   */
  function create_checkups_table() {
    $this->query( "CREATE TABLE IF NOT EXISTS $this->checkups ( checkup_id bigint(20) NOT NULL AUTO_INCREMENT, checkup_patient_id bigint(20), checkup_date datetime, checkup_date_gmt datetime, checkup_status varchar(20), checkup_symptoms tinytext, checkup_blood_pressure varchar(20), checkup_body_temperature varchar(20), checkup_weight varchar(20), checkup_height varchar(20), checkup_pulse_rate varchar(20), checkup_blood_type varchar(20), checkup_medications text, checkup_findings text, checkup_update datetime, checkup_update_gmt datetime, PRIMARY KEY (checkup_id) ) ENGINE=InnoDB DEFAULT CHARSET=" . DB_CHARSET . " COLLATE=" . DB_COLLATE );
  }

  /**
   * Create the settings table.
   */
  function create_settings_table() {
    $this->query( "CREATE TABLE IF NOT EXISTS $this->settings ( setting_id bigint(20) NOT NULL AUTO_INCREMENT, setting_name varchar(255), setting_title tinytext, setting_value text, setting_update datetime, setting_update_gmt datetime, PRIMARY KEY (setting_id) ) ENGINE=InnoDB DEFAULT CHARSET=" . DB_CHARSET . " COLLATE=" . DB_COLLATE );

    $settings = array(
      'app_title'       => 'Title',
      'app_description' => 'Description',
      'app_name'        => 'Name',
      'physician_name'  => 'Physician',
      'street_address'  => 'Street Address',
      'town'            => 'Town',
      'province'        => 'Province',
      'zip_code'        => 'ZIP code',
      'country'         => 'Country',
      'phone'           => 'Phone'
    );

    // 
    foreach ( $settings as $setting_name => $setting_title ) {
      if ( $result = $this->query( "SELECT setting_name FROM $this->settings WHERE setting_name = '$setting_name'" ) ) {
        if ( 0 === $result->num_rows ) {
          $this->query( "INSERT INTO $this->settings (setting_name, setting_title) VALUES ('$setting_name', '$setting_title')" );
        }
      }
    }
  }

  /**
   * Create the users table.
   */
  function create_users_table() {
    $this->query( "CREATE TABLE IF NOT EXISTS $this->users ( user_id bigint(20) NOT NULL AUTO_INCREMENT, user_username varchar(100), user_password varchar(255), user_role varchar(100), PRIMARY KEY (user_id) ) ENGINE=InnoDB DEFAULT CHARSET=" . DB_CHARSET . " COLLATE=" . DB_COLLATE );
  }

  /**
   *
   */
  function create_tables() {
    $this->create_patients_table();
    $this->create_checkups_table();
    $this->create_settings_table();
    $this->create_users_table();
  }
}
