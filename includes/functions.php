<?php

/**
 * @package PIS
 */

/**
 * @return string
 */
function app_name() {
  return 'Patient Information System';
}

/**
 * @param string $file
 * @return string
 */
function version( $file ) {
  $version = '1.0';

  if ( file_exists( $file ) ) {
    $version = filemtime( $file );
  }

  return $version;
}

/**
 * @param string $time_zone
 * @return string
 */
function now( $time_zone = '' ) {
  if ( isset( $time_zone ) && 'UTC' === $time_zone ) {
    return 'UTC_TIMESTAMP()';
  }

  if ( ! defined( 'TIMEZONE' ) ) {
    return;
  }

  switch ( TIMEZONE ) {
    case 'Asia/Manila' :
      $offset = '+08:00';
      break;
    default :
      break;
  }

  return "CONVERT_TZ(UTC_TIMESTAMP(),'+00:00','$offset')";
}

/**
 * @param string $variable
 * @param string $value
 * @return string
 */
function get( $variable = '', $value = '' ) {
  $value = ( isset( $_GET[$variable] ) && ! empty( $_GET[$variable] ) ) ? $_GET[$variable] : $value;
  return $value;
}

/**
 * @param string $variable
 * @param string $value
 * @return string
 */
function post_variable( $variable = '', $value = '' ) {
  $value = ( isset( $_POST[$variable] ) && ! empty( $_POST[$variable] ) ) ? $_POST[$variable] : $value;
  return $value;
}

/**
 * @param string $variable
 * @param string $value
 * @return string
 */
function session_variable( $variable = '', $value = '' ) {
  $value = ( isset( $_SESSION[$variable] ) && ! empty( $_SESSION[$variable] ) ) ? $_SESSION[$variable] : $value;
  return $value;
}

/**
 * @return bool
 */
function logged_in() {
  if ( empty( session_variable( 'role' ) ) ) {
    return false;
  }

  return true;
}

/**
 * @return bool
 */
function receptionist() {
  $role = session_variable( 'role' );

  if ( empty( $role ) ) {
    return false;
  }

  if ( 'receptionist' !== $role ) {
    return false;
  }

  return true;
}

/**
 * @return bool
 */
function physician() {
  $role = session_variable( 'role' );

  if ( empty( $role ) ) {
    return false;
  }

  if ( 'physician' !== $role ) {
    return false;
  }

  return true;
}

/**
 * @param string $setting_name
 * @return string
 */
function get_setting( $setting_name ) {
  global $pisdb;
  $query  = "SELECT setting_value FROM $pisdb->settings WHERE setting_name = '$setting_name'";

  if ( $option = $pisdb->get_row( $query ) ) {
    return $option->setting_value;
  }
}

/**
 * @param string $setting_name
 * @param string $setting_value
 * @param string
 */
function update_setting( $setting_name, $setting_value ) {
  global $pisdb;
  $query = "UPDATE $pisdb->settings SET setting_value = '$setting_value', setting_update = " . now() . ", setting_update_gmt = " . now( 'UTC' ) . " WHERE setting_name = '$setting_name' AND NOT setting_value = '$setting_value'";
  $pisdb->query( $query );
}

/**
 * @param array $settings
 */
function default_settings( $settings ) {
  global $pisdb;
  foreach ( $settings as $setting_name => $setting_value ) {
    $query = "UPDATE $pisdb->settings SET setting_value = '$setting_value', setting_update = " . now() . ", setting_update_gmt = " . now( 'UTC' ) . " WHERE setting_name = '$setting_name'";
    $pisdb->query( $query );
  }
}

/**
 * @param string $page
 * @param string $sep
 * @return string
 */
function app_title( $page = '', $sep = '-' ) {
  $title = get_setting( 'app_title' );

  if ( ! empty ( $page ) ) {
    if ( ! empty( $title ) ) {
      $title = $page . ' ' . $sep . ' ' . $title;
    } else {
      $title = $page;
    }
  }

  return $title;
}

/**
 * @param string $page_title
 * @return string
 */
