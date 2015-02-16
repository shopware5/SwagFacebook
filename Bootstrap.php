<?php
/*
 * (c) shopware AG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Shopware_Plugins_Frontend_SwagFacebook_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    /**
     * Returns the meta information about the plugin
     * as an array.
     *
     * @return array
     */
    public function getInfo()
    {
        return array(
            'version'   => $this->getVersion(),
            'label'     => $this->getLabel(),
            'name'      => 'SwagFacebook',
            'author'    => 'shopware AG',
            'copyright' => 'Copyright (c) shopware AG',
            'license'   => 'The MIT License (MIT) (http://opensource.org/licenses/MIT)',
            'link'      => 'https://github.com/shopwareLabs/SwagFacebook',
        );
    }

    /**
     * Returns the well-formatted name of the plugin
     * as a sting
     *
     * @return string
     */
    public function getLabel()
    {
        return 'Facebook Integration';
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getVersion()
    {
        $info = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'plugin.json'), true);

        if ($info) {
            return $info['currentVersion'];
        } else {
            throw new Exception('The plugin has an invalid version file.');
        }
    }

    /**
     * Install plugin method
     *
     * @return bool
     */
    public function install()
    {
        $this->createEvents();
        $this->createForm();

        return true;
    }

    /**
     * Register Frontend Dispatch Event
     */
    private function createEvents()
    {
        $this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatch_Frontend_Detail',
            'onPostDispatchDetail'
        );
    }

    /**
     * Create the Plugin Settings Form
     */
    private function createForm()
    {
        $translations = array(
            'en_GB'  => array(
                'showSwagFacebook' => array('label' => 'Show Facebook'),
                'app_id_SwagFacebook' => array('label' => 'Facebook App-ID'),
                'showDetailPageComments' => array('label' => 'Show comments in detail page'),
                'hideCommentTab' => array('label' => 'Only show facebook comments if available'),
            )
        );

        $form = $this->Form();

        $form->setElement('checkbox', 'showSwagFacebook', array('label' => 'Facebook zeigen', 'value' => 1, 'scope' => Shopware_Components_Form::SCOPE_SHOP));
        $form->setElement('text', 'app_id_SwagFacebook', array('label' => 'Facebook App-ID', 'value' => '', 'scope' => Shopware_Components_Form::SCOPE_SHOP));

        $form->setElement('checkbox', 'showDetailPageComments', array('label' => 'Facebook-Kommentare auf Detailseite anzeigen', 'value' => 1, 'scope' => Shopware_Components_Form::SCOPE_SHOP));
        $form->setElement('checkbox', 'hideCommentTab', array('label' => 'Facebook-Kommentare nur anzeigen, falls verfügbar', 'value' => 1, 'scope' => Shopware_Components_Form::SCOPE_SHOP));

        $this->addFormTranslations($translations);
    }

    /**
     * Event listener method
     *
     * @param Enlight_Controller_ActionEventArgs $args
     */
    public function onPostDispatchDetail(Enlight_Controller_ActionEventArgs $args)
    {
        $view = $args->getSubject()->View();
        $request = $args->getSubject()->Request();
        $config = $this->Config();

        if (!$config->get('showSwagFacebook')) {
            return;
        }

        $view->assign('app_id', $config->get('app_id_SwagFacebook'));

        $showDetailPageComments = $config->get('showDetailPageComments');
        if ($showDetailPageComments) {
            $showFacebookTab = true;
            $showFacebookTabConfig = $config->get('hideCommentTab');
            if ($showFacebookTabConfig) {
                $pageUrl = $request->getScheme(). '://' . $request->getHttpHost() . $request->getRequestUri();
                $commentCount = $this->getCommentCount($pageUrl);
                if ($commentCount == 0) {
                    $showFacebookTab = false;
                }
            }
            $view->assign('showFacebookTab', $showFacebookTab);
        }

        if (preg_match("/MSIE 6/", $request->getHeader('USER_AGENT'))) {
            $view->assign('hideFacebook', true);
        } else {
            $view->assign('hideFacebook', false);
        }

        $article = $view->getAssign('sArticle');
        $view->assign('unique_id', Shopware()->Shop()->getId() . '_' . $article['articleID']);

        $view->addTemplateDir(__DIR__.'/Views/');
        Shopware()->Snippets()->addConfigDir($this->Path() . 'Snippets/');
        $view->extendsTemplate('frontend/SwagFacebook/blocks_detail.tpl');

        if (Shopware()->Shop()->getTemplate()->getVersion() == 2) {
            $view->extendsTemplate('frontend/SwagFacebook/header.tpl');
        }
    }

    /**
     * Get facebook comment count from facebook graph api for current url
     *
     * @param string $url
     * @return integer
     */
    private function getCommentCount($url)
    {
        $response = file_get_contents('http://graph.facebook.com/?id=' . $url);
        $data = json_decode($response, true);
        $commentsCount = $data['comments'] ? $data['comments'] : 0;

        return $commentsCount;
    }
}
