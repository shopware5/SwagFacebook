{extends file='parent:frontend/index/header.tpl'}

{block name='frontend_index_header_meta_tags'}
    {$smarty.block.parent}

    {if $app_id}
        <meta property="fb:app_id"      content="{$app_id}" />
    {/if}

    {* Add main image *}
    {if $sArticle.image}
        <meta property="og:image" content="{$sArticle.image.source}" />
    {else}
        {if $sArticle.supplierImg}
            <meta property="og:image" content="{$sArticle.supplierImg}" />
        {else}
            <meta property="og:image" content="{link file='frontend/_resources/images/no_picture.jpg'}" />
        {/if}
    {/if}

    <meta property="og:type"            content="product" />
    <meta property="og:url"             content="{url sArticle=$sArticle.articleID title=$sArticle.articleName}" />
    <meta property="og:title"           content="{$sArticle.articleName|escape} | {$sShopname}" />
    <meta property="og:description"     content="{$sArticle.description_long|strip_tags|truncate:200}" />
{/block}