function page_title( $page_title ) {
  return '<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"><h1 class="h2">' . $page_title . '</h1></div>';
}

/**
 * @return string
 */
function app_home() {
  // return PIS_HOME . '/';
  return str_replace( HOST_URL, '', PIS_HOME ) . '/';
}

/**
 * @return bool
 */
function is_production() {
  if ( defined( 'PRODUCTION' ) && true === PRODUCTION ) {
    return true;
  }

  return false;
}

/**
 *
 */
function getbootstrap() {
  $getbootstrap = 'https://getbootstrap.com/';

  if ( ! is_production() ) {
    $getbootstrap = PIS_HOME . '/dependencies/getbootstrap.com/';
  }

  return $getbootstrap;
}

/**
 *
 */
function jsdelivr() {
  $jsdelivr = 'https://cdn.jsdelivr.net/';

  if ( ! is_production() ) {
    $jsdelivr = PIS_HOME . '/dependencies/cdn.jsdelivr.net/';
  }

  return $jsdelivr;
}

/**
 *
 */
function unpkg() {
  $unpkg = 'https://unpkg.com/';

  if ( ! is_production() ) {
    $unpkg = PIS_HOME . '/dependencies/unpkg.com/';
  }

  return $unpkg;
}

/**
 * @return array
 */
function app_styles() {
  return array(
    // jsdelivr() . 'npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css',
    unpkg() . 'bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css',
    app_home() . 'css/app.min.css?ver=' . version( PIS_DIR . 'css/app.min.css' )
  );
}

/**
 * @return array
 */
function login_styles() {
  return array(
    // jsdelivr() . 'npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css',
    unpkg() . 'bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css',
    app_home() . 'css/login.min.css?ver=' . version( PIS_DIR . 'css/login.min.css' )
  );
}

/**
 * @return array
 */
function login_scripts() {
  return array(
    // jsdelivr() . 'npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js',
    unpkg() . 'bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js',
    unpkg() . 'vue@3.2.47/dist/vue.global.prod.js',
    app_home() . 'js/app.js?ver=' . version( PIS_DIR . 'js/app.js' )
  );
}

/**
 * @return array
 */
function app_scripts() {
  return array(
    // jsdelivr() . 'npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js',
    unpkg() . 'bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js',
    // jsdelivr() . 'npm/feather-icons/dist/feather.min.js',
    unpkg() . 'feather-icons@4.29.0/dist/feather.min.js',
    // jsdelivr() . 'npm/instant.page@5.1.1/instantpage.min.js'
    // unpkg() . 'instant.page@5.1.1/instantpage.js',
    unpkg() . 'vue@3.2.47/dist/vue.global.prod.js',
    app_home() . 'js/app.js?ver=' . version( PIS_DIR . 'js/app.js' )
  );
}

/**
 * @param string $middle_name
 * @return string
 */
function middle_initial( $middle_name = '' ) {
  if ( empty( $middle_name ) ) {
    return;
  }

  $middle_name = str_split( $middle_name );
  $middle_initial = $middle_name[0] . '.';
  return $middle_initial;
}

/**
 * @param string $url
 * @return bool
 */
function is_active( $url ) {
  $uri = pathinfo( $url )['basename'];
  $previous = isset( $_GET['previous'] ) ? $_GET['previous'] : '';
  $main = isset( $_SESSION['main'] ) ? $_SESSION['main'] : '';

  $request_uri = pathinfo( $_SERVER['REQUEST_URI'] )['basename'];

  if ( ! empty( $previous ) ) {
    $request_uri = pathinfo( $previous )['basename'];
  }

  if ( ! empty( $main ) ) {
    $request_uri = pathinfo( $main )['basename'];
  }

  if ( 0 !== strcmp( $request_uri, $uri ) ) {
    return false;
  }

  return true;
}

/**
 * @param string $link
 * @param string $title
 * @param string $icon
 * @return string
 */
