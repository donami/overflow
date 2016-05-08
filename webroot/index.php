<?php
require __DIR__.'/config_with_app.php';

$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
$app->theme->configure(ANAX_APP_PATH . 'config/theme.php');

$app->navbar->configure(ANAX_APP_PATH . 'config/navbar.php');

$di->setShared('db', function() {
    $db = new \Mos\Database\CDatabaseBasic();
    $db->setOptions(require ANAX_APP_PATH . 'config/config_db.php');
    $db->connect();
    return $db;
});

$app = new \Anax\Kernel\CAnax($di);

$app->router->add('', function() use ($app) {

    $app->theme->setTitle("Välkommen till min sida");

});

$app->router->add('questions', function() use ($app) {
  $app->theme->setTitle('Questions');

  $app->db->select('title', 'body')->from('questions');
  $res = $app->db->executeFetchAll();

  $app->views->add('questions/index', [
    'questions' => $res,
  ]);
});


$app->router->handle();
$app->theme->render();
