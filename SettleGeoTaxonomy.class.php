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
				$items = $earth->getCountries()->setLanguage( $lang )->useShortNames()->toArray();
				break;
			case self::TYPE_STATE:
				if( $parent != null ) {
					$country = $earth->findOne( array('geonamesCode' => $parent) );
					$items = $country->getStates()->setLanguage( $lang )->useShortNames()->toArray();
				}
				break;
			case self::TYPE_CITY:
				if( $parent != null ) {
					$state = Geographer\State::build( $parent );
					if( $state ) {
						$items = $state->getCities()->setLanguage( $lang )->useShortNames()->toArray();
					}
				}
				break;
		}

		return $items;
	}

	public function getMatch( $term, $limit = null, $type = null, $parentGeoCode = null )
	{

		$items = array();

		$dbr = wfGetDB(DB_SLAVE);

		$conditions = array();
		$options = array();

		if( $limit !== null ) {
			$options = array('LIMIT' => $limit);
		}

		if( $type !== null ) {
			$conditions['type'] = $type;
		}

		if( $parentGeoCode !== null ) {
			$conditions['parent_id'] = $parentGeoCode;
		}

		$conditions[] = 'MATCH(body) AGAINST("'.htmlspecialchars(trim($term)).'*" in boolean mode)';

		$result = $dbr->select( 'sgt_geo_index',
			array(
				'type',
				'code',
				'parent_id',
				'code_geonames',
				'name',
				'suffix'
			),
			$conditions,
			__METHOD__,
			$options
		);

		if( $result->numRows() ) {
			while( $row = $result->fetchRow() ) {
				$items[] = array(
					'type' => $row['type'],
					'code' => $row['type'],
					'parent_id' => $row['parent_id'],
					'code_geonames' => $row['code_geonames'],
					'name' => $row['name'],
					'suffix' => $row['suffix']
				);
			}
		}

		return $items;

	}

}
