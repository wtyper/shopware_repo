{extends file="parent:frontend/index/index.tpl"}
{block name="frontend_index_content_left"}{/block}
{block name="frontend_index_content"}
    <h1>{$name}</h1>
    <p>{$description}</p>
    {if ($image)}
        <img src="/{$image}" alt="{$name}"
             style="max-width: 300px; max-height: 300px">
    {else}
        <img src="/themes/Frontend/Responsive/frontend/_public/src/img/no-picture.jpg"
             alt="{$name}"
             style="max-width: 300px; max-height: 300px">
    {/if}
{/block}
