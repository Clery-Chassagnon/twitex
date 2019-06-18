<?php

namespace app;

use app\src\App;
use app\Routing;
use app\src\serviceContainer\ServiceContainer;
use database\Database;
use model\finder\PostFinder;
use model\gateway\PostGateway;

$container = new ServiceContainer();
$app = new app($container);

$app->setService('database', new Database(
    '127.0.0.1',
    'twitex',
    'root',
    '',
    '3306'
));

$app->setService('postFinder', new PostFinder($app));


$routing = new Routing($app);
$routing->setup();

return $app;
