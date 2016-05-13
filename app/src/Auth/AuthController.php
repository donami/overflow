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
      $check = $this->db
                  ->select('id, username, email')
                  ->from('users')
                  ->where('username = "' . $username . '" && password = "' . $password . '"')
                  ->limit(1);

      $this->db->execute();
      $res = $this->db->fetchOne();

      // If auth is succesfull
      if ($res) {
        $this->di->session->set('user', $res);
      }
      else {
        $this->logoutAction();
      }

      return $res;
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

      $this->views->add('auth/login', [
        'form' => $formHTML,
      ]);
    }


}
