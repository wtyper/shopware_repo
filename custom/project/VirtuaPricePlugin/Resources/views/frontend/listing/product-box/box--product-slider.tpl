{extends file="parent:frontend/listing/product-box/box-product-slider.tpl"}

{block name="frontend_listing_box_article_price_default"}
    {if !$sHidePrice}
        {$smarty.block.parent}
    {/if}
{/block}
