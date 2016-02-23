<?php
namespace Dkd\Hostedsolr\Domain\Model;


    /***************************************************************
     *
     *  Copyright notice
     *
     *  (c) 2016
     *
     *  All rights reserved
     *
     *  This script is part of the TYPO3 project. The TYPO3 project is
     *  free software; you can redistribute it and/or modify
     *  it under the terms of the GNU General Public License as published by
     *  the Free Software Foundation; either version 3 of the License, or
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

/**
 * Core
 */
class Core extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * uid
     *
     * @var integer
     */
    protected $uid;

    /**
     * name
     *
     * @var string
     */
    protected $name = '';

    /**
     * language
     *
     * @var string
     */
    protected $language = '';

    /**
     * solrVersion
     *
     * @var string
     */
    protected $solrVersion = '';

    /**
     * host
     *
     * @var string
     */
    protected $host = '';

    /**
     * isActivated
     *
     * @var string
     */
    protected $isActivated = '';

    /**
     * id
     *
     * @var integer
     */
    protected $id;

    /**
     * Returns the name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \HostedSolr\ApiClient\Domain\Api\Client\Solr\Core $core
     */
    public function mapCoreObject(\HostedSolr\ApiClient\Domain\Api\Client\Solr\Core $core)
    {

        $this->setName($core->getName());
        $this->setLanguage($core->getSchema());
        $this->setSolrVersion($core->getSolrVersion());
        $this->setHost($core->getHost());
        $this->setIsActivated($core->getIsActivated());
        $this->setId($core->getId());
        // Uid is necessary for extbase object mapping!
        $this->setUid($core->getId());

    }
    /**
     * Sets the name
     *
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the language
     *
     * @return string $language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Sets the language
     *
     * @param string $language
     * @return void
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Returns the solrVersion
     *
     * @return string $solrVersion
     */
    public function getSolrVersion()
    {
        return $this->solrVersion;
    }

    /**
     * Sets the solrVersion
     *
     * @param string $solrVersion
     * @return void
     */
    public function setSolrVersion($solrVersion)
    {
        $this->solrVersion = $solrVersion;
    }

    /**
     * Returns the host
     *
     * @return string $host
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Sets the host
     *
     * @param string $host
     * @return void
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * Returns the isActivated
     *
     * @return string $isActivated
     */
    public function getIsActivated()
    {
        return $this->isActivated;
    }

    /**
     * Sets the isActivated
     *
     * @param string $isActivated
     * @return void
     */
    public function setIsActivated($isActivated)
    {
        $this->isActivated = $isActivated;
    }

    /**
     * @param integer $uid
     * return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns the id
     *
     * return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Uid
     *
     * @param integer $uid
     * return void
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }
}