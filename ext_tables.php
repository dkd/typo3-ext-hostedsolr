<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}
\TYPO3\CMS\Core\Utility\DebugUtility::debug($_EXTKEY,"EXTKEY");


if (TYPO3_MODE == 'BE') {
    $fileExtension = version_compare(TYPO3_branch, '7.0', '>=') ? 'svg' : 'png';
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'Dkd.' . $_EXTKEY,
        'tools',
        'administration',
        '',
        array(
            // An array holding the controller-action-combinations that are accessible
            'Administration' => 'index, create, show, edit, delete, cancel, information'
        ),
        array(
            'access' => 'admin',
            'icon' => 'EXT:' . $_EXTKEY . '/Resources/Public/Images/Icons/ModuleAdministration.' . $fileExtension,
            'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/ModuleAdministration.xlf',
        )
    );

}