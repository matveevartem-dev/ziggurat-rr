<?php

/**
 * ROADRUNNER PROXY WORKER
 */

use Spiral\RoadRunner;
use Nyholm\Psr7;

require __DIR__ . '/../vendor/autoload.php';

// Максимально примитивный цикл без внешних классов (только ядро RR)
$worker = RoadRunner\Worker::create();
$psrFactory = new Psr7\Factory\Psr17Factory();
$psr7 = new RoadRunner\Http\PSR7Worker($worker, $psrFactory, $psrFactory, $psrFactory);

while ($request = $psr7->waitRequest()) {
    try {
        $res = $psrFactory->createResponse(200)
            ->withHeader('Content-Type', 'application/json; charset=utf-8');
            
        $res->getBody()->write(json_encode([
            'status' => 'maintenance',
            'message' => 'Zigguret OS Proxy-Api на техобслуживании...'
        ], JSON_UNESCAPED_UNICODE));
        
        $psr7->respond($res);
        
    } catch (\Throwable $e) {
        $worker->error((string)$e);
    }
}
