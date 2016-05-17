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

      try {
        $user = new \donami\User\User;
        $user->setUsername(trim($data['username']));
        $user->setPassword(trim($data['password']));
        $user->setEmail(trim($data['email']));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
      } catch (\Exception $e) {
        die($e->getMessage());

      }

      return true;
    }


}
