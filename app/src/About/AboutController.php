<?php
namespace donami\About;
/**
 * About controller
 *
 */
class AboutController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    /**
     * Display about view
     *
     * @return void
     */
    public function viewAction()
    {
      $this->theme->setTitle('About us');

      // Create the view
      $this->views->add('about/view', []);
    }

}
