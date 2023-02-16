{include file="header.tpl"}

{$page_title}
<div class="alerts">{$alert}</div>

{if $checkups}
<p class="lead">The following patients are waiting.</p>
  {foreach $checkups as $checkup}
      <div class="card mb-3">
        <div class="row g-0 d-flex align-items-center">
          <div class="col-12 col-sm-6 col-md-6 col-lg-6">
            <div class="card-body">
              <h5 class="card-title"><a href="{$checkup.edit_url}" style="color: inherit;">{$checkup.name}</a></h5>
              {if $checkup.last_checked_up}<p class="card-text"><small class="text-muted">Last checked up on {$checkup.last_checked_up}</small></p>{/if}
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-6 col-lg-6">
            <div class="card-body">
              <div class="d-grid gap-3 col-12">
                <a @click="disableLinkButton" href="{$checkup.checkup_url}" class="btn btn-primary">{if $physician}Check Up{else}Edit Checkup{/if}</a>
                <a @click="disableLinkButton" href="{$checkup.history_url}" class="btn btn-secondary">View History</a>
              </div>
            </div>
          </div>
        </div>
      </div>
  {/foreach}
{else}
<p class="lead">The patients {if $physician}you{else}the physician{/if} will check up will show up here.</p>
{/if}

{include file="footer.tpl"}
