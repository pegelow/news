<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Frans Saris <frans@beech.it>
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
 * Inline Element Hook
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_InlineElementHook implements t3lib_tceformsInlineHook  {

	/**
	 * Initializes this hook object.
	 *
	 * @param t3lib_TCEforms_inline $parentObject
	 * @return void
	 */
	public function init(&$parentObject) {}

	/**
	 * Pre-processing to define which control items are enabled or disabled.
	 *
	 * @param string $parentUid The uid of the parent (embedding) record (uid or NEW...)
	 * @param string $foreignTable The table (foreign_table) we create control-icons for
	 * @param array $childRecord The current record of that foreign_table
	 * @param array $childConfig TCA configuration of the current field of the child record
	 * @param boolean $isVirtual Defines whether the current records is only virtually shown and not physically part of the parent record
	 * @param array &$enabledControls (reference) Associative array with the enabled control items
	 * @return void
	 */
	public function renderForeignRecordHeaderControl_preProcess($parentUid, $foreignTable, array $childRecord, array $childConfig, $isVirtual, array &$enabledControls) {

	}

	/**
	 * Post-processing to define which control items to show. Possibly own icons can be added here.
	 *
	 * @param string $parentUid The uid of the parent (embedding) record (uid or NEW...)
	 * @param string $foreignTable The table (foreign_table) we create control-icons for
	 * @param array $childRecord The current record of that foreign_table
	 * @param array $childConfig TCA configuration of the current field of the child record
	 * @param boolean $isVirtual Defines whether the current records is only virtually shown and not physically part of the parent record
	 * @param array &$controlItems (reference) Associative array with the currently available control items
	 * @return void
	 */
	public function renderForeignRecordHeaderControl_postProcess($parentUid, $foreignTable, array $childRecord, array $childConfig, $isVirtual, array &$controlItems) {
		if ($foreignTable === 'sys_file_reference' && !empty($childRecord['showinpreview'])) {
				$ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xml:';
				$label = $GLOBALS['LANG']->sL($ll . 'tx_news_domain_model_media.showinpreview', TRUE);
				$icon = '../' . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath('news') . 'Resources/Public/Icons/preview.gif';
				$extraItem['showinpreview'] = ' <img title="' . $label . '" src="' . $icon . '" />';
				$controlItems = $extraItem+$controlItems;
		}
	}

}