function menu_item( $link = '', $classes = '', $title = '', $icon = '', $data = '' ) {
  if ( empty( $link ) ) {
    return;
  }

  if ( is_active( $link ) ) {
    $classes .= ( ( ! empty( $classes ) ) ? ' ' : '' ) . 'active';
  }

  $data = ( ! empty( $data ) ) ? ' ' . $data : '';
  $icon = ( ! empty( $icon ) ) ? '<span data-feather="' . $icon . '" class="align-text-bottom"></span>' : '';
  return '<a' . ( ( ! empty( $classes ) ) ? ' class="' . $classes . '"' : '' ) . ' href="' . $link . '"' . $data . '>' . $icon . $title . '</a>';
}

/**
 * @return array
 */
function nav_items() {
  $home = array(
    'home' => array(
      array( 'url' => app_home(), 'icon' => 'home', 'title' => ' Home', 'data' => '' )
    )
  );

  $patients = array(
    'patients' => array(
      array( 'url' => app_home() . 'edit.php', 'icon' => 'users', 'title' => ' Patients', 'data' => '' ),
    )
  );

  $patients_for_receptionist = array(
    'patients' => array(
      array( 'url' => app_home() . 'edit.php', 'icon' => 'users', 'title' => ' Patients', 'data' => '' ),
      array( 'url' => app_home() . 'add.php', 'icon' => 'user-plus', 'title' => ' Add New', 'data' => '' )
    )
  );

  $checkups = array(
    'checkups' => array(
      array( 'url' => app_home() . 'edit.php?type=checkup', 'icon' => 'folder', 'title' => ' Checkups', 'data' => '' )
    )
  );

  $history = array(
    'history' => array(
      array( 'url' => app_home() . 'edit.php?type=checkup&page=history', 'icon' => 'clock', 'title' => ' History', 'data' => '' )
    )
  );

  $trash = array(
    'trash' => array(
      array( 'url' => app_home() . 'trash.php', 'icon' => 'trash-2', 'title' => ' Trash', 'data' => '' )
    )
  );

  $settings = array(
    'settings' => array(
      array( 'url' => app_home() . 'settings.php', 'icon' => 'sliders', 'title' => ' Settings', 'data' => '' ),
      array( 'url' => app_home() . 'settings.php?page=account', 'icon' => 'user', 'title' => ' Account', 'data' => '' ),
    )
  );

  $account = array(
    'account' => array(
      array( 'url' => app_home() . '?action=log_out', 'icon' => 'log-out', 'title' => ' Log Out', 'data' => 'data-no-instant' )
    )
  );

  $nav_items = array();

  if ( receptionist() ) {
    $nav_items = array_merge( $nav_items, $home );
    $nav_items = array_merge( $nav_items, $patients_for_receptionist );
    $nav_items = array_merge( $nav_items, $checkups );
    $nav_items = array_merge( $nav_items, $history );
    $nav_items = array_merge( $nav_items, $trash );
    $nav_items = array_merge( $nav_items, $settings );
    $nav_items = array_merge( $nav_items, $account );
  } else if ( physician() ) {
    $nav_items = array_merge( $nav_items, $home );
    $nav_items = array_merge( $nav_items, $checkups );
    $nav_items = array_merge( $nav_items, $patients );
    $nav_items = array_merge( $nav_items, $history );
    $nav_items = array_merge( $nav_items, $settings );
    $nav_items = array_merge( $nav_items, $account );
  }

  return $nav_items;
}

/**
 * @return string
 */
function admin_sidebar() {
  $nav_items = nav_items();
  $html = '';

  foreach ( $nav_items as $key => $group ) {
    $html .= '<ul class="nav nav-pills flex-column mb-2 nav-' . $key . '">';

    foreach ( $group as $item => $value ) {
      $html .= '<li class="nav-item">' . menu_item( $value['url'], 'nav-link', $value['title'], $value['icon'], $value['data'] ) . '</li>';
    }

    $html .= '</ul>' . "\n";
  }

  return $html;
}

/**
 * @return string
 */
