<?php
namespace donami\User;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="users")
 * @UniqueEntity("username")
 */
class User
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * @Column(type="string", unique=true)
     * @var string
     */
    protected $username;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $password = '';

    /**
    * @Column(type="string")
    * @var string
    */
    protected $email = '';

    /**
     * @Column(type="integer")
     * @var int
     */
    protected $admin = 0;

    /**
     * @Column(type="integer")
     * @var int
     */
    protected $posts = 0;

    /**
     * @OneToMany(targetEntity="\donami\Answer\Answer", mappedBy="user")
     * @var int
     */
    protected $answers = null;

    /**
     * @OneToMany(targetEntity="\donami\Comment\Comment", mappedBy="user")
     * @var int
     */
    protected $comments = null;

    /**
     * @OneToMany(targetEntity="\donami\Question\Question", mappedBy="user")
     * @var int
     */
    protected $questions = null;

    /**
     * @OneToMany(targetEntity="\donami\Point\Point", mappedBy="user")
     * @var \donami\Point\Point[]
     */
    protected $points = null;


    /**
     * @OneToOne(targetEntity="\donami\Reputation\Reputation", cascade={"persist"})
     * @var \donami\Reputation\Reputation
     */
    protected $reputation;

    /**
     * @Column(type="datetime")
     * @var \Datetime
     */
    protected $date_created;


    public function __construct()
    {
      $this->answers = new ArrayCollection();
      $this->questions = new ArrayCollection();
      $this->posts = 0;
    }


    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPosts()
    {
        return $this->posts;
    }

    public function setPosts($posts)
    {
        $this->posts = $posts;
    }

    public function getAnswerCount()
    {
      return $this->answer_count;
    }

    public function getAnswers()
    {
        return $this->answers;
    }

    public function setAnswers($answers)
    {
        $this->answers = $answers;
    }

    public function getQuestions()
    {
        return $this->questions;
    }

    public function getQuestionCount()
    {
        return $this->question_count;
    }

    public function setQuestions($questions)
    {
        $this->questions = $questions;
    }

    public function getComments()
    {
      return $this->comments;
    }

    public function getAdmin()
    {
      return $this->admin;
    }

    public function getImageSrc($size = 200)
    {
      $sizeString = '?size=' . $size;

      $hash = md5( strtolower( trim( $this->email ) ) );
      return 'http://www.gravatar.com/avatar/' . $hash . $sizeString . '.jpg';
    }

    public function getImage()
    {
      return '<img src="' . $this->getImageSrc . '" />';
    }

    /**
     * Get the value of Reputation
     *
     * @return \donami\Reputation\Reputation
     */
    public function getReputation()
    {
      return $this->reputation;
    }

    /**
     * Set the value of Reputation
     *
     * @param \donami\Reputation\Reputation reputation
     *
     * @return self
     */
    public function setReputation(\donami\Reputation\Reputation $reputation)
    {
      $this->reputation = $reputation;
      $reputation->setUser($this);

      return $this;
    }

    /**
    * Get the value of Date Created
    *
    * @return \Datetime
    */
    public function getDateCreated()
    {
      return $this->date_created;
    }

    /**
    * Set the value of Date Created
    *
    * @param \Datetime date_created
    *
    * @return self
    */
    public function setDateCreated(\Datetime $date_created)
    {
      $this->date_created = $date_created;

      return $this;
    }


}
