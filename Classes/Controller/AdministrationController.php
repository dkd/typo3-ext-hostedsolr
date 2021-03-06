<?php
namespace Dkd\Hostedsolr\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Arthur Rehm <arthur.rehm@dkd.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Dkd\Hostedsolr\Domain\Model\Core;
use Dkd\Hostedsolr\Service\HostedsolrService;
use Dkd\Hostedsolr\Utility\TypoScriptFactory;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Administration module controller
 *
 * @author Arthur Rehm <arthur.rehm@gmail.com>
 * @todo: implement dependend <select> like on hosted solr
 */
class AdministrationController extends ActionController
{

    /**
     * An Instance of HostedsolrService;
     *
     * @var HostedsolrService;
     */
    protected $service;

    /**
     * An Instance of TypoScriptParser;
     *
     * @var TypoScriptParser;
     */
    protected $tsparser;

    /**
     * An Instance of \Dkd\Hostedsolr\Utility\TypoScriptFactory;
     *
     * @var TypoScriptFactory;
     */
    protected $typoScriptFactory;

    /**
     * @param HostedsolrService $service
     */
    public function injectHostedsolrService(HostedsolrService $service)
    {
        $this->service = $service;
    }

    /**
     * @param TypoScriptFactory $typoScriptFactory
     */
    public function injectTypoScriptFactory(TypoScriptFactory $typoScriptFactory)
    {
        $this->typoScriptFactory = $typoScriptFactory;
    }

    /**
     * @param TypoScriptParser $tsparser
     */
    public function injectTSparser(TypoScriptParser $tsparser)
    {
        $this->tsparser = $tsparser;
    }

    /**
     * Index Action
     */
    public function indexAction()
    {
        $extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['hostedsolr']);

        if($extConf['apiToken'] == null || $extConf['secretToken'] == null) {
            $this->addFlashMessage("The authentication requires two parameters: api_token and secret_token.", "Hostedsolr API credentials required", AbstractMessage::WARNING);
            $this->redirect('information');
        }

        $this->view->assignMultiple(array(
            'solrVersions' => $this->settings['availableCoreSetups']['TYPO3'],
            'languages' => $this->getLanguages(),
            'solrCores' => $this->service->getAllCores()
        ));
    }

    /**
     * Show Action
     *
     * @param string $name
     * @param string $internalName
     * @param string $password
     * @param string $host
     * @return void
     *
     */
    public function showAction($name, $internalName, $password, $host)
    {
        $array = array(
            'solr' => array(
                'schema' => 'https',
                'port' => '443',
                'path' => '/'.$internalName.'/'.'core/',
                'host' => $internalName.':'.$password.'@'.$host,
            )
        );

        $ts = $this->typoScriptFactory->convertArrayToTypoScript($array, 'plugin.tx_solr');

        $this->tsparser->parse($ts);
        $ts = $this->tsparser->doSyntaxHighlight($ts);

        $this->view->assignMultiple(array(
            'coreName' => $name,
            'typoScript' => $ts
        ));
    }

    /**
     * Create Action for Solr Core
     *
     * @param Core $core
     * @return void
     */
    public function createAction(Core $core)
    {

        $name = $core->getName();
        $schema = $core->getLanguage();
        $variant = $core->getVariant();
        $this->resolveSolrVersion($core);
        $solrVersion = $core->getSolrVersion();

        // Validation
        if(empty($name)){
            $this->addFlashMessage("Name cannot be empty!", "Error", AbstractMessage::ERROR);
            $this->redirect('index');
        }

        // Create Core
        $status = $this->service->createCore($name, $solrVersion, $schema, $variant);

        $message = $status ? LocalizationUtility::translate('message.create.success', 'hostedsolr')
            : LocalizationUtility::translate('message.create.fail', 'hostedsolr');

        $messageType = $status ? AbstractMessage::OK
            : AbstractMessage::ERROR;

        $this->addFlashMessage($message, $name, $messageType);
        $this->redirect('index');

    }

    /**
     * Delete Action for Solr Core
     *
     * @param integer $id
     * @param string $name
     */
    public function deleteAction($id, $name)
    {
        // Delete Core
        $status = $this->service->deleteCoreById($id);

        $message = $status ? LocalizationUtility::translate('message.delete.success', 'hostedsolr')
            : LocalizationUtility::translate('message.delete.fail', 'hostedsolr');

        $messageType = $status ? AbstractMessage::OK
            : AbstractMessage::ERROR;

        $this->addFlashMessage($message, $name, $messageType);

        $this->redirect('index');
    }

    /**
     * Information Action
     */
    public function informationAction()
    {
    }


    /**
     * prepare languages for select box
     *
     * @return array
     */
    public function getLanguages()
    {
        $languages = explode(',', str_replace(' ', '', $this->settings['availableCoreSetups']['TYPO3'][1]['schema']));
        $languages = array_combine($languages, $languages);
        return $languages;
    }

    protected function resolveSolrVersion(Core $core)
    {
        foreach ($this->settings['availableCoreSetups']['TYPO3'] as $key => $setup) {
            if ($setup['variant'] == $core->getVariant()) {
                $core->setSolrVersion($setup['solr_version']);
            }
        }
        $variant = $core->getVariant();

    }

}