function admin_navbar() {
  $nav_items = nav_items();
  $html = '';

  foreach ( $nav_items as $key => $group ) {
    $counter = 0;
    $dropdown_items = '';

    foreach ( $group as $item => $value ) {
      $counter++;
      $dropdown_items .= menu_item( $value['url'], 'dropdown-item', $value['title'], $value['icon'] );
    }

    if ( 1 < $counter ) {
      $html .= '<li class="nav-item dropdown"><a class="nav-link dropdown-toggle' . ( is_active( $group[0]['url'] ) ? ' active" aria-current="page' : '' ) . '" href="' . $group[0]['url'] . '" data-bs-toggle="dropdown" aria-expanded="false">' . ( ( "" !== $group[0]['icon'] ) ? '<span data-feather="' . $group[0]['icon'] . '" class="align-text-bottom"></span>' : '' ) . ' ' . $group[0]['title'] . '</a><ul class="dropdown-menu">' . $dropdown_items . '</ul></li>';
    } else {
      $html .= '<li class="nav-item"><a class="nav-link' . ( is_active( $group[0]['url'] ) ? ' active" aria-current="page' : '' ) . '" href="' . $group[0]['url'] . '">' . ( ( "" !== $group[0]['icon'] ) ? '<span data-feather="' . $group[0]['icon'] . '" class="align-text-bottom"></span>' : '' ) . ' ' . $group[0]['title'] . '</a></li>';
    }
  }

  $html = '<ul class="navbar-nav me-auto mb-2 mb-md-0">' . $html . '</ul>';

  return $html;
}

/**
 * @param $selected string
 * @return array
 */
function sex_options( $selected = '' ) {
  $options = array( '', 'Male', 'Female' );
  $data = array();

  foreach ( $options  as $option ) {
    $data[] = array(
      'value'    => $option,
      'selected' => ( 0 === strcmp( $option, $selected ) ) ? 'selected' : '',
      'name'     => $option
    );
  }

  return $data;
}

/**
 * @param $selected string
 * @return array
 */
function civil_status_options( $selected = '' ) {
  $options = array( '', 'Single', 'Married', 'Widow', 'Separated' );
  $data = array();

  foreach ( $options  as $option ) {
    $data[] = array(
      'value'    => $option,
      'selected' => ( 0 === strcmp( $option, $selected ) ) ? 'selected' : '',
      'name'     => $option
    );
  }

  return $data;
}

/**
 * @return string
 */
function redirect( $url = '' ) {
  if ( empty( $url ) ) {
    return;
  }

  header( 'Refresh: 0; url=' . $url );
  /* ob_start();
  ?>
  <script>location.replace('<?php echo $url; ?>')</script>
  <noscript><meta http-equiv="refresh" content="0; url=<?php echo $url; ?>"></noscript>
  <?php
  return ob_get_clean(); */
}

/**
 * @return string
 */
function app_inline_scripts() {
  ob_start();
  ?>
  <script>feather.replace()</script>
  <?php
  return ob_get_clean();
}

/**
 * @return string
 */
function print_inline_scripts() {
  ob_start();
  ?>
  <script>window.print()</script>
  <?php
  return ob_get_clean();
}

/**
 * @return string
 */
function post_inline_styles() {
  ob_start();
  ?>
  <style>
    @media print {
      @page { margin: 0; }
      body { margin: 1.6cm; }
      .alert,
      .navbar { display: none !important; }
      .for-print-only { display: block !important; }
    }
  </style>
  <?php
  return ob_get_clean();
}

/**
 * @param string $class
 * @param string $alert
 * @return string
 */
function alert( $class, $alert ) {
  return '<div class="alert alert-' . $class . ' alert-dismissible fade show" role="alert">' . $alert . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}

/**
 * @param string $string
 * @return int
 */
function to_integer( $string ) {
  $string = trim( $string, ' ' );
  $string = str_replace( ',', '', $string );
  return intval( $string );
}

/**
 * @return string
 */
function back_button( $link ) {
  return '<a @click="disableLinkButton" class="btn btn-light me-3" href="' . $link . '"><i data-feather="arrow-left" class="previous"></i></a>';
}
