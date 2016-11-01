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

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Dkd\Hostedsolr\Service;

/**
 * Administration module controller
 *
 * @author Arthur Rehm <arthur.rehm@gmail.com>
 */
class AdministrationController extends ActionController
{

    /**
     * An Instance of \Dkd\Hostedsolr\Service\HostedsolrService;
     *
     * @var \Dkd\Hostedsolr\Service\HostedsolrService;
     */
    protected $service;

    /**
     * An Instance of \TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
     *
     * @var \TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
     */
    protected $tsparser;

    /**
     * An Instance of \Dkd\Hostedsolr\Utility\TypoScriptFactory;
     *
     * @var \Dkd\Hostedsolr\Utility\TypoScriptFactory;
     */
    protected $typoScriptFactory;

    /**
     * @param \Dkd\Hostedsolr\Service\HostedsolrService $service
     */
    public function injectHostedsolrService(\Dkd\Hostedsolr\Service\HostedsolrService $service)
    {
        $this->service = $service;
    }

    /**
     * @param \Dkd\Hostedsolr\Utility\TypoScriptFactory $typoScriptFactory
     */
    public function injectTypoScriptFactory(\Dkd\Hostedsolr\Utility\TypoScriptFactory $typoScriptFactory)
    {
        $this->typoScriptFactory = $typoScriptFactory;
    }

    /**
     * @param \TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser $tsparser
     */
    public function injectTSparser(\TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser $tsparser)
    {
        $this->tsparser = $tsparser;
    }



    /**
     * Index Action
     *
     * @param string $category
     * @return void
     */
    public function indexAction()
    {
        // redirect to information tab
        $extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['hostedsolr']);
        if($extConf['apiToken'] == null || $extConf['secretToken'] == null) {
            $this->addFlashMessage("The authentication requires two parameters: api_token and secret_token.", "Hostedsolr API credentials required", \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
            $this->redirect('information');
        }

        $allCoresFromApi = $this->service->getAllCores();

        $solrVersions = $this->getSolrVersions();
        $languages = $this->getLanguages();


        $this->view->assignMultiple(array(
            'solrVersions' => $solrVersions,
            'languages' => $languages,
            'solrCores' => $allCoresFromApi
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
     * @param \Dkd\Hostedsolr\Domain\Model\Core $core
     * @return void
     */
    public function createAction(\Dkd\Hostedsolr\Domain\Model\Core $core)
    {

        $name = $core->getName();
        $solrVersion = $core->getSolrVersion();
        $schema = $core->getLanguage();

        // Validation
        if(empty($name)){
            $this->addFlashMessage("Name cannot be empty!", "Error", \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->redirect('index');
        }

        // Create Core
        $status = $this->service->createCore($name, $solrVersion, $schema);

        $message = $status ? LocalizationUtility::translate('message.create.success', 'hostedsolr')
            : LocalizationUtility::translate('message.create.fail', 'hostedsolr');

        $messageType = $status ? \TYPO3\CMS\Core\Messaging\AbstractMessage::OK
            : \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR;

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

        $messageType = $status ? \TYPO3\CMS\Core\Messaging\AbstractMessage::OK
            : \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR;

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
     * prepare Solr Core Version for select box
     *
     * @return array
     */
    public function getSolrVersions() {
        return array (
            '4.10' => LocalizationUtility::translate('version.4.10', 'hostedsolr'),
            '4.8' => LocalizationUtility::translate('version.4.8', 'hostedsolr'),
            '3.6' => LocalizationUtility::translate('version.3.6', 'hostedsolr')
        );
    }

    /**
     * prepare languages for select box
     *
     * @return array
     */
    public function getLanguages() {
        return array (
            'german' => LocalizationUtility::translate('language.german', 'hostedsolr'),
            'english' => LocalizationUtility::translate('language.english', 'hostedsolr')
        );
    }

}