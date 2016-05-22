<?php

namespace donami\User;

/**
 * Testing User.
 *
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
  /**
   * Test
   *
   * @return void
   */
  public function testSetUsername()
  {
    $user = new \donami\User\User;

    $username = 'username';

    $user->setUsername($username);

    $this->assertEquals($username, $user->getUsername(), 'Username was not set correctly');
  }

  public function testSetPassword()
  {
    $user = new \donami\User\User;

    $password = 'password';

    $user->setPassword($password);

    $this->assertEquals($password, $user->getPassword(), 'Password was not set correctly');
  }
}
