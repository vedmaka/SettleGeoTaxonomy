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
		$updater->addExtensionTable( 'settle_geo_countries', dirname( __FILE__ ) .'/schema/countries.sql' );
		$updater->addExtensionTable( 'settle_geo_states', dirname( __FILE__ ) .'/schema/states.sql' );
		$updater->addExtensionTable( 'settle_geo_cities', dirname( __FILE__ ) .'/schema/cities.sql' );
	}

}
