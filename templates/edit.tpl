{include file="header.tpl"}

{$page_title}
{$page_description}
<div class="alerts">{$alert}</div>
{if $patients || $search_query}
  <form action="{$app_home}edit.php" method="get" class="d-flex col-lg-12 mb-3 ms-auto" role="search">
    <input class="form-control form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search_query" value="{$search_query|escape}">
    <button @click="disableButton" class="btn btn-primary" type="submit">Search</button>
  </form>
{/if}
{if not $patients}
<p class="lead">
  {if $search_query}
    No patients found.
  {else}
    {if $receptionist}
      Go <a href="{$add_new_patient}">add new</a> patients! The patients you add will show up here.
    {else}
      The patients the receptionist adds will show up here.
    {/if}
  {/if}
</p>
{else}
  {foreach $patients as $patient}
    <div class="card mb-3">
      <div class="row g-0 d-flex align-items-center">
        <div class="col-12 col-sm-6 col-md-6 col-lg-6">
          <div class="card-body">
            <h5 class="card-title"><a href="{$patient.edit_url}" style="color: inherit;">{$patient.name}</a></h5>
            <p class="card-text">
              <small class="text-muted">
                {if $patient.last_checked_up}
                  Last checked up on {$patient.last_checked_up}
                {else}
                  Added on {$patient.added_on}
                {/if}
              </small>
            </p>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-6">
          <div class="card-body text-end">
            <div class="d-grid gap-2">
            {if not $physician}
              {if not $patient.incomplete_checkup}
                <a @click="disableLinkButton" class="btn btn-primary" href="{$patient.check_up_url}">Check Up</a>
              {else}
                <a @click="disableLinkButton" class="btn btn-primary disabled">Added to Checkups</a>
              {/if}
            {/if}
            <a @click="disableLinkButton" class="btn btn-secondary" href="{$patient.history_url}">View History</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  {/foreach}
{/if}

{include file="footer.tpl"}
