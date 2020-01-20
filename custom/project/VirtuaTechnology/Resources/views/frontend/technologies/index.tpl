{extends file="parent:frontend/index/index.tpl"}
{block name="frontend_index_content_left"}{/block}
{block name="frontend_index_content"}
    <h1>List of technologies</h1>
    <div class="block-group">
        {foreach $sTechnologies as $tech}
            <div class="block" style="width: 25%;">
                <a href="/technologies/{$tech['url']}">
                    <h5>{$tech['name']}</h5>
                    <div>{$tech['description']}</div>
                    {if ($tech['image'])}
                        <img src="/{$tech['image']}" alt="{$tech['name']}"
                             style="max-width: 100px; max-height: 100px">
                    {else}
                        <img src="/themes/Frontend/Responsive/frontend/_public/src/img/no-picture.jpg"
                             alt="{$tech['name']}" style="max-width: 100px; max-height: 100px">
                    {/if}
                </a>
            </div>
        {/foreach}
    </div>
{/block}
