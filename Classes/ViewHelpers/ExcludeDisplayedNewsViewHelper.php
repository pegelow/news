<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2013 Georg Ringer <typo3@ringerge.org>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * ViewHelper to exclude news items in other plugins
 *
 * # Example: Basic example
 *
 * <code>
 * <n:excludeDisplayedNews newsItem="{newsItem}" />
 * </code>
 * <output>
 * None
 * </output>
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_ViewHelpers_ExcludeDisplayedNewsViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

    /**
     * Die IDs der übersetzen News holen
     *
     *  @return	array	News ID
     */
    public static function getTranslatedNews($uid, $sprache) {

        # DBG
        #$GLOBALS['TYPO3_DB']->store_lastBuiltQuery = 1;
        #$GLOBALS['TYPO3_DB']->debugOutput = true;
        #$GLOBALS['TYPO3_DB']->debug_lastBuiltQuery;
        // echo $GLOBALS['TYPO3_DB']->debug_lastBuiltQuery;

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('`uid`', '`tx_news_domain_model_news`',
            '`l10n_parent`=' . $uid . ' AND `sys_language_uid`='. $sprache . $GLOBALS['TSFE']->sys_page->enableFields('tx_news_domain_model_news')
        );

        $rootList = array();
        if ($GLOBALS['TYPO3_DB']->sql_num_rows($res)) {
            while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)){
                $rootList[] = array('uid' => $row['uid']);
                // if more then one, maybe error handling
            }
        }
        return $rootList[0]['uid'];
    }

    /**
	 * Add the news uid to a global variable to be able to exclude it later
	 *
	 * @param Tx_News_Domain_Model_News $newsItem current news item
	 * @return void
	 */
	public function render(Tx_News_Domain_Model_News $newsItem) {
		$uid = $newsItem->getUid();

        if (empty($GLOBALS['EXT']['news']['alreadyDisplayed'])) {
            $GLOBALS['EXT']['news']['alreadyDisplayed'] = array();
        }

        // wenn Übersetzungsdatensatz, dann muss auch die Übersetzungsdatensatz-ID geschrieben werden
        if ($newsItem->getSysLanguageUid() !== 0) {
            $GLOBALS['EXT']['news']['alreadyDisplayed'][$uid] = self::getTranslatedNews($newsItem->getL10nParent(), $newsItem->getSysLanguageUid());
        } else {
    		$GLOBALS['EXT']['news']['alreadyDisplayed'][$uid] = $uid;
        }
	}
}
