<?php
namespace donami\Auth;
/**
 * Auth controller
 *
 */
class CAuth
{

  use \Anax\DI\TInjectionAware;


  public function isAuthed() {
    return $this->di->session->get('user') or false;
  }

  public function user()
  {
    return $this->di->session->get('user');
  }

  public function username()
  {
    return $this->di->session->get('user')->username;
  }

  public function id()
  {
    return $this->di->session->get('user')->id;
  }

  public function isAdmin()
  {
    if ($this->isAuthed()) {
      if ($this->di->session->get('user')->admin) {
        return true;
      }
    }
    return false;
  }
}
