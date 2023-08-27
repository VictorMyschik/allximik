<?php

namespace Models\References;

use App\Models\Reference\Country;
use Tests\BaseTest;

class CountryTest extends BaseTest
{
  public function testCountry(): void
  {
    /**
     * 'Name',
     * 'ISO3166alpha2',
     * 'ISO3166alpha3',
     * 'ISO3166numeric',
     * 'Continent',
     */

    $country = new Country();

    // 'Name'
    $Name = self::randomString();
    $country->setName($Name);
    // 'ISO3166alpha2'
    $ISO3166alpha2 = self::randomString(3);
    $country->setISO3166alpha2($ISO3166alpha2);
    // 'ISO3166alpha3'
    $ISO3166alpha3 = self::randomString(4);
    $country->setISO3166alpha3($ISO3166alpha3);
    // 'ISO3166numeric'
    $ISO3166numeric = self::randomString(3);
    $country->setISO3166numeric($ISO3166numeric);
    // 'Continent'
    $Continent = (int)array_rand(Country::getContinentList());
    $country->setContinent($Continent);

    $country_id = $country->save_mr();

    /// Asserts
    $country = Country::loadBy($country_id);
    self::assertNotNull($country);
    self::assertEquals($Name, $country->getName());
    self::assertEquals($ISO3166alpha2, $country->getISO3166alpha2());
    self::assertEquals($ISO3166alpha3, $country->getISO3166alpha3());
    self::assertEquals($ISO3166numeric, $country->getISO3166numeric());
    self::assertEquals($Continent, $country->getContinent());

    /// Update
    // 'Name'
    $Name = self::randomString();
    $country->setName($Name);
    // 'ISO3166alpha2'
    $ISO3166alpha2 = self::randomString(3);
    $country->setISO3166alpha2($ISO3166alpha2);
    // 'ISO3166alpha3'
    $ISO3166alpha3 = self::randomString(4);
    $country->setISO3166alpha3($ISO3166alpha3);
    // 'ISO3166numeric'
    $ISO3166numeric = self::randomString(3);
    $country->setISO3166numeric($ISO3166numeric);
    // 'Continent'
    $Continent = (int)array_rand(Country::getContinentList());
    $country->setContinent($Continent);

    $country_id = $country->save_mr();

    /// Asserts
    $country = Country::loadBy($country_id);
    self::assertNotNull($country);
    self::assertEquals($Name, $country->getName());
    self::assertEquals($ISO3166alpha2, $country->getISO3166alpha2());
    self::assertEquals($ISO3166alpha3, $country->getISO3166alpha3());
    self::assertEquals($ISO3166numeric, $country->getISO3166numeric());
    self::assertEquals($Continent, $country->getContinent());


    $country->delete_mr();
    $country = Country::loadBy($country_id);
    self::assertNull($country);
  }
}
