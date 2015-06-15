{block name='frontend_index_header_meta_tags'}

    {$smarty.block.parent}

    {if $swagFacebook_app_id}
        <meta property="fb:app_id" content="{$swagFacebook_app_id}"/>
        <meta property="og:image" content="{$swagFacebook_thumbnail}"/>
        <meta property="og:type" content="product"/>
        <meta property="og:url" content="{url sArticle=$sArticle.articleID title=$sArticle.articleName}"/>
        <meta property="og:title" content="{$sArticle.articleName|escape} | {$sShopname}"/>
        <meta property="og:description" content="{$sArticle.description_long|strip_tags|truncate:200}"/>
    {/if}
{/block}

{block name="frontend_detail_index_actions"}

    {$smarty.block.parent}

    {if $swagFacebook_app_id && !$swagFacebook_hideFacebook}
        <div id="SwagFacebookMarginTopContainer">
            <fb:like href="{url sArticle=$sArticle.articleID}" send="send" layout="button_count" width="250" show_faces="{if $swagFacebook_showFaces == false}false{else}true{/if}"></fb:like>
        </div>
    {/if}

{/block}

{block name="frontend_detail_index_tabs_related"}

    {$smarty.block.parent}

    {if $swagFacebook_app_id && !$swagFacebook_hideFacebook}
        {if $swagFacebook_showFacebookTab}
            <div id="facebook">
                <h2>{s namespace="frontend/SwagFacebook/blocks_detail" name="facebookTabTitle"}{/s}</h2>

                <div class="container">
                    <div id="fb-root"></div>
                    <script src="//connect.facebook.net/{$Locale}/all.js#appId={$swagFacebook_app_id}&amp;xfbml=1"></script>
                    <fb:comments href="{url sArticle=$sArticle.articleID title=$sArticle.articleName}" migrated="1" xid="{$swagFacebook_unique_id}" width="560"></fb:comments>
                </div>
            </div>
        {/if}
    {/if}

{/block}

{block name="frontend_detail_tabs_rating"}
    {$smarty.block.parent}
    {if $swagFacebook_app_id && !$swagFacebook_hideFacebook}
        {if $swagFacebook_showFacebookTab}
            <li>
                <a href="#facebook">{s namespace="frontend/SwagFacebook/blocks_detail" name="facebookTabTitle"}{/s}</a>
            </li>
        {/if}
    {/if}
{/block}