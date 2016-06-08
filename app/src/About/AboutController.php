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

      $breadcrumb = new \Tadcka\Component\Breadcrumbs\Breadcrumb();
      $breadcrumb->add('Home', '');
      $breadcrumb->add('About');

      // Render the view
      echo $this->twig->render('about/view.twig', [
        'breadcrumb' => $breadcrumb,
      ]);
    }

}
