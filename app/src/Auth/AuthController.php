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
      $auth = $this->entityManager
                ->getRepository('\donami\User\User')
                ->findOneBy(['username' => $username]);

      if (!$auth) {
        $this->di->message->error('No user with that name exists', $this->url->create('login'));
        die('No user with that username');
      }

      // If auth is succesfull
      if (password_verify($password, $auth->getPassword())) {
        $this->di->session->set('user', $auth);
        $this->di->message->success('You were successfully logged in');
      }
      else {
        $this->di->message->error('Password did not match any existing users');
        $this->logoutAction();
      }

      return $auth;
    }

    public function isAuthed() {
      return $this->session->get('user');
    }


    public function logoutAction() {
      $this->di->message->success('You were successfully logged out');
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
