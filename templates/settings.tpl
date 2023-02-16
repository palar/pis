{include file="header.tpl"}

{$page_title}
<div class="alerts">{$alert}</div>
<form action="{$app_home}settings.php" method="post">
  {foreach $settings as $setting}
    <div class="form-floating mb-3">
      {assign var="input_id" value="{$setting.name|replace:'_':'-'}"}
      <input type="text" class="form-control" id="{$input_id}" placeholder="{$setting.title}" name="{$setting.name}" value="{$setting.value|escape}">
      <label for="{$input_id}">{$setting.title}<label>
    </div>
  {/foreach}
  <button @click="disableButton" type="submit" class="btn btn-lg btn-primary w-100 mb-3" name="save_changes">Update</button>
</form>

{include file="footer.tpl"}
