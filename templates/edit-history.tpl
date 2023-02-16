{include file="header.tpl"}

{$page_title}
<div class="alerts">{$alert}</div>

{if not $checkups}
<p class="lead">The patients {if $physician}you{else}the physician{/if} check up will show up here.</p>
{else}
<p class="lead">This is the medical history of all patients.</p>
{foreach $checkups as $checkup_details}
  {foreach $checkup_details as $date => $checkup}
    <div class="card mb-3">
      <div class="card-header">
        {if $smarty.now|date_format:"%Y%m%d" == $date|date_format:"%Y%m%d"}Today - {/if}{$date}
      </div>
      <ul class="list-group list-group-flush">
      {foreach from=$checkup key=k item=v}
        <li class="list-group-item">
          {$v.time}{if $v.time|count_characters:true == 7}&nbsp;&nbsp;{/if}&emsp;&emsp;<strong><a href="{$v.checkup_url}">{$v.name}</a></strong>
        </li>
      {/foreach}
      </ul>
    </div>
  {/foreach}
{/foreach}
{/if}

{include file="footer.tpl"}
