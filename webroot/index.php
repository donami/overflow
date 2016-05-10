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

// Reply controller
$di->set('ReplyController', function() use($di) {
  $controller = new donami\Reply\ReplyController();
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

    // Get 10 recent questions
    $recent = $app->dispatcher->forward([
      'controller' => 'question',
      'action' => 'getRecent',
      'params' => [10],
    ]);

    // Get top 10 active users
    $activeUsers = $app->dispatcher->forward([
      'controller' => 'user',
      'action' => 'getActive',
      'params' => [10],
    ]);

    // Get top 10 popular tags
    $popularTags = $app->dispatcher->forward([
      'controller' => 'tag',
      'action' => 'getPopular',
      'params' => [10],
    ]);

    $app->views->add('welcome/index', [
      'recentQuestions' => $recent,
      'activeUsers' => $activeUsers,
      'popularTags' => $popularTags,
    ]);
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

$app->router->add('question/create', function() use($app) {
  $app
    ->dispatcher
    ->forward([
      'controller' => 'question',
      'action' => 'create',
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


// Accept reply as answer route
$app->router->add('reply/accept', function() use ($app) {

  $replyId = $app->request->getGet('replyID');
  $questionId = $app->request->getGet('questionID');

  $app
    ->dispatcher
    ->forward([
      'controller' => 'reply',
      'action' => 'acceptAnswer',
      'params' => [$replyId, $questionId]
    ]);

});

// Rate an answer route
$app->router->add('reply/point', function() use ($app) {

  $id = $app->request->getGet('id');
  $action = $app->request->getGet('action');

  if (!($action === 'increase' || $action === 'decrease')) {
    die("Invalid action");
  }

  $app
    ->dispatcher
    ->forward([
      'controller' => 'reply',
      'action' => 'point',
      'params' => [$id, $action]
    ]);

});

// Post create reply route
$app->router->add('reply/create', function() use ($app) {

  $app
    ->dispatcher
    ->forward([
      'controller' => 'reply',
      'action' => 'create',
      'params' => [$_POST]
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
