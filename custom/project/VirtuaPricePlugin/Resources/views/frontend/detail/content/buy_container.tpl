{extends file="parent:frontend/detail/content/buy_container.tpl"}

{block name="frontend_detail_index_data"}
    {if !$sHidePrice}
        {$smarty.block.parent}
    {/if}
{/block}
