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

      // Render the view
      echo $this->twig->render('about/view.twig', []);
    }

}
