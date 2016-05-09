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

  $app->db->select()->from('questions');
  $res = $app->db->executeFetchAll();

  $app->views->add('questions/index', [
    'questions' => $res,
  ]);
});

$app->router->add('question', function() use ($app) {
  $app->theme->setTitle('View question');

  $questionId = $app->request->getGet('id');

  $app->db->select()->from('questions')->where('id = ' . $questionId);
  $app->db->execute();
  $res = $app->db->fetchOne();

  $app->views->add('questions/view', [
    'question' => $res,
  ]);

});

$app->router->add('tags', function() use ($app) {
  $app->theme->setTitle('Tags');

  // Fetch tags from database
  $app->db->select()->from('tags');
  $res = $app->db->executeFetchAll();

  // Create the view
  $app->views->add('tags/list', [
    'tags' => $res,
  ]);
});

// Display single tag route
$app->router->add('tag', function() use ($app) {
  $app->theme->setTitle('View tag');

  // Get the tag ID from url
  $tagId = $app->request->getGet('id');

  // Fetch the tag from database
  $app->db->select()->from('tags')->where('id = ' . $tagId);
  $app->db->execute();
  $res = $app->db->fetchOne();

  // Get questions with this tag
  $app->db->select("Q.title")
      ->from('questions AS Q')
      ->leftJoin('questions_tags AS QT', 'Q.id = QT.question_id')
      ->leftJoin('tags AS T', 'T.id = QT.tag_id')
      ->where('QT.tag_id = ' . $tagId);

  $questions = $app->db->executeFetchAll();

  // Create the view
  $app->views->add('tags/view', [
    'tag' => $res,
    'questions' => $questions,
  ]);
});

// Display user list route
$app->router->add('users', function() use ($app) {
  $app->theme->setTitle('Display user list');

  // Fetch tags from database
  $app->db->select()->from('users');
  $res = $app->db->executeFetchAll();

  // Create the view
  $app->views->add('users/list', [
    'users' => $res,
  ]);
});

// Display user route
$app->router->add('user', function() use ($app) {
  $app->theme->setTitle('View user page');

  // Get the user ID from url
  $userId = $app->request->getGet('id');

  // Fetch the user from database
  $app->db->select()->from('users')->where('id = ' . $userId);
  $app->db->execute();
  $res = $app->db->fetchOne();

  // Fetch users questions_tags
  $app->db->select()
    ->from('questions')
    ->where('user_id = ' . $userId);
  $questions = $app->db->executeFetchAll();

  // Create the view
  $app->views->add('users/view', [
    'user' => $res,
    'questions' => $questions,
  ]);
});

// About route
$app->router->add('about', function() use ($app) {
  $app->theme->setTitle('About us');


  // Create the view
  $app->views->add('about/view', []);
});


$app->router->handle();
$app->theme->render();
