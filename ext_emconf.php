<?php
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

/**
 * ext_emconf file for hostedsolr
 *
 * @package TYPO3
 * @subpackage tx_hostedsolr
 * @author Arthur Rehm <arthur.rehm@dkd.de>
 */

$EM_CONF[$_EXTKEY] = array(
  'title' => 'Hosted Solr',
  'description' => '',
  'category' => 'misc',
  'author' => 'Arthur Rehm',
  'author_email' => 'arthur.rehm@dkd.de',
  'shy' => '',
  'dependencies' => '',
  'conflicts' => '',
  'priority' => 'bottom',
  'module' => '',
  'state' => 'beta',
  'internal' => '',
  'uploadfolder' => 0,
  'createDirs' => '',
  'modify_tables' => 'pages,pages_language_overlay',
  'clearCacheOnLoad' => 0,
  'lockType' => '',
  'author_company' => 'dkd Internet Service GmbH',
  'version' => '0.4.1',
  'constraints' => array(
    'depends' => array(
      'typo3' => '7.6.0-8.7.99',
    ),
    'conflicts' => array(
      'coreapi' => '',
    ),
    'suggests' => array(
    )
  )
);