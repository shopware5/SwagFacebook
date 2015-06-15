{extends file='parent:frontend/index/header.tpl'}

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
