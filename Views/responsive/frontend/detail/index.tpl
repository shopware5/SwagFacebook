{extends file='parent:frontend/detail/index.tpl'}

{block name='frontend_detail_data_attributes'}

    {$smarty.block.parent}

    {if !$swagFacebook_hideFacebook && $swagFacebook_app_id}
        <div class="SwagFacebook--LikeButtonContainer">
            <div id="fb-root"></div>
            <script>
                (function (d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) {
                        return;
                    }
                    js = d.createElement(s);
                    js.id = id;
                    js.src = "//connect.facebook.net/{$Locale}/sdk.js#xfbml=1&version=v2.3&appId={$swagFacebook_app_id}";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));

            </script>

            <div class="fb-like"
                 data-action="like"
                 data-colorscheme="{if $swagFacebook_colorScheme == 2}dark{else}light{/if}"
                 data-href="{url sArticle=$sArticle.articleID title=$sArticle.articleName}"
                 data-layout="standard"
                 data-ref="articleID:{$sArticle.articleID}"
                 data-show-faces="{if $swagFacebook_showFaces == false}false{else}true{/if}"
                 data-share="{if $swagFacebook_showShareButton == false}false{else}true{/if}">
            </div>

        </div>
    {/if}

{/block}
