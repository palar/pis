{include file="header.tpl"}

{$page_title}
<div class="alerts">{$alert}</div>
<form action="{$action_url}" method="post">
  <fieldset class="mb-4">
    <div class="row g-3 mb-3">
      <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <input type="text" class="form-control{if $is_invalid} {$is_invalid}{/if}" id="patient-first-name" placeholder="First name" name="patient_first_name" value="{$patient.first_name|escape}"{if $patient.status != 'published' && $receptionist} :autofocus="'autofocus'"{/if}>
          <label for="patient-first-name">First name</label>
        </div>
      </div>
      <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <input type="text" class="form-control{if $is_invalid} {$is_invalid}{/if}" id="patient-last-name" placeholder="Last name" name="patient_last_name" value="{$patient.last_name|escape}">
          <label for="patient-last-name">Last name</label>
        </div>
      </div>
      <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <input type="text" class="form-control" id="patient-middle-name" placeholder="Middle name" name="patient_middle_name" value="{$patient.middle_name|escape}">
          <label for="patient-middle-name">Middle name</label>
        </div>
      </div>
      <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <select class="form-select" id="patient-sex" name="patient_sex" aria-label="Sex">
            {foreach $patient.sex_options as $sex_option}
              <option value="{$sex_option.value}"{if $sex_option.selected} {$sex_option.selected}{/if}>{$sex_option.name}</option>
            {/foreach}
          </select>
          <label for="patient-sex">Sex</label>
        </div>
      </div>
      <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <input type="date" class="form-control" id="patient-birthdate" name="patient_birthdate" value="{$patient.birthdate}">
          <label for="patient-birthdate">Date of birth (dd/mm/yyyy)</label>
        </div>
      </div>
      <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <select class="form-select" id="patient-civil-status" name="patient_civil_status" aria-label="Civil status">
            {foreach $patient.civil_status_options as $civil_status_option}
                <option value="{$civil_status_option.value}"{if $civil_status_option.selected} {$civil_status_option.selected}{/if}>{$civil_status_option.name}</option>
              {/foreach}
          </select>
          <label for="patient-civil-status">Civil status</label>
        </div>
      </div>
      <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <input type="text" class="form-control" id="patient-street-address" placeholder="Street address" name="patient_street_address" value="{$patient.street_address|escape}">
          <label for="patient-street-address">Street address</label>
        </div>
      </div>
      <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <input type="text" class="form-control" id="patient-town-or-city" placeholder="Town/City" name="patient_town_or_city" value="{$patient.town_or_city|escape}">
          <label for="patient-town-or-city">Town/City</label>
        </div>
      </div>
      <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <input type="text" class="form-control" id="patient-province-or-state" placeholder="Province/State" name="patient_province_or_state" value="{$patient.province_or_state|escape}">
          <label for="patient-province-or-state">Province/State</label>
        </div>
      </div>
      <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <input type="text" class="form-control" id="patient-postal-code" placeholder="ZIP/Postal code" name="patient_postal_code" value="{$patient.postal_code|escape}">
          <label for="patient-postal-code">ZIP/Postal code</label>
        </div>
      </div>
      <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <input type="text" class="form-control" id="patient-country" placeholder="Country" name="patient_country" value="{$patient.country|escape}">
          <label for="patient-country">Country</label>
        </div>
      </div>
      <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <input type="text" class="form-control" id="patient-phone" placeholder="Contact number" name="patient_phone" value="{$patient.phone|escape}">
          <label for="patient-phone">Contact number</label>
        </div>
      </div>
    </div>
  </fieldset>
  <fieldset class="mb-4">
    <legend>In case of emergency</legend>
    <div class="row g-3 mb-3">
      <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <input type="text" class="form-control" id="patient-emergency-first-name" placeholder="First name" name="patient_emergency_first_name" value="{$patient.emergency_first_name|escape}">
          <label for="patient-emergency-first-name">First name</label>
        </div>
      </div>
      <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <input type="text" class="form-control" id="patient-emergency-last-name" placeholder="Last name" name="patient_emergency_last_name" value="{$patient.emergency_last_name|escape}">
          <label for="patient-emergency-last-name">Last name</label>
        </div>
      </div>
      <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <input type="text" class="form-control" id="patient-emergency-relationship" placeholder="Relationship to patient" name="patient_emergency_relationship" value="{$patient.emergency_relationship|escape}">
          <label for="patient-emergency-relationship">Relationship to patient</label>
        </div>
      </div>
      <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-floating">
          <input type="text" class="form-control" id="patient-emergency-phone" placeholder="Contact number" name="patient_emergency_phone" value="{$patient.emergency_phone|escape}">
          <label for="patient-emergency-phone">Contact number</label>
        </div>
      </div>
    </div>
  </fieldset>
  {if isset($checkup.status) && $receptionist}
    <fieldset class="mb-4">
      <legend>Check up (additional information)</legend>
      <div class="row g-3 mb-3">
        <div class="col-12">
          <div class="form-floating">
            <input type="text" class="form-control" id="checkup-symptoms" placeholder="Symptoms" name="checkup_symptoms" value="{$checkup.symptoms|escape}">
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
  {/if}
  <div class="d-grid gap-3 d-md-flex justify-content-md-end mb-3">
    {if isset($patient.incomplete_checkup) && not $patient.incomplete_checkup && $patient.status != 'auto-draft' && $receptionist}<a @click="disableLinkButton" class="btn btn-lg btn-primary w-100" href="{$check_up_link}">Check up</a>{/if}
    <button @click="disableButton" type="submit" class="btn btn-lg btn-primary w-100" name="save_changes">{if $patient.status == 'auto-draft'}Add{else}Update{/if}</button>
    {if $patient.status != 'auto-draft' && not $patient.checked_up_before && not $physician}<a @click="disableLinkButton" class="btn btn-lg btn-secondary trash-link w-100" href="{$trash_link}" role="button" data-no-instant>Move to trash</a>{/if}
  </div>
</form>

{include file="footer.tpl"}
