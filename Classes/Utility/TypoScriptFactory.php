<?php
namespace Dkd\Hostedsolr\Utility;

    /***************************************************************
     *  Copyright notice
     *
     *  (c) 2015 Markus Friedrich (arthur.rehm@dkd.de)
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
 * TS Utility
 *
 * @package TYPO3
 */
class TypoScriptFactory {

    /**
     * Converts given array to TypoScript
     *
     * Source: https://gist.github.com/ArminVieweg/442693801bab280e42b7
     *
     * @param array $typoScriptArray The array to convert to string
     * @param string $addKey Prefix given values with given key (eg. lib.whatever = {...})
     * @param integer $tab Internal
     * @param boolean $init Internal
     * @return string TypoScript
     */
    function convertArrayToTypoScript(array $typoScriptArray, $addKey = '', $tab = 0, $init = TRUE) {
        $typoScript = '';
        if ($addKey !== '') {
            $typoScript .= str_repeat("\t", ($tab === 0) ? $tab : $tab - 1) . $addKey . " {\n";
            if ($init === TRUE) {
                $tab++;
            }
        }
        $tab++;
        foreach($typoScriptArray as $key => $value) {
            if (!is_array($value)) {
                if (strpos($value, "\n") === FALSE) {
                    $typoScript .= str_repeat("\t", ($tab === 0) ? $tab : $tab - 1) . "$key = $value\n";
                } else {
                    $typoScript .= str_repeat("\t", ($tab === 0) ? $tab : $tab - 1) . "$key (\n$value\n" . str_repeat("\t", ($tab === 0) ? $tab : $tab - 1) . ")\n";
                }
            } else {
                $typoScript .= $this->convertArrayToTypoScript($value, $key, $tab, FALSE);
            }
        }
        if ($addKey !== '') {
            $tab--;
            $typoScript .= str_repeat("\t", ($tab === 0) ? $tab : $tab - 1) . '}';
            if ($init !== TRUE) {
                $typoScript .= "\n";
            }
        }
        return $typoScript;
    }

}