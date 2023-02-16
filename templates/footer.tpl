          </main>
        </div>
      </div>
    </div>
    {* <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script> *}
    {if $app_scripts}
      {foreach $app_scripts as $admin_script}
        <script src="{$admin_script}"></script>
      {/foreach}
      {if $app_inline_scripts}{$app_inline_scripts}{/if}
    {/if}
    {if isset($print_inline_scripts) && $print_inline_scripts}{$print_inline_scripts}{/if}
    <script>
      if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('sw.js')
      }
    </script>
  </body>
</html>
