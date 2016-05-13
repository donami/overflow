<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'navbar',

    // Here comes the menu strcture
    'items' => [

        // This is a menu item
        'home'  => [
            'text'  => 'Home',
            'url'   => $this->di->get('url')->create(''),
            'title' => 'Go to the start page'
        ],

        // This is a menu item
        'questions'  => [
            'text'  => 'Questions',
            'url'   => $this->di->get('url')->create('questions'),
            'title' => 'Questions',
        ],

        // This is a menu item
        'tags' => [
            'text'  => 'Tags',
            'url'   => $this->di->get('url')->create('tags'),
            'title' => 'Tags'
        ],

        // This is a menu item
        'users' => [
            'text'  => 'Users',
            'url'   => $this->di->get('url')->create('users'),
            'title' => 'Display user list'
        ],

        // This is a menu item
        'about' => [
            'text'  => 'About',
            'url'   => $this->di->get('url')->create('about'),
            'title' => 'About page'
        ],

        // This is a menu item
        'login' => [
            'text'  => 'Login',
            'url'   => $this->di->get('url')->create('login'),
            'title' => 'Login page',
            'class' => 'login',
        ],

        // This is a menu item
        'register' => [
            'text'  => 'Register',
            'url'   => $this->di->get('url')->create('register'),
            'title' => 'Register page',
            'class' => 'register',
        ],

        // This is a menu item
        'logout' => [
            'text'  => 'Logout',
            'url'   => $this->di->get('url')->create('logout'),
            'title' => 'Logout page',
            'class' => 'logout',
        ],

    ],



    /**
     * Callback tracing the current selected menu item base on scriptname
     *
     */
    'callback' => function ($url) {
        if ($url == $this->di->get('request')->getCurrentUrl(false)) {
            return true;
        }
    },



    /**
     * Callback to check if current page is a decendant of the menuitem, this check applies for those
     * menuitems that has the setting 'mark-if-parent' set to true.
     *
     */
    'is_parent' => function ($parent) {
        $route = $this->di->get('request')->getRoute();
        return !substr_compare($parent, $route, 0, strlen($parent));
    },



   /**
     * Callback to create the url, if needed, else comment out.
     *
     */
   /*
    'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },
    */
];
