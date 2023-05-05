{include file="header.tpl"}

{$page_title}
{* $page_description *}
<div class="alerts">{$alert}</div>
<h3 class="mb-3">
  {if $checkups}
    Medical history of <strong>{$name}</strong>
  {else}
    Medical history of <strong>{$name}</strong> will show up here
  {/if}
</h3>

{if $checkups}
<ul class="list-group">
  {foreach $checkups as $checkup}
    <!-- <li class="list-group-item"><strong><a href="{$checkup.checkup_url}">{$checkup.date}</a></strong>
      <ul>
        <li>Symptoms: {$checkup.symptoms}</li>
      </ul>
    </li> -->
    <div class="card mb-3">
      <div class="card-header">
        <strong><a href="{$checkup.checkup_url}">{$checkup.date}</a></strong>
      </div>
      <div class="card-body">
        <!-- <h5 class="card-title">Special title treatment</h5>
        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
        <a href="#" class="btn btn-primary">Go somewhere</a> -->
        <ul>
          <li>Symptoms: {$checkup.symptoms}</li>
          <li>Blood pressure: {$checkup.blood_pressure}</li>
          <li>Body temperature: {$checkup.body_temperature}</li>
          <li>Weight (kgs.): {$checkup.weight}</li>
          <li>Pulse rate: {$checkup.pulse_rate}</li>
          <li>Medications: {$checkup.medications}</li>
          <li>Findings: {$checkup.findings}</li>
        </ul>
      </div>
    </div>
  {/foreach}
</ul>
{/if}

{include file="footer.tpl"}
