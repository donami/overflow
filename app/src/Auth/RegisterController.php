<?php
namespace donami\Auth;
/**
 * RegisterController controller
 *
 */
class RegisterController extends RegisterForm implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    /**
     * View register page
     *
     * @return void
     */
    public function viewAction()
    {
      $this->theme->setTitle('Register');

      // Init form
      $form = $this->initForm();

      $this->views->add('auth/register', [
        'form' => $form->getHTML(),
      ]);
    }

    /**
     * Once the form has been submitted, insert data
     *
     * @param  array  $data
     * @return boolean
     */
    public function submitForm($data = array())
    {
      // Check if username is taken
      $checkUsername = $this->db->select(1)->from('users')->where('username = "' . $data['username'] . '"');
      $this->db->execute();
      $usernameExists = $this->db->fetchOne();

      // Check if email is taken
      $checkEmail = $this->db->select(1)->from('users')->where('email = "' . $data['email'] . '"');
      $this->db->execute();
      $emailExists = $this->db->fetchOne();

      if ($usernameExists) {
        die('Username already taken');
      }

      if ($emailExists) {
        die('Email already exists');
      }


      $this->db
        ->insert(
          'users',
          [
            'username'  => $data['username'],
            'password'  => $data['password'],
            'email'     => $data['email'],
          ]
        );

      return true;
    }


}
