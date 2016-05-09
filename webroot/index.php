<?php
require __DIR__.'/config_with_app.php';

// Nicer looking urls
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);

// Set the theme
$app->theme->configure(ANAX_APP_PATH . 'config/theme.php');

// Load the navbar configuration
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar.php');

// Database handling
$di->setShared('db', function() {
    $db = new \Mos\Database\CDatabaseBasic();
    $db->setOptions(require ANAX_APP_PATH . 'config/config_db.php');
    $db->connect();
    return $db;
});

// Question controller
$di->set('QuestionController', function() use($di) {
  $controller = new donami\Question\QuestionController();
  $controller->setDI($di);
  return $controller;
});

// Tag controller
$di->set('TagController', function() use($di) {
  $controller = new donami\Tag\TagController();
  $controller->setDI($di);
  return $controller;
});

// User controller
$di->set('UserController', function() use($di) {
  $controller = new donami\User\UserController();
  $controller->setDI($di);
  return $controller;
});

// About controller
$di->set('AboutController', function() use($di) {
  $controller = new donami\About\AboutController();
  $controller->setDI($di);
  return $controller;
});

$app = new \Anax\Kernel\CAnax($di);

// Default route
$app->router->add('', function() use ($app) {

    $app->theme->setTitle("Welcome to this page");

});

// List question route
$app->router->add('questions', function() use ($app) {
  $app
    ->dispatcher
    ->forward([
      'controller' => 'question',
      'action' => 'list',
    ]);
});

// View question route
$app->router->add('question', function() use ($app) {
  $questionId = $app->request->getGet('id');

  $app
    ->dispatcher
    ->forward([
      'controller' => 'question',
      'action' => 'view',
      'params' => [$questionId]
    ]);
});

$app->router->add('tags', function() use ($app) {
  $app
    ->dispatcher
    ->forward([
      'controller' => 'tag',
      'action' => 'list',
    ]);
});

// Display single tag route
$app->router->add('tag', function() use ($app) {

  $tagId = $app->request->getGet('id');

  $app
    ->dispatcher
    ->forward([
      'controller' => 'tag',
      'action' => 'view',
      'params' => [$tagId]
    ]);

});

// Display user list route
$app->router->add('users', function() use ($app) {

  $app
    ->dispatcher
    ->forward([
      'controller' => 'user',
      'action' => 'list',
    ]);

});

// Display user route
$app->router->add('user', function() use ($app) {
  $userId = $app->request->getGet('id');

  $app
    ->dispatcher
    ->forward([
      'controller' => 'user',
      'action' => 'view',
      'params' => [$userId]
    ]);
});

// About route
$app->router->add('about', function() use ($app) {

  $app
    ->dispatcher
    ->forward([
      'controller' => 'about',
      'action' => 'view',
    ]);

});

$app->router->handle();
$app->theme->render();
