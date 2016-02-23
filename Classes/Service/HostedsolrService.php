<?php

namespace Dkd\Hostedsolr\Service;

use TYPO3\CMS\Core\SingletonInterface;

/* *
 *  Hostedsolr API Service
 *
 *
 */
class HostedsolrService implements SingletonInterface
{


    /**
     * Hostedsolr Service
     * @var \HostedSolr\ApiClient\Domain\Api\Service
     */
    protected $service;


    /**
     * Constructor Hostedsolr Service
     *
     */
    function __construct() {
        $this->initService();
    }

    /**
     * find the apiKey in extension configuration
     *
     * @return void
     * @throws  \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException
     */
    public function initService() {
        $extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['hostedsolr']);


        if($extConf['apiToken'] !== null || $extConf['secretToken'] !== null) {

            $this->service  = \HostedSolr\ApiClient\Factory::getApiService($extConf['apiToken'],$extConf['secretToken']);

        }else {
            throw new \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException("You must set an API and Secret Token", 1);
        }
    }

    /**
     * Create a Solr Core
     * @param $name
     * @param string $solrVersion
     * @param string $schema
     * @return boolean
     */
    public function createCore($name, $solrVersion = '4.8', $schema = 'english')
    {
        return $this->service->createNewCore($name, $system = 'typo3',$solrVersion, $schema);
    }

    /**
     * @param integer $id
     */
    public function deleteCoreById($id)
    {
        return $this->service->deleteCoreById($id);
    }

    /**
     * Return Service
     * @return HostedSolr\ApiClient\Domain\Api\Service
     */
    public function getApiService()
    {
        return $this->service;
    }

    public function getAllCores()
    {
        return $this->service->getAllCores();
    }





}