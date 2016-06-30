<?php

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

	private $db = null;

	const TYPE_COUNTRY = 1;
	const TYPE_STATE = 2;
	const TYPE_CITY = 3;

	private static $typeTables = array(
		self::TYPE_COUNTRY  => 'settle_geo_countries',
		self::TYPE_STATE    => 'settle_geo_states',
		self::TYPE_CITY     => 'settle_geo_cities'
	);

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
	 * SettleGeoTaxonomy constructor.
	 */
	private function __construct() {
		// In most cases we only
		$this->db = wfGetDB(DB_SLAVE);
	}

	/**
	 * Returns table related to entity type
	 * @param $type
	 * @return string
	 */
	public function getTableByType( $type )
	{
		return self::$typeTables[ $type ];
	}

	/**
	 * Returns all entities by type
	 *
	 * @param $type
	 * @param null $parent
	 * @param bool $onlyNames
	 *
	 * @return array
	 */
	public function getEntities( $type, $parent = null, $onlyNames = true )
	{
		$items = array();
		$conditions = '';

		if( $parent !== null ) {
			switch ($type) {
				case self::TYPE_STATE:
					$conditions = array( 'country_id' => $parent );
					break;
				case self::TYPE_CITY:
					$conditions = array( 'state_id' => $parent );
					break;
				default:
					// This should never happen:
					$conditions = '';
					break;
			}
		}

		$result = $this->db->select( $this->getTableByType( $type ), '*', $conditions);

		if( $result->numRows() ) {
			while( $row = $result->fetchRow() ) {
				if( $onlyNames ) {
					$items[] = $row['name'];
				}else{
					$items[] = $row;
				}
			}
		}
		return $items;
	}

}
