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
     * Reads the Version of the Plugin from the plugin.json and returns it
     *
     * @return string
     * @throws Exception
     */
    public function getVersion()
    {
        $info = json_decode(file_get_contents(__DIR__ . '/plugin.json'), true);

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
        $this->subscribeEvent('Enlight_Controller_Front_DispatchLoopStartup', 'onStartDispatch');
    }

    /**
     * Register SubscriberClasses
     */
    public function onStartDispatch()
    {
        $this->registerPluginNamespace();

        $subscribers = array(
            new \Shopware\SwagFacebook\Subscriber\Frontend($this),
            new \Shopware\SwagFacebook\Subscriber\Less()
        );

        foreach ($subscribers as $subscriber) {
            $this->Application()->Events()->addSubscriber($subscriber);
        }
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
                'swagFacebook_showShareButton' => array('label' => 'Show Like Button'),
                'swagFacebook_showFaces' => array('label' => 'Show Faces'),
                'swagFacebook_colorscheme' => array('label' => 'color scheme')
            )
        );

        $form = $this->Form();

        $form->setElement('text', 'app_id_SwagFacebook', array('label' => 'Facebook App-ID', 'value' => '', 'scope' => Shopware_Components_Form::SCOPE_SHOP));
        $form->setElement('checkbox', 'showSwagFacebook', array('label' => 'Facebook zeigen', 'value' => 1, 'scope' => Shopware_Components_Form::SCOPE_SHOP));
        $form->setElement('checkbox', 'swagFacebook_showShareButton', array('label' => 'Teilen Button zeigen', 'value' => 1, 'scope' => Shopware_Components_Form::SCOPE_SHOP));
        $form->setElement('checkbox', 'swagFacebook_showFaces', array('label' => 'Bilder "Gesichter" zeigen', 'value' => 1, 'scope' => Shopware_Components_Form::SCOPE_SHOP));
        $form->setElement('checkbox', 'showDetailPageComments', array('label' => 'Facebook-Kommentare auf Detailseite anzeigen', 'value' => 1, 'scope' => Shopware_Components_Form::SCOPE_SHOP));
        $form->setElement('checkbox', 'hideCommentTab', array('label' => 'Facebook-Kommentare nur anzeigen, falls verfügbar', 'value' => 0, 'scope' => Shopware_Components_Form::SCOPE_SHOP));
        $form->setElement('select', 'swagFacebook_colorscheme', array('label' => 'Farbschema', 'store' => array(array(1,'light'), array(2, 'dark')), 'scope' => Shopware_Components_Form::SCOPE_SHOP));

        $this->addFormTranslations($translations);
    }

    /**
     * Check if the environment is Shopware 5
     *
     * @return mixed
     */
    public function isShopwareFive()
    {
        return version_compare(Shopware()->Config()->get('Version'), '5.0.0', '>=');
    }

    /**
     * Registers the plugin namespace to the application loader.
     */
    public function registerPluginNamespace()
    {
        $this->Application()->Loader()->registerNamespace(
            'Shopware\SwagFacebook',
            $this->Path()
        );
    }
}
