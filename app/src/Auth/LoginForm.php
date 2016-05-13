<?php
namespace donami\Auth;
/**
 * Login form
 */
class LoginForm extends \Mos\HTMLForm\CForm
{
  protected function initForm()
  {
    $form = $this->di->form->create([], [
        'username' => [
            'type'        => 'text',
            'label'       => 'Username',
            // 'required'    => true,
            // 'validation'  => ['not_empty'],
        ],
        'password' => [
            'type'        => 'password',
            'label'       => 'Password',
            // 'required'    => true,
            // 'validation'  => ['not_empty', 'email_adress'],
        ],
        'submit' => [
            'type'      => 'submit',
            'callback'  => [$this, 'callbackSubmit'],
        ],
    ]);

    $form->check([$this, 'callbackSuccess'], [$this, 'callbackFail']);

    return $form;
  }

  /**
   * Callback If form success
   *
   */
  protected function callbackSuccess($form)
  {
    $this->response->redirect($this->url->createRelative(''));
  }


  /**
   * Callback What to do if the form was submitted?
   *
   */
  protected function callbackSubmit($form)
  {
    $username = $form->Value('username');
    $password = $form->Value('password');

    if ($this->loginAction($username, $password)) {
      return true;
    }
    return false;
  }

  /**
   * Callback If form failed
   *
   */
  protected function callbackFail($form)
  {
    $this->response->redirect($this->url->createRelative('login'));
  }
}
