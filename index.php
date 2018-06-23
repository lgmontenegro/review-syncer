<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './vendor/autoload.php';
require 'classes.php';

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$conf = new \Slim\Container($configuration);
$container = new \Slim\Container;
$app = new \Slim\App($conf, $container);

$container = $app->getContainer();
$container['reviewObj'] = function ($container) {
    $reviewObj = new Review();
    return $reviewObj;
};
$container['curl'] = function ($container) {
    $curl = new Curl\Curl();
    return $curl;
};
$container['pdo'] = function ($container) {
    $dsn = 'mysql:host=127.0.0.1;dbname=reviews';
    $usr = 'root';
    $pwd = 'password';
    $pdo = new \Slim\PDO\Database($dsn, $usr, $pwd);
    return $pdo;
};

$app->get('/{app}', function (Request $request, Response $response, array $args) 
{
    $appName = $args['app'];
    $shopify_address = "https://apps.shopify.com/$appName/reviews.json";
    
    $reviewObj = $this->reviewObj;
    $curl = $this->curl;

    $reviewObj->setPdo($this->pdo);
    $curl->get($shopify_address);

    if($curl->http_status_code == 200){
        $returnedReviews = json_decode($curl->response);
        $reviewObj->insert($returnedReviews->reviews, $appName);
    }

    $newRes = $response->withJson(json_decode(json_encode(['error'=>404])));
    return $newRes;
});

$app->run();