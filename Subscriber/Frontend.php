<?php

namespace Shopware\SwagFacebook\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Config;
use Enlight_Controller_ActionEventArgs;
use Enlight_Controller_Request_Request;
use Enlight_View_Default;
use Shopware\SwagFacebook\Components\ArticleImageSourceGenerator;
use Shopware_Plugins_Frontend_SwagFacebook_Bootstrap;

class Frontend implements SubscriberInterface
{
    /**
     * @var Shopware_Plugins_Frontend_SwagFacebook_Bootstrap $bootstrap
     */
    private $bootstrap;

    /**
     * @param Shopware_Plugins_Frontend_SwagFacebook_Bootstrap $bootstrap
     */
    public function __construct(Shopware_Plugins_Frontend_SwagFacebook_Bootstrap $bootstrap)
    {
        $this->bootstrap = $bootstrap;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'Enlight_Controller_Action_PostDispatch_Frontend_Detail' => 'onPostDispatchDetail'
        );
    }

    /**
     * Event listener method
     *
     * @param Enlight_Controller_ActionEventArgs $args
     */
    public function onPostDispatchDetail(Enlight_Controller_ActionEventArgs $args)
    {
        $config = $this->bootstrap->Config();
        if (!$config->get('showSwagFacebook')) {
            return;
        }

        $view = $args->getSubject()->View();
        $request = $args->getSubject()->Request();
        $request->clearParams();

        $this->assignConfigDataToView($view, $config, $request);
        $this->addTemplateDir($view);
        $this->addSnippetDir();
        $this->extendsTemplateForShopwareFour($view);
    }

    /**
     * @param Enlight_View_Default $view
     */
    private function extendsTemplateForShopwareFour(Enlight_View_Default $view)
    {
        if (!$this->bootstrap->isTemplateResponsive()) {
            $view->extendsTemplate('frontend/SwagFacebook/blocks_detail.tpl');
            $view->extendsTemplate('frontend/SwagFacebook/header.tpl');
        }
    }

    /**
     * @param Enlight_View_Default $view
     * @param Enlight_Config $config
     * @param Enlight_Controller_Request_Request $request
     */
    private function assignConfigDataToView(
        Enlight_View_Default $view,
        Enlight_Config $config,
        Enlight_Controller_Request_Request $request
    ) {
        $article = $view->getAssign('sArticle');
        $view->assign('swagFacebook_thumbnail', $this->getArticleImageSourceGenerator($request, $article)->getImageSource());
        $view->assign('swagFacebook_unique_id', Shopware()->Shop()->getId() . '_' . $article['articleID']);
        $view->assign('swagFacebook_app_id', $config->get('app_id_SwagFacebook'));
        $view->assign('swagFacebook_showShareButton', $config->get('swagFacebook_showShareButton'));
        $view->assign('swagFacebook_showFaces', $config->get('swagFacebook_showFaces'));
        $view->assign('swagFacebook_showFacebookTab', $this->tabHandling($config, $request));
        // TODO: if the setting "swagFacebookColorscheme" has a effect in the Facebook application, activate the settings in the Bootstrap for the Customer... See function "createForm()"
        $view->assign('swagFacebook_colorScheme', ($config->get('swagFacebook_colorscheme') || 1));
        $this->IE6Fix($view, $request->getHeader('USER_AGENT'));
    }

    /**
     * @param Enlight_Config $config
     * @param Enlight_Controller_Request_Request $request
     *
     * @return bool
     */
    private function tabHandling(Enlight_Config $config, Enlight_Controller_Request_Request $request)
    {
        $showDetailPageComments = $config->get('showDetailPageComments');
        $showFacebookTab = false;
        if ($showDetailPageComments) {
            $showFacebookTab = true;
            $showFacebookTabConfig = $config->get('hideCommentTab');
            if ($showFacebookTabConfig) {
                $pageUrl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getRequestUri();
                $commentCount = $this->getCommentCount($pageUrl);
                if ($commentCount == 0) {
                    $showFacebookTab = false;
                }
            }
        }

        return $showFacebookTab;
    }

    /**
     * @param Enlight_View_Default $view
     */
    private function addTemplateDir(Enlight_View_Default $view)
    {
        if ($this->bootstrap->isShopwareFive() && $this->bootstrap->isTemplateResponsive()) {
            $view->addTemplateDir(__DIR__ . '/../Views/responsive');
        } else {
            $view->addTemplateDir(__DIR__ . '/../Views/emotion');
        }
    }

    /**
     * Add the snippets directory
     */
    private function addSnippetDir()
    {
        Shopware()->Snippets()->addConfigDir(__DIR__ . '/../Snippets/');
    }

    /**
     * Get facebook comment count from facebook graph api for current url
     *
     * @param string $url
     * @return integer
     */
    private function getCommentCount($url)
    {
        //remove additional GET parameters
        if ($pos = strpos($url, '?')) {
            $url = substr($url, 0, $pos);
        }

        $response = file_get_contents('http://graph.facebook.com/?id=' . $url);
        $data = json_decode($response, true);
        $commentsCount = $data['comments'] ? $data['comments'] : 0;

        return $commentsCount;
    }

    /**
     * A little hack for the IE6
     *
     * @param Enlight_View_Default $view
     * @param string $userAgent
     */
    private function IE6Fix(Enlight_View_Default $view, $userAgent)
    {
        if (preg_match("/MSIE 6/", $userAgent)) {
            $view->assign('swagFacebook_hideFacebook', true);
        } else {
            $view->assign('swagFacebook_hideFacebook', false);
        }
    }

    /**
     * @param Enlight_Controller_Request_Request $request
     * @param array $article
     *
     * @return ArticleImageSourceGenerator
     */
    private function getArticleImageSourceGenerator(Enlight_Controller_Request_Request $request, array $article)
    {
        return new ArticleImageSourceGenerator($this->bootstrap, $request, $article);
    }
}
