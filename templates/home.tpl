{include file="header.tpl"}

{$page_title}
{$page_content}

{if $receptionist}
<p>As the receptionist, you can:</p>
<ul>
  <li>Add new patients</li>
  <li>View and update patients’ information</li>
  <li>Move patients with no medical history to the trash</li>
  <li>Permanently delete items from the trash</li>
  <li>Search patients</li>
  <li>Add patients to the checkups folder</li>
  <li>Move (incomplete) checkups to the trash</li>
  <li>View all patients’ medical history</li>
  <li>View individual patient’s medical history</li>
  <li>Update the app’s settings</strong></li>
</ul>
{/if}

{if $physician}
<p>As the physician, you can:</p>
<ul>
  <li>Check up patients listed in the Checkups folder</li>
  <li>Search patients</li>
  <li>View and update patients’ information</li>
  <li>View all patients’ medical history</li>
  <li>View individual patient’s medical history</li>
  <li>Update the app's settings</li>
</ul>
{/if}

{include file="footer.tpl"}
