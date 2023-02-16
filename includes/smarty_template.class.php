<?php

/**
 * PIS Smarty_Template Class
 *
 * @package PIS
 * @subpackage Smarty_Template
 */

/**
 * @package PIS
 * @subpackage Smarty_Template
 */
class Smarty_Template {
  /**
   * @param string $template
   * @param array $data
   */
  function render( $template, $data ) {
    global $smarty;
    $smarty->caching = false;
    $smarty->setTemplateDir( PIS_DIR . '/dependencies/templates/' );
    $smarty->setCompileDir( PIS_DIR . '/dependencies/template_c/' );
    $smarty->setCacheDir( PIS_DIR . '/dependencies/cache/' );
    $smarty->setConfigDir( PIS_DIR . '/dependencies/config/' );
    $smarty->setTemplateDir( PIS_DIR . '/templates/' );
    $smarty->assign( array(
        'default_app_name'   => app_name(),
        'app_description'    => get_setting( 'app_description' ),
        'app_logo'           => get_setting( 'app_logo'),
        'app_title'          => app_title(),
        'app_name'           => get_setting( 'app_name' ),
        'street_address'     => get_setting( 'street_address' ),
        'town'               => get_setting( 'town' ),
        'province'           => get_setting( 'province' ),
        'zip_code'           => get_setting( 'zip_code' ),
        'country'            => get_setting( 'country' ),
        'phone'              => get_setting( 'phone' ),
        'app_home'           => app_home(),
        'alert'              => '',
        'admin_navbar'       => admin_navbar(),
        'app_styles'         => app_styles(),
        'admin_sidebar'      => admin_sidebar(),
        'app_scripts'        => app_scripts(),
        'app_inline_scripts' => app_inline_scripts(),
        'physician_name'     => get_setting( 'physician_name' ),
        'receptionist'       => receptionist(),
        'physician'          => physician()
      )
    );

    // 
    $smarty->assign( $data );
    $smarty->display( "$template.tpl" );
  }

  /**
   * @param string $template
   * @param array $data
   */
  function view( $template, $data ) {
    if ( ! isset( $template ) || empty( $template ) ) {
      http_response_code( 404 );
      header( 'Content-Type: text/plain; charset=utf-8' );
      echo '404: Not Found';
      die();
    }

    $this->render( $template, $data );
  }
}
