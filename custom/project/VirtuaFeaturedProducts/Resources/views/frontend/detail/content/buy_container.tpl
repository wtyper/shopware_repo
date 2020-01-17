{extends file="parent:frontend/detail/content/buy_container.tpl"}

{block name='frontend_detail_index_buy_container_base_info'}
    {$smarty.block.parent}
    <li class="base-info--entry entry-attribute">
        <strong class="entry--label">
            {s name="virtua_is_featured"}{/s}:
        </strong>
        <span class="entry--content">
            {if $sArticle.virtua_is_featured }
                {s name="virtua_yes"}{/s}
            {else}
                {s name="virtua_no"}{/s}
            {/if}
        </span>
    </li>
        <div class="panel has--border is--rounded">
            <div class="panel--title is--underline">Featured Products</div>
            {include file="frontend/_includes/product_slider.tpl" articles=$sFeaturedArticles}
        </div>
{/block}
