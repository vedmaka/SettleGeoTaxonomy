<?php

class SettleGeoTaxonomyApi extends ApiBase {

	public function execute() {
		$params = $this->extractRequestParams();
		$type = $params['type'];

		$parent = null;
		if( array_key_exists('parent', $params) ) {
			$parent = $params['parent'];
		}

		$nativeType = '';
		switch ($type) {
			case 'country':
				$nativeType = SettleGeoTaxonomy::TYPE_COUNTRY;
				break;
			case 'state':
				$nativeType = SettleGeoTaxonomy::TYPE_STATE;
				break;
			case 'city':
				$nativeType = SettleGeoTaxonomy::TYPE_CITY;
				break;
		}

		$items = SettleGeoTaxonomy::getInstance()->getEntities( $nativeType, $parent, false );

		$this->getResult()->addValue( $this->getModuleName(), 'items', $items );

	}

	public function getAllowedParams( /* $flags = 0 */ ) {
		return array(
			'type' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'parent' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_REQUIRED => false
			)
		);
	}

}