<?php
require __DIR__.'/config_with_app.php';

$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
$app->theme->configure(ANAX_APP_PATH . 'config/theme.php');

$app->navbar->configure(ANAX_APP_PATH . 'config/navbar.php');


$app = new \Anax\Kernel\CAnax($di);

$app->router->add('', function() use ($app) {

    $app->theme->setTitle("Välkommen till min sida");

    // $content = $app->fileContent->get('me.md');
    // $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
    //
    // $byline = $app->fileContent->get('byline.md');
    // $byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');
    //
    // // Add the side bar
    // $app->views->add('welcome/sidebar', [], 'sidebar');
    //
    // $app->views->add('me/welcome', [
    //     'content' => $content,
    //     'byline' => $byline,
    // ]);
    //
    // $app->dispatcher->forward([
    //     'controller' => 'comment',
    //     'action'     => 'view',
    //     'params'     => array('home'),
    // ]);
    //
    // $app->dispatcher->forward([
    //     'controller' => 'comment',
    //     'action'     => 'add',
    //     'params'     => array('home'),
    // ]);
    //
    // // Add the footer columns
    // $app->views->add('footer/first', [], 'footer-col-1');
    // $app->views->add('footer/second', [], 'footer-col-2');
    // $app->views->add('footer/third', [], 'footer-col-3');
    // $app->views->add('footer/fourth', [], 'footer-col-4');

});


$app->router->handle();
$app->theme->render();
