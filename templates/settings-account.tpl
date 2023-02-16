{include file="header.tpl"}

{$page_title}
<div class="alerts">{$alert}</div>
<form action="{$app_home}settings.php?page=account" method="post">
  <fieldset class="mb-4">
    <div class="form-floating mb-3">
      <input type="text" class="form-control" id="user-username" placeholder="Username" name="user_username" value="{$username|escape}" disabled>
      <label for="user-username">Username</label>
    </div>
    <div class="form-floating mb-3">
      <input type="text" class="form-control text-capitalize" id="user-role" placeholder="Role" name="user_role" value="{$role|escape}" disabled>
      <label for="user-role">Role</label>
    </div>
  </fieldset>
  <fieldset class="mb-4">
    <legend>Change password</legend>
    <div class="form-floating mb-3">
      <input type="password" class="form-control" id="user-current-password" placeholder="Current" name="user_current_password" value="{$user_current_password|escape}">
      <label for="user-current-password">Current</label>
    </div>
    <div class="form-floating mb-3">
      <input type="password" class="form-control" id="user-new-password" placeholder="New" name="user_new_password" value="{$user_new_password|escape}">
      <label for="user-new-password">New</label>
    </div>
    <div class="form-floating mb-3">
      <input type="password" class="form-control" id="user-new-password-confirm" placeholder="Re-type new" name="user_new_password_confirm">
      <label for="user-new-password-confirm">Re-type new</label>
    </div>
  </fieldset>
  <button @click="disableButton" type="submit" class="btn btn-lg btn-primary w-100 mb-3" name="save_changes">Save changes</button>
</form>

{include file="footer.tpl"}
