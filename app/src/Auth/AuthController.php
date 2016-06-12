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
        $this->di->message->error('Password did not match any existing users', $this->url->create('login'));
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

    public function editAction()
    {
      if (!empty($_POST)) {
        $this->save($_POST);
      }

      $breadcrumb = new \Tadcka\Component\Breadcrumbs\Breadcrumb();
      $breadcrumb->add('Home', '');
      $breadcrumb->add('Profile', 'user?id=' . $this->auth->id());
      $breadcrumb->add('Edit');

      echo $this->twig->render('auth/edit.twig', [
        'breadcrumb' => $breadcrumb,
        'user' => $this->auth->user(),
      ]);
    }

    /**
     * Saving a profile
     *
     * @param  array $data
     * @return \donami\User\User
     */
    private function save($data) {
      $user = $this->entityManager->getRepository('\donami\User\User')->find($this->auth->user()->getId());

      $user->setUsername( trim($data['username']) );
      $user->setEmail( trim($data['email']) );
      $user->setDescription( trim($data['description']) );

      // Only update password if changed
      if (!empty( trim($data['password']) )) {
        $hash = password_hash( trim($data['password']), PASSWORD_DEFAULT );
        $user->setPassword($hash);
      }

      $this->entityManager->persist($user);
      $this->entityManager->flush();

      // Update session object
      $this->di->session->set('user', $user);

      $this->di->message->success('Your profile was updated successfully', $this->url->create('edit-profile'));
      return $user;
    }

}
