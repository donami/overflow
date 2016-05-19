<?php
namespace donami\Auth;
/**
 * Auth controller
 *
 */
class AuthController extends LoginForm implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    /**
     * Login method
     *
     * @return mixed
     */
    public function loginAction($username, $password)
    {
      $auth = $this->entityManager->getRepository('\donami\User\User')->findOneBy(['username' => $username, 'password' => $password]);

      // If auth is succesfull
      if ($auth) {
        $this->di->session->set('user', $auth);
      }
      else {
        $this->logoutAction();
      }

      return $auth;
    }

    public function isAuthed() {
      return $this->session->get('user');
    }


    public function logoutAction() {
      $this->di->session->set('user', null);
    }

    public function loginViewAction()
    {
      $this->theme->setTitle('Login');

      // Initialize login form
      $form = $this->initForm();

      // Display instead of form if already authed
      $authedHTML = '<p>You are already authed</p>';

      // Check if the user is already authed
      $formHTML = $this->auth->isAuthed() ? $authedHTML : $form->getHTML();

      echo $this->twig->render('auth/login.twig', [
        'form' => $formHTML,
      ]);
    }


}
