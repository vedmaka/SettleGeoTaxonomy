<?php

use MenaraSolutions\Geographer;

$basePath = getenv( 'MW_INSTALL_PATH' ) !== false ? getenv( 'MW_INSTALL_PATH' ) : __DIR__ . '/../../..';

require_once $basePath . '/maintenance/Maintenance.php';

class SettleGeoTaxonomyIndexGenerator extends \Maintenance {

	private $dbw;

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

		$this->addOption( 'languages', '<lang1,lang2> list of languages.', false, true, 'l' );

	}

	/**
	 * @see Maintenance::execute
	 *
	 * @since 2.0
	 */
	public function execute() {
		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);

		global $wgLang;
		
		$defaultLang = $wgLang->getCode();
		
		$languagesToProcess = array();

		$this->dbw = wfGetDB(DB_MASTER);

		$this->output("\nClearing old index..");

		$this->dbw->delete( 'sgt_geo_index', '*' );

		$this->output("\nStarting to build search index..");

		if( $this->hasOption('languages') ) {
			$optLanguage = $this->getOption('languages');
			$optValues = $optLanguage;
			if( strpos( $optValues, ',' ) !== false ) {
				$optValues = explode(',', $optValues );
				$languagesToProcess = $optValues;
			}else{
				$languagesToProcess = array( $optValues );
			}
		}else{
			$languagesToProcess = array( $defaultLang );
		}
		
		foreach( $languagesToProcess as $l ) {
			$this->output("\nLanguage: ".$l);
			$this->generateIndex( strtolower($l) );
		}
		
		$this->output("\n");
		return true;
	}
	
	private function generateIndex( $lang ) {
		
		$earth = new Geographer\Earth();
		$earth->setLanguage( $lang )->useShortNames();

		$countries = $earth->getCountries();
		foreach ( $countries as $country ) {
			try {
				$this->dbw->insert( 'sgt_geo_index', array(
					'body' => $country->getName() . ' ' . $country->getShortName() . ' ' . $country->getLongName(),
					'type' => 'country',
					'code' => $country->getCode(),
					'code_geonames' => $country->getGeonamesCode(),
					'name' => $country->getShortName(),
					'lang' => $lang
				));
				$this->output(".");
			}catch( Exception $e ) {
				$this->output("X");
			}
			
			$states = $country->getStates();
			foreach ( $states as $state ) {
				
				try {
					$this->dbw->insert( 'sgt_geo_index', array(
						'body' => $state->getName() . ' ' . $state->getShortName() . ' ' . $state->getLongName(),
						'type' => 'state',
						'code' => $state->getCode(),
						'code_geonames' => $state->getGeonamesCode(),
						'name' => $state->getShortName(),
						'parent_id' => $country->getGeonamesCode(),
						'suffix' => $country->getShortName(),
						'lang' => $lang
					));
					$this->output(".");
				}catch( Exception $e ) {
					$this->output("X");	
				}	
				
				$cities = $state->getCities();
				foreach ( $cities as $city ) {
					
					try {
						$this->dbw->insert( 'sgt_geo_index', array(
							'body' => $city->getName() . ' ' . $city->getShortName() . ' ' . $city->getLongName(),
							'type' => 'city',
							'code' => $city->getCode(),
							'code_geonames' => $city->getGeonamesCode(),
							'name' => $city->getShortName(),
							'parent_id' => $state->getGeonamesCode(),
							'suffix' => $state->getShortName() .', '. $country->getShortName(),
							'lang' => $lang
						));
						$this->output(".");
					}catch( Exception $e ) {
						$this->output("X");
					}
				}
			}
		}

		
	}

}

$maintClass = 'SettleGeoTaxonomyIndexGenerator';
require_once ( RUN_MAINTENANCE_IF_MAIN );
