{extends file='parent:frontend/detail/tabs.tpl'}

{block name="frontend_detail_tabs_navigation_inner"}

    {$smarty.block.parent}

    {*FacebookTab*}
    {block name="swagFacebookTab"}
        {if $app_id && !$hideFacebook}
            {if $showFacebookTab}
                <a class="tab--link" href="#facebook">{s namespace="frontend/SwagFacebook/blocks_detail" name="facebookTabTitle"}{/s}</a>
            {/if}
        {/if}
    {/block}

{/block}

{block name="frontend_detail_tabs_content_inner"}

    {$smarty.block.parent}

        {if $app_id && !$hideFacebook}
            {if $showFacebookTab}
            <div class="tab--container">
                <div class="tab--header">
                    <h2>{s namespace="frontend/SwagFacebook/blocks_detail" name="facebookTabTitle"}{/s}</h2>
                </div>
                <div class="tab--preview">
                    Facebook
                </div>
                <div class="tab--content">

                    <div class="fb-comments"
                         data-href="{url sArticle=$sArticle.articleID title=$sArticle.articleName}"
                         data-numposts="5"
                         data-colorscheme="{if $swagFacebookColorscheme == 2}dark{else}light{/if}"
                         data-width="100%"
                         data-order-by="time">
                    </div>

                </div>
            </div>
            {/if}
        {/if}

{/block}
