{include file="header.tpl"}

{$page_title}
{$page_description}
<div class="alerts">{$alert}</div>

{if not $patients && not $checkups}
<p class="lead">The patients and/or checkups you think are mistakes will show up here.</p>
{/if}

{if $patients}
<div class="mb-3">
  <h4>Patients</h4>
    {foreach $patients as $patient}
        <div class="card mb-3">
          <div class="row g-0 d-flex align-items-center">
            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
              <div class="card-body">
                <h5 class="card-title">{$patient.name}</h5>
                {* <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p> *}
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
              <div class="card-body">
                <div class="d-grid gap-3 col-12">
                  <a @click="disableLinkButton" class="btn btn-primary" href="{$patient.restore_url}" data-no-instant>Restore</a>
                  <a @click="disableLinkButton" class="btn btn-danger" href="{$patient.delete_url}" data-no-instant>Delete</a>
                </div>
              </div>
            </div>
          </div>
        </div>
    {/foreach}
</div>
{/if}

{if $checkups}
<div class="mb-3">
  <h4>Checkups</h4>
    {foreach $checkups as $checkup}
        <div class="card mb-3">
          <div class="row g-0 d-flex align-items-center">
            <div class="col">
              <div class="card-body">
                <h5 class="card-title">{$checkup.name}</h5>
                {* <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p> *}
              </div>
            </div>
            <div class="col">
              <div class="card-body">
                <a @click="disableLinkButton" class="btn btn-primary w-100 mb-3" href="{$checkup.restore_url}" data-no-instant>Restore</a>
                <a @click="disableLinkButton" class="btn btn-danger w-100" href="{$checkup.delete_url}" data-no-instant>Delete</a>
              </div>
            </div>
          </div>
        </div>
    {/foreach}
</div>
{/if}

{include file="footer.tpl"}
