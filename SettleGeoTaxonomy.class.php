<?php

use MenaraSolutions\Geographer;

/**
 * Class for SettleGeoTaxonomy extension
 *
 * @file
 * @ingroup Extensions
 */
class SettleGeoTaxonomy
{

	/** @var SettleGeoTaxonomy */
	private static $instance = null;

	const TYPE_COUNTRY = 1;
	const TYPE_STATE = 2;
	const TYPE_CITY = 3;

	/**
	 * @return SettleGeoTaxonomy
	 */
	public static function getInstance() {
		if( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Returns all entities by type
	 *
	 * @param int $type
	 * @param null $parent
	 * @param string $lang
	 *
	 * @return array
	 *
	 */
	public function getEntities( $type, $parent = null, $lang = 'en' ) {

		$items = array();
		$earth = new Geographer\Earth();
		$earth->setLanguage( $lang );

		switch ( $type ) {
			case self::TYPE_COUNTRY:
				$items = $earth->getCountries()->setLanguage( $lang )->toArray();
				break;
			case self::TYPE_STATE:
				if( $parent != null ) {
					$country = $earth->findOne( array('geonamesCode' => $parent) );
					$items = $country->getStates()->setLanguage( $lang )->toArray();
				}
				break;
			case self::TYPE_CITY:
				if( $parent != null ) {
					$state = Geographer\State::build( $parent );
					if( $state ) {
						$items = $state->getCities()->setLanguage( $lang )->toArray();
					}
				}
				break;
		}

		return $items;
	}

}
