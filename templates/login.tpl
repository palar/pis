<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{$app_description}">
    <meta name="author" content="">
    <title>{$app_title}</title>
    <link rel="icon" href="/favicon.ico">
    {* <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous"> *}
    {foreach $login_styles as $login_style}
      <link href="{$login_style}" rel="stylesheet">
    {/foreach}
    <meta name="robots" content="noindex, nofollow">
    <link href="/manifest.json" rel="manifest">
    <meta name="theme-color" content="#ffffff">
  </head>
  <body>
    <main class="m-auto">
      <div class="container px-4 py-5">
        <div class="row align-items-center g-lg-5 py-5">
          <div class="col-lg-7 text-center text-lg-start">
            {* <h1 class="display-6 fw-bold lh-1 mb-3">Patient Information System</h1> *}
            <img src="/images/bmalinaomedclinic.png" class="img-fluid mb-3">
            {* <h1 class="display-6 fw-bold lh-1 mb-3">{$app_description}</h1> *}
            <p class="col-lg-10 fs-2">{$app_description}</p>
          </div>
          <div id="app" class="col-md-10 mx-auto col-lg-5">
            {$alert}
            <form class="p-3 border rounded-3 bg-light" action="{$action_url}" method="post">
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="Username" name="username"{if isset($username) && $username} value="{$username}"{/if} :autofocus="'autofocus'">
                <label for="floatingInput">Username</label>
              </div>
              <div class="form-floating mb-3">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
                <label for="floatingPassword">Password</label>
              </div>
              <button @click="disableButton" class="w-100 btn btn-lg btn-primary" type="submit">Log in</button>
            </form>
          </div>
        </div>
      </div>
    </main>
    {* <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script> *}
    {foreach $login_scripts as $login_script}
      <script src="{$login_script}"></script>
    {/foreach}
    <script>
      if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('sw.js')
      }
    </script>
  </body>
</html>
