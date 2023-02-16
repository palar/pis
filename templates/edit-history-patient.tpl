{include file="header.tpl"}

{$page_title}
{* $page_description *}
<div class="alerts">{$alert}</div>
<p class="lead">
  {if $checkups}
    This is the medical history of <strong>{$name}</strong>.
  {else}
    The medical history of <strong>{$name}</strong> will show up here.
  {/if}
</p>

{if $checkups}
<ul class="list-group">
  {foreach $checkups as $checkup}
    <li class="list-group-item"><strong><a href="{$checkup.checkup_url}">{$checkup.date}</a></strong></li>
  {/foreach}
</ul>
{/if}

{include file="footer.tpl"}
