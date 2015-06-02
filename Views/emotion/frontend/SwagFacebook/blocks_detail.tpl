{block name='frontend_index_header_meta_tags'}
    {$smarty.block.parent}
    {* Article name *}
    <meta property="og:title" content="{$sArticle.articleName|escape} | {$sShopname}" />

    {* Article is a product *}
    <meta property="og:type" content="product" />

    {* Product URL, same as the canonical URL *}
    <meta property="og:url" content="{url sArticle=$sArticle.articleID title=$sArticle.articleName}" />

    {* Description *}
    <meta property="og:description" content="{$sArticle.description_long|strip_tags|truncate:200}" />

    {* Set Application ID if set *}
    {if $app_id}
        <meta property="fb:app_id" content="{$app_id}"/>
    {/if}

    {* Add main image *}
    {if $sArticle.image.src.3}
        <meta property="og:image" content="{$sArticle.image.src.3}" />
    {else}
        <meta property="og:image" content="{link file='frontend/_resources/images/no_picture.jpg'}" />
    {/if}
{/block}

{block name="frontend_detail_index_actions"}
    {$smarty.block.parent}
    {if !$hideFacebook}
        <div id="SwagFacebookMarginTopContainer">
            <script>

                (function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) {
                        return;
                    }
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/{$Locale}/sdk.js#xfbml=1&version=v2.3&appId={$app_id}";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));

            </script>

            <div class="fb-like"
                 data-action="like"
                 data-colorscheme="{if $swagFacebookColorscheme == 2}dark{else}light{/if}"
                 data-href="{url sArticle=$sArticle.articleID title=$sArticle.articleName}"
                 data-layout="standard"
                 data-ref="articleID:{$sArticle.articleID}"
                 data-show-faces="{if $swagFbShowFaces == false}false{else}true{/if}"
                 data-share="{if $swagFbShowShareButton == false}false{else}true{/if}">
            </div>

        </div>
    {/if}
{/block}

{block name="frontend_detail_index_tabs_related"}
    {$smarty.block.parent}
    {if $app_id && !$hideFacebook}
        {if $showFacebookTab}
        <div id="facebook">
            <h2>{s namespace="frontend/SwagFacebook/blocks_detail" name="facebookTabTitle"}{/s}</h2>
            <div class="container">
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

{block name="frontend_detail_tabs_rating"}
    {$smarty.block.parent}
    {if $app_id && !$hideFacebook}
        {if $showFacebookTab}
        <li>
            <a href="#facebook">{s namespace="frontend/SwagFacebook/blocks_detail" name="facebookTabTitle"}{/s}</a>
        </li>
        {/if}
    {/if}
{/block}
