{extends file='parent:frontend/detail/tabs.tpl'}

{block name="frontend_detail_tabs_navigation_inner"}

    {$smarty.block.parent}

    {*FacebookTab*}
    {block name="swagFacebookTab"}
        {if $swagFacebook_app_id && !$swagFacebook_hideFacebook}
            {if $swagFacebook_showFacebookTab}
                <a class="tab--link" href="#facebook">{s namespace="frontend/SwagFacebook/blocks_detail" name="facebookTabTitle"}{/s}</a>
            {/if}
        {/if}
    {/block}

{/block}

{block name="frontend_detail_tabs_content_inner"}

    {$smarty.block.parent}

    {if $swagFacebook_app_id && !$swagFacebook_hideFacebook}
        {if $swagFacebook_showFacebookTab}
            <div class="tab--container">
                <div class="tab--header">
                    <span>{s namespace="frontend/SwagFacebook/blocks_detail" name="facebookTabTitle"}{/s}</span>
                </div>
                <div class="tab--preview">
                    Facebook
                </div>
                <div class="tab--content">

                    <div class="fb-comments"
                         data-href="{url sArticle=$sArticle.articleID title=$sArticle.articleName}"
                         data-numposts="5"
                         data-colorscheme="{if $swagFacebook_colorScheme == 2}dark{else}light{/if}"
                         data-width="100%"
                         data-order-by="time">
                    </div>

                </div>
            </div>
        {/if}
    {/if}

{/block}
