{include file="header.tpl"}

<div class="d-print-none">
{$page_title}
</div>

{if $checkup.status == 'published' && $physician}
<div>
  <div class="mb-4">
    <img src="/images/bmalinaomedclinic.png" style="width: 70%; display: block;" class="mx-auto mb-3">
    <div class="text-center">
      <div><i data-feather="map-pin"></i> {if $street_address}{$street_address}{/if}{if $town}, {$town}{/if}{if $zip_code}, {$zip_code}{/if}{if $town} {$town}{/if}</div>
      {if $phone}<div><i data-feather="phone"></i> {$phone}</div>{/if}
    </div>
  </div>
  {if $print == 'all'}
    <h3 class="text-center text-uppercase mb-4">Medical Checkup</h3>
    <div style="margin-bottom: 4rem;">
      <div class="row mb-3">
        <div class="col-3">Name: </div>
        <div class="col-9">{$patient.name}</div>
      </div>
      <div class="row mb-3">
        <div class="col-3">Date: </div>
        <div class="col-9">{$checkup.date}</div>
      </div>
      <div class="row mb-3">
        <div class="col-3">Symptoms: </div>
        <div class="col-9">{$checkup.symptoms}</div>
      </div>
      <div class="row mb-3">
        <div class="col-3">Blood pressure: </div>
        <div class="col-9">{$checkup.blood_pressure}</div>
      </div>
      <div class="row mb-3">
        <div class="col-3">Body temperature: </div>
        <div class="col-9">{$checkup.body_temperature}</div>
      </div>
      <div class="row mb-3">
        <div class="col-3">Weight (kgs.): </div>
        <div class="col-9">{$checkup.weight}</div>
      </div>
      <div class="row mb-3">
        <div class="col-3">Height (ft.): </div>
        <div class="col-9">{$checkup.height}</div>
      </div>
      <div class="row mb-3">
        <div class="col-3">Pulse rate: </div>
        <div class="col-9">{$checkup.pulse_rate}</div>
      </div>
      <div class="row mb-3">
        <div class="col-3">Blood type: </div>
        <div class="col-9">{$checkup.blood_type}</div>
      </div>
      <div class="row mb-3">
        <div class="col-3">Medications: </div>
        <div class="col-9">{$medications}</div>
      </div>
      <div class="row mb-3">
        <div class="col-3">Findings: </div>
        <div class="col-9">{$findings}</div>
      </div>
    </div>
  {else}
    <div style="margin-bottom: 4rem;">
      <div class="row mb-3">
        <div class="col-3">Name: </div>
        <div class="col-9">{$patient.name}</div>
      </div>
      <div class="row mb-3">
        <div class="col-3">Date: </div>
        <div class="col-9">{$checkup.date}</div>
      </div>
      <div class="row mb-3">
        <div class="col-3">Medications: </div>
        <div class="col-9">{$medications}</div>
      </div>
    </div>
  {/if}
  <div class="row mb-3">
    <div class="col-6"></div>
    <div class="col-6 text-center">
      <div>{$physician_name}</div>
      <hr class="m-auto">
      <div>Attending Physician</div>
    </div>
  </div>
  <div class="d-grid gap-3 d-md-flex justify-content-md-end mb-3 d-print-none">
    <button type="button" class="btn btn-lg btn-primary print-button w-100" onclick="window.print()">Print</button>
  </div>
</div>
{/if}

{include file="footer.tpl"}
