<?php

use MenaraSolutions\Geographer;

$basePath = getenv( 'MW_INSTALL_PATH' ) !== false ? getenv( 'MW_INSTALL_PATH' ) : __DIR__ . '/../../..';

require_once $basePath . '/maintenance/Maintenance.php';

class SettleGeoTaxonomyIndexGenerator extends \Maintenance {

	/**
	 * @since 2.0
	 */
	public function __construct() {
		parent::__construct();

		$this->addDescription( "\n" ."Generates search index for taxonomy search functions. \n" );
		$this->addDefaultParams();
	}

	/**
	 * @see Maintenance::addDefaultParams
	 *
	 * @since 2.0
	 */
	protected function addDefaultParams() {

		//$this->addOption( 'file', '<file> output file.', false, true, 'o' );

	}

	/**
	 * @see Maintenance::execute
	 *
	 * @since 2.0
	 */
	public function execute() {

		global $wgLang;

		$dbw = wfGetDB(DB_MASTER);

		$this->output("\nClearing old index..");

		$dbw->delete( 'sgt_geo_index', '*' );

		$this->output("\nStarting to build search index..");

		$earth = new Geographer\Earth();
		$earth->setLanguage( $wgLang->getCode() );

		$countries = $earth->getCountries();
		foreach ( $countries as $country ) {
			$dbw->insert( 'sgt_geo_index', array(
				'body' => $country->getName() . ' ' . $country->getShortName() . ' ' . $country->getLongName(),
				'type' => 'country',
				'code' => $country->getCode(),
				'code_geonames' => $country->getGeonamesCode(),
				'name' => $country->getShortName()
			));
			$states = $country->getStates();
			foreach ( $states as $state ) {
				$dbw->insert( 'sgt_geo_index', array(
					'body' => $state->getName() . ' ' . $state->getShortName() . ' ' . $state->getLongName(),
					'type' => 'state',
					'code' => $state->getCode(),
					'code_geonames' => $state->getGeonamesCode(),
					'name' => $state->getShortName(),
					'parent_id' => $country->getGeonamesCode(),
					'suffix' => $country->getShortName()
				));
				$cities = $state->getCities();
				foreach ( $cities as $city ) {
					$dbw->insert( 'sgt_geo_index', array(
						'body' => $city->getName() . ' ' . $city->getShortName() . ' ' . $city->getLongName(),
						'type' => 'city',
						'code' => $city->getCode(),
						'code_geonames' => $city->getGeonamesCode(),
						'name' => $city->getShortName(),
						'parent_id' => $state->getGeonamesCode(),
						'suffix' => $state->getShortName() .', '. $country->getShortName()
					));
				}
			}
		}

		return true;
	}

}

$maintClass = 'SettleGeoTaxonomyIndexGenerator';
require_once ( RUN_MAINTENANCE_IF_MAIN );
