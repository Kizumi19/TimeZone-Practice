<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;

echo PHP_EOL;
echo "----------".PHP_EOL."Welcome! Please enter the time zone for which you want to know the current time. For example: Cuba".PHP_EOL."Otherwise, if you prefer to check the principal time zones, choose the options you prefer!".PHP_EOL."----------".PHP_EOL.PHP_EOL;
echo "----------".PHP_EOL."OPTIONS".PHP_EOL."----------".PHP_EOL."Enter the timezone by yourself or select one option".PHP_EOL."A) Amsterdam".PHP_EOL."B) Madrid".PHP_EOL."C) Tokyo".PHP_EOL."D) Sydney".PHP_EOL;

fscanf(STDIN, "%s", $timezone);
$timezone = strtoupper($timezone);

function AnalitzaryExtreureZH($url) {
    $AnalitzarZonaHoraria = parse_url($url, PHP_URL_QUERY);
    parse_str($AnalitzarZonaHoraria, $ExtreureZonaHoraria);
    $timezone = $ExtreureZonaHoraria['timeZone'];
    $timezone = ucfirst($timezone);

    return $timezone;
}

switch ($timezone) {
    case "A":
        $url = "https://timeapi.io/api/Time/current/zone?timeZone=Europe/Amsterdam";
        $timezone = AnalitzaryExtreureZH($url);
        echo "You selected: ".$timezone.PHP_EOL;
        break;
    case "B":
        $url = "https://timeapi.io/api/Time/current/zone?timeZone=Europe/Madrid";
        $timezone = AnalitzaryExtreureZH($url);
        break;
    case "C":
        $url = "https://timeapi.io/api/Time/current/zone?timeZone=Asia/Tokyo";
        $timezone = AnalitzaryExtreureZH($url);
        echo "You selected: ".$timezone.PHP_EOL;
        break;
    case "D":
        $url = "https://timeapi.io/api/Time/current/zone?timeZone=Australia/Sydney";
        $timezone = AnalitzaryExtreureZH($url);
        echo "You selected: ".$timezone.PHP_EOL;
        break;
    default:
        try {
            $url = "https://timeapi.io/api/Time/current/zone?timeZone=". $timezone;
            $timezone = AnalitzaryExtreureZH($url);
            $timezone = ucfirst(strtolower($timezone));
            echo "You selected: ".$timezone.PHP_EOL;
        } catch (Exception $e) {
            echo "Try again, something is wrong".PHP_EOL;
            echo $e;
        }
}
echo "Loading";
for ($i = 0; $i < 3; $i++) {
    echo ".";
    sleep(1);
}
echo PHP_EOL;

$client = HttpClient::create();
$response = $client->request(
    'GET',
    $url
);

if($response->getStatusCode() == 200){
    $data = json_decode($response->getContent());
    $date = date('d-m-Y', strtotime($data->date));
    echo "Date ". $date.PHP_EOL;
    echo "Time ". $data->time.PHP_EOL;
    }else{
    echo 'Something goes wrong'.PHP_EOL;
    echo "----> TIP! You can search in https://en.wikipedia.org/wiki/List_of_tz_database_time_zones be more specific which country you want to check its timezone".PHP_EOL;
}

echo ' Thanks for using this code! '.PHP_EOL;

