{extends file="parent:frontend/detail/data.tpl"}
{block name="frontend_widgets_delivery_infos"}
    {debug}
    {if $sArticle['estimated_delivery_time']}
        {if $sArticle['ship_today']}
            <b>Will be shipped today.</b>
            <br>
        {/if}
        Estimated delivery date:
        <b>{$sArticle['estimated_delivery_time']}</b>
    {else}
        {$smarty.block.parent}
    {/if}
{/block}
