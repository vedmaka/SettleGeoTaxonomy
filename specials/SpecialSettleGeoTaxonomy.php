<?php

/**
 * SettleGeoTaxonomy SpecialPage for SettleGeoTaxonomy extension
 *
 * @file
 * @ingroup Extensions
 */
class SpecialSettleGeoTaxonomy extends SpecialPage
{
    public function __construct()
    {
        parent::__construct( 'SettleGeoTaxonomy' );
    }

    /**
     * Show the page to the user
     *
     * @param string $sub The subpage string argument (if any).
     *  [[Special:SettleGeoTaxonomy/subpage]].
     */
    public function execute( $sub )
    {
        $out = $this->getOutput();

        $out->setPageTitle( $this->msg( 'settlegeotaxonomy-helloworld' ) );

        $out->addHelpLink( 'How to become a MediaWiki hacker' );

        $out->addWikiMsg( 'settlegeotaxonomy-helloworld-intro' );
    }

    protected function getGroupName()
    {
        return 'other';
    }
}
