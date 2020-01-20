{extends file="parent:frontend/listing/product-box/box-basic.tpl"}

{block name="frontend_listing_box_article_price_info"}
    {if !$sHidePrice}
        {$smarty.block.parent}
    {/if}
{/block}
