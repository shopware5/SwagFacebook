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
    public function __construct(Shopware_Plugins_Frontend_SwagFacebook_Bootstrap $bootstrap, Enlight_Controller_Request_Request $request, array $article)
    {
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
        if($this->bootstrap->isShopwareFive()){
            $imageSource = $this->getImageSourceFromSW5Article($this->article);
            if(isset($imageSource)){
                return $imageSource;
            }
        } else {
            $imageSource = $this->getImageSourceFromSW4Article($this->article);
            if(isset($imageSource)){
                return $imageSource;
            }
        }

        $imageSource = $this->createArticleSupplierImageSource();
        if(isset($imageSource)){
            return $imageSource;
        }

        return $this->getDefaultImageSource();
    }

    /**
     * Get the ArticleImage from SW5 Article
     *
     * @param $article
     * @return String|null
     */
    private function getImageSourceFromSW5Article($article)
    {
        if(isset($article['image']['thumbnails'][0]['source'])){
            return $article['image']['thumbnails'][0]['source'];
        }

        if(isset($article['image']['source'])){
            return $article['image']['source'];
        }

        return null;
    }

    /**
     * Get the ArticleImage from SW4 Article
     *
     * @param $article
     * @return String|null
     */
    private function getImageSourceFromSW4Article($article)
    {
        if(isset($article['image']['src'][3])){
            return $article['image']['src'][3];
        }

        if(isset($article['image']['src']['original'])){
            return $article['image']['src']['original'];
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
        if(!$this->article['supplierImg']){
            return null;
        }
        return $this->request->getScheme() . '://'. Shopware()->Config()->get('sHOST') . '/' . $this->article['supplierImg'];
    }

    /**
     * returns the path to the "NoImage" Image
     *
     * @return string
     */
    private function getDefaultImageSource()
    {
        return $this->request->getScheme() . '://'. Shopware()->Config()->get('sHOST') . '/frontend/_resources/images/no_picture.jpg';
    }
}