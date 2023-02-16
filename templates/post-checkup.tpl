{include file="header.tpl"}

{$page_title}
<div class="alerts">{$alert}</div>
<div class="d-grid gap-3 d-md-flex justify-content-md-end mb-3">
  {if $checkup.status == 'published' && $physician}<a @click="disableLinkButton" class="btn btn-lg btn-primary w-100" href="{$print_link}">Print <small>(Medications)</small></a><a @click="disableLinkButton" class="btn btn-lg btn-primary w-100" href="{$print_link_all}">Print <small>(All)</small></a>{/if}
</div>
<form action="{$action_url}" method="post" class="d-print-none">
  <fieldset class="mb-4">
    <div class="form-floating mb-3">
      <input type="text" class="form-control" id="patient-name" placeholder="Name" name="patient_name" value="{$patient.name|escape}" disabled>
      <label for="patient-name">Name</label>
    </div>
    {if $checkup.status == 'published'}
      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="checkup-date" placeholder="Date" name="checkup_date" value="{$checkup.date|escape}" disabled>
        <label for="checkup-date">Date</label>
      </div>
    {/if}
  </fieldset>
  <fieldset class="mb-4">
    <legend>Additional information</legend>
    <div class="row g-3 mb-3">
      <div class="col-12">
        <div class="form-floating">
          <input type="text" class="form-control" id="checkup-symptoms" placeholder="Symptoms" name="checkup_symptoms" value="{$checkup.symptoms|escape}"{if $receptionist && $checkup.status != 'published'} :autofocus="'autofocus'"{/if}>
          <label for="checkup-symptoms">Symptoms</label>
        </div>
      </div>
      <div class="col-6 col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <input type="text" class="form-control" id="checkup-blood-pressure" placeholder="Blood pressure" name="checkup_blood_pressure" value="{$checkup.blood_pressure|escape}">
          <label for="checkup-blood-pressure">Blood pressure</label>
        </div>
      </div>
      <div class="col-6 col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <input type="text" class="form-control" id="checkup-body-temperature" placeholder="Body temperature" name="checkup_body_temperature" value="{$checkup.body_temperature|escape}">
          <label for="checkup-body-temperature">Body temperature</label>
        </div>
      </div>
      <div class="col-6 col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <input type="text" class="form-control" id="checkup-weight" placeholder="Weight (kgs.)" name="checkup_weight" value="{$checkup.weight|escape}">
          <label for="checkup-weight">Weight (kgs.)</label>
        </div>
      </div>
      <div class="col-6 col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <input type="text" class="form-control" id="checkup-height" placeholder="Height (ft.)" name="checkup_height" value="{$checkup.height|escape}">
          <label for="checkup-height">Height (ft.)</label>
        </div>
      </div>
      <div class="col-6 col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <input type="text" class="form-control" id="checkup-pulse-rate" placeholder="Pulse rate" name="checkup_pulse_rate" value="{$checkup.pulse_rate|escape}">
          <label for="checkup-pulse-rate">Pulse rate</label>
        </div>
      </div>
      <div class="col-6 col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <input type="text" class="form-control" id="checkup-blood-type" placeholder="Blood type" name="checkup_blood_type" value="{$checkup.blood_type|escape}">
          <label for="checkup-blood-type">Blood type</label>
        </div>
      </div>
    </div>
  </fieldset>
  <div class="mb-4"{if $receptionist} style="display: none;"{/if}>
    <legend>Physician’s</legend>
    <div class="form-floating mb-3">
      <textarea class="form-control" placeholder="Symptoms" id="checkup-medications" style="height: 150px" name="checkup_medications"{if $receptionist} readonly{/if}>{$checkup.medications|escape}</textarea>
      <label for="checkup-medications">Medications</label>
    </div>
    <div class="form-floating mb-3">
      <textarea class="form-control" placeholder="Findings" id="checkup-findings" style="height: 150px" name="checkup_findings"{if $receptionist} readonly{/if}>{$checkup.findings|escape}</textarea>
      <label for="checkup-findings">Findings</label>
    </div>
  </div>
  <div class="d-grid gap-3 d-md-flex justify-content-md-end mb-3">
    <button @click="disableButton" type="submit" class="btn btn-lg btn-primary w-100" name="save_changes">{if $checkup.status == 'auto-draft'}Add to Checkups{else}{if $receptionist}Update{/if}{if $physician && $checkup.status == 'draft'}Done{else if $physician && $checkup.status == 'published'}Update{/if}{/if}</button>
    {if $checkup.status != 'auto-draft' && $checkup.status == 'draft' && $receptionist}<a @click="disableLinkButton" class="btn btn-lg btn-secondary trash-link w-100" href="{$trash_link}" role="button" data-no-instant>Move to trash</a>{/if}
  </div>
</form>

{include file="footer.tpl"}
