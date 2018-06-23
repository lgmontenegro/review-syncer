<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './vendor/autoload.php';

$app = new \Slim\App;

$app->update('/{app}', function (Request $request, Response $response, array $args) 
{
    $appName = $args['app'];
    $shopify_address = "https://apps.shopify.com/$appName/reviews.json";
    
    $curl = new Curl\Curl();
    $curl->get($shopify_address);
    if($curl->http_response_code == 200){
        $newRes = $response->withJson(json_decode($curl->response));
        return $newRes;
    }

    $newRes = $response->withJson(json_decode(json_encode(['error'=>404])));
    return $newRes;
    
});

/*$app->update('/{app}', function (Request $request, Response $response, array $args) 
{
    $appName = $args['app'];
    $shopify_address = "https://apps.shopify.com/$appName/reviews.json";
    
    $curl = new Curl\Curl();
    $curl->get($shopify_address);

    //$response->getBody()->write("shopify: $shopify_address - $curl->response");
    //$responde->
    $response->getBody()->withHeader('Content-type', 'application/json')->write($curl->response);
    return $response;
});*/



$app->run();