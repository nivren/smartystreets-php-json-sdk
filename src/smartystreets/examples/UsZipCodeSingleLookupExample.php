<?php

namespace smartystreets\examples;

require_once(dirname(dirname(__FILE__)) . '/api/us_zipcode/ClientBuilder.php');
require_once(dirname(dirname(__FILE__)) . '/api/us_zipcode/Result.php');
require_once(dirname(dirname(__FILE__)) . '/api/StaticCredentials.php');
require_once(dirname(dirname(__FILE__)) . '/api/SharedCredentials.php');
use smartystreets\api\exceptions\SmartyException;
use smartystreets\api\StaticCredentials;
use smartystreets\api\us_zipcode\Lookup;
use smartystreets\api\us_zipcode\ClientBuilder;

$lookupExample = new UsZipCodeSingleLookupExample();
$lookupExample->run();

class UsZipCodeSingleLookupExample {

    public function run() {
//        $staticCredentials = new StaticCredentials($_ENV['SMARTY_AUTH_ID'], $_ENV['SMARTY_AUTH_TOKEN']);
        $staticCredentials = new StaticCredentials('auth_id', 'auth_token');
        $client = (new ClientBuilder($staticCredentials))->build();

        $lookup = new Lookup();
        $lookup->setCity("Mountain View");
        $lookup->setState("California");

        try {
            $client->sendLookup($lookup);
            $this->displayResults($lookup);
        }
        catch (SmartyException $ex) {
            echo($ex->getMessage());
        }
        catch (\Exception $ex) {
            echo($ex->getMessage());
        }
    }

    public function displayResults(Lookup $lookup) {
        $result = $lookup->getResult();
        $zipCodes = $result->getZipCodes();
        $cities = $result->getCities();

        foreach ($cities as $city) {
            echo("\n\nCity: " . $city->getCity());
            echo("\nState: " . $city->getState());
            echo("\nMailable City: " . json_encode($city->getMailableCity()));
        }

        foreach ($zipCodes as $zip) {
            echo("\n\nZIP Code: " . $zip->getZipCode());
            echo("\nLatitude: " . $zip->getLatitude());
            echo("\nLongitude: " . $zip->getLongitude());
        }
    }
}