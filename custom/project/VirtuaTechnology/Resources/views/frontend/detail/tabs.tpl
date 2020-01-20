{extends file="parent:frontend/detail/tabs.tpl"}
{block name="frontend_detail_tabs_description"}
    {$smarty.block.parent}
    <a href="#" class="tab--link" title="Technologies" data-tabName="technologies">Virtua Technologies</a>
{/block}
{block name="frontend_detail_tabs_content_description"}
    {$smarty.block.parent}
    <div class="tab--container">
        <div class="tab--header">
            <a href="#" class="tab--title" title="Virtua Technologies">Virtua Technologies</a>
        </div>
        <div class="tab--content" style="display: flex">
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Link</th>
                </tr>
                </thead>
                <tbody>
                {foreach from=$sArticle['virtua_technology'] item=tech}
                    <tr>
                        <td>{$tech['name']}</td>
                        <td>{$tech['description']}</td>
                        <td>
                            {if ($tech['image'])}
                                <img src="/{$tech['image']}" alt="{$tech['name']}"
                                     style="max-width: 100px; max-height: 100px">
                            {else}
                                <img src="/themes/Frontend/Responsive/frontend/_public/src/img/no-picture.jpg"
                                     alt="{$tech['name']}" style="max-width: 100px; max-height: 100px">
                            {/if}
                        </td>
                        <td>
                            <a href="/technologies/{$tech['url']}" class="btn is--primary">click me</a>
                        </td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
    </div>
{/block}
