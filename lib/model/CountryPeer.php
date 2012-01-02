<?php

class CountryPeer extends BaseCountryPeer
{
    static public function getSortedCountries() {
        $c = new Criteria();
        $c->addAscendingOrderByColumn(CountryPeer::NAME);
        $rs = CountryPeer::doSelect($c);
        return $rs;
    }
}
