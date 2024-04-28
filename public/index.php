<?php


use App\PowerAll\Client;
use Cerbero\LazyJson\Macro;
use Illuminate\Support\LazyCollection;

include "../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

//$client = new \GuzzleHttp\Client();
//$userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.54 Safari/537.36';
//$response = $client->get('https://www.indi.nl/nl-nl/p/900508KR', ['headers' => ['User-Agent' => $userAgent,]]);
//echo (string)$response->getBody(); exit;
$powerAllClient = new Client($_ENV['POWERALL_API_USERNAME'], $_ENV['POWERALL_API_PASSWORD']);
$name = 'products.json';

$products = [];
if(file_exists($name) && !isset($_GET['nocache'])) {
    $products = file_get_contents($name);
}

// Check active codes
$activeCodes = [
    //'F931970160042G',
    //'RE541922',
    'RE62419',
    //'X820001190000',
    //'0009839000',
    //'3903700033',
    // '7919040042',
    //'7919040041'
];


if(!$products) {
    $products = (string)$powerAllClient->products()->getBody();
    file_put_contents($name, $products);
}
LazyCollection::macro('fromJson', new Macro());


$krampCollection = new \Illuminate\Support\Collection();
$totalCount = 0;
$counter = new \App\Counter();
LazyCollection::fromJson($name)->chunk(100)
    ->each(function ($products) use ($krampCollection, $activeCodes, $counter) {
        foreach ($products as $product) {
            if($product['HideOnWebshop'] === false) {
                dump($product);
                $counter->add();
            }

        }
    });
dd($counter->count);
