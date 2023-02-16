<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{$app_description}">
    <meta name="author" content="">
    <title>{$app_title}</title>
    <link rel="icon" href="/favicon.ico">
    {foreach $app_styles as $app_style}
      <link href="{$app_style}" rel="stylesheet">
    {/foreach}
    {if isset($post_inline_styles)}{$post_inline_styles}{/if}
    <meta name="robots" content="noindex, nofollow">
    {if isset($redirect)}{$redirect}{/if}
    <link href="/manifest.json" rel="manifest">
    <meta name="theme-color" content="#ffffff">
  </head>
  <body data-instant-allow-query-string>
    <div id="app">
      <header class="navbar navbar-light sticky-top bg-light flex-md-nowrap">
        <a class="navbar-brand px-3" href="{$app_home}"><img src="/images/logo.png" height="40" class="navbar-pis-small"><img src="/images/bmalinaomedclinic.png" height="40" class="navbar-pis"></a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-nav">
          <div class="nav-item text-nowrap">
            <a class="nav-link px-3" href="{$app_home}?action=log_out" data-no-instant><span data-feather="log-out" class="align-text-bottom"></span> Log Out</a>
          </div>
        </div>
      </header>
        <div class="container-fluid">
          <div class="row">
            <nav id="sidebarMenu" class="col-md-4 col-lg-3 d-md-block bg-light sidebar collapse">
              <div class="position-sticky pt-3 sidebar-sticky">
                {$admin_sidebar}
              </div>
            </nav>
            <main class="col-md-8 offset-md-4 col-lg-6 offset-lg-3 px-3">
