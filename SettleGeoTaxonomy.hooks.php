<?php

/**
 * Hooks for SettleGeoTaxonomy extension
 *
 * @file
 * @ingroup Extensions
 */
class SettleGeoTaxonomyHooks
{

	public static function onExtensionLoad()
	{
		
	}

	/**
	 * @param DatabaseUpdater $updater
	 */
	public static function onLoadExtensionSchemaUpdates( $updater )
	{

		$updater->addExtensionTable( 'sgt_geo_index', dirname(__FILE__).'/schema/sgt_geo_index.sql' );
		$updater->addExtensionField( 'sgt_geo_index', 'lang', dirname(__FILE__).'/schema/sgt_geo_index_add_lang.sql' );

	}

}
