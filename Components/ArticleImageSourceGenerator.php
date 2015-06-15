<?php

namespace Shopware\SwagFacebook\Components;

use Enlight_Controller_Request_Request;
use Shopware_Plugins_Frontend_SwagFacebook_Bootstrap;

class ArticleImageSourceGenerator
{
    /**
     * @var Shopware_Plugins_Frontend_SwagFacebook_Bootstrap $bootstrap
     */
    private $bootstrap;

    /**
     * @var Enlight_Controller_Request_Request $request
     */
    private $request;

    /**
     * @var array $article
     */
    private $article;

    /**
     * @var String $imageSource
     */
    private $imageSource;

    /**
     * @param Shopware_Plugins_Frontend_SwagFacebook_Bootstrap $bootstrap
     * @param Enlight_Controller_Request_Request $request
     * @param array $article
     */
    public function __construct(
        Shopware_Plugins_Frontend_SwagFacebook_Bootstrap $bootstrap,
        Enlight_Controller_Request_Request $request,
        array $article
    ) {
        $this->bootstrap = $bootstrap;
        $this->request = $request;
        $this->article = $article;
        $this->setArticleImageSource();
    }

    /**
     * This is the public getter to get the determined imageSource
     *
     * @return String
     */
    public function getImageSource()
    {
        return $this->imageSource;
    }

    /**
     * set the property $this->imageSource
     */
    private function setArticleImageSource()
    {
        $this->imageSource = $this->getArticleImageSource();
    }

    /**
     * Try to get the Article imageSource...
     * if there no image try to get the supplierImage...
     * if there no supplierImage get the default "NoImage" Image
     *
     * @return String
     */
    private function getArticleImageSource()
    {
        $imageSource = $this->getImageSourceFromArticle();
        if (isset($imageSource)) {
            return $imageSource;
        }

        $imageSource = $this->createArticleSupplierImageSource();
        if (isset($imageSource)) {
            return $imageSource;
        }

        return $this->getDefaultImageSource();
    }

    /**
     * try get the ArticleImage from Article
     *
     * @return String|null
     */
    private function getImageSourceFromArticle()
    {
        if (isset($this->article['image']['thumbnails'][0]['source'])) {
            return $this->article['image']['thumbnails'][0]['source'];
        }

        if (isset($this->article['image']['source'])) {
            return $this->article['image']['source'];
        }

        if (isset($this->article['image']['src'][3])) {
            return $this->article['image']['src'][3];
        }

        if (isset($this->article['image']['src']['original'])) {
            return $this->article['image']['src']['original'];
        }

        return null;
    }

    /**
     * use "$this->request->getScheme()" to get the request Scheme Like "http/https"
     * use "Shopware()->Config()->get('sHOST')" to get the Domain Name like "DemoShop.de"
     *
     * @return string|null
     */
    private function createArticleSupplierImageSource()
    {
        if (!$this->article['supplierImg']) {
            return null;
        }
        $basePath = Shopware()->Container()->get('Shop')->getBasePath();

        return $this->request->getScheme() . '://' . Shopware()->Config()->get('sHOST') . $basePath . '/' . $this->article['supplierImg'];
    }

    /**
     * returns the path to the "NoImage" Image
     *
     * @return string
     */
    private function getDefaultImageSource()
    {
        $basePath = Shopware()->Container()->get('Shop')->getBasePath();

        if ($this->bootstrap->isShopwareFive() && $this->bootstrap->isTemplateResponsive()) {
            return $this->getSW5ImagePath($basePath);
        } elseif ($this->bootstrap->isShopwareFive()) {
            return $this->getSW5ImagePath($basePath);
        } else {
            return $this->request->getScheme() . '://' . Shopware()->Config()->get('sHOST') . $basePath . '/templates/_default/frontend/_resources/images/no_picture.jpg';
        }
    }

    /**
     * Create the SW5 "NoImage" Path.
     * Implemented to prevent duplicate code
     *
     * @param $basePath
     * @return string
     */
    private function getSW5ImagePath($basePath)
    {
        return $this->request->getScheme() . '://' . Shopware()->Config()->get('sHOST') . $basePath . '/themes/Frontend/Responsive/frontend/_public/src/img/no-picture.jpg';
    }
}
