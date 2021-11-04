<?php


use App\PowerAll\Client;
use Cerbero\LazyJson\Macro;
use Illuminate\Support\LazyCollection;

include "../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

$powerAllClient = new Client($_ENV['POWERALL_API_USERNAME'], $_ENV['POWERALL_API_PASSWORD']);
$name = 'products.json';

$products = [];
if(file_exists($name)) {
    $products = file_get_contents($name);
}

if(!$products) {
    $products = (string)$powerAllClient->products()->getBody();
    file_put_contents($name, $products);
}

LazyCollection::macro('fromJson', new Macro());


$collection = LazyCollection::fromJson($products)->chunk(100)
->each(function ($products) {
    dd($products);
});