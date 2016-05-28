<?php
namespace donami\Question;
/**
 * Question controller
 *
 */
class QuestionController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    /**
     * Get a single question
     *
     * @return void
     */
    public function viewAction($questionId, $sort = 'date_created')
    {
      $this->theme->setTitle('View question');

      // Fetch the question
      if (!$question = $this->entityManager->find('\donami\Question\Question', $questionId)) {
        $this->di->message->error('Unable to find question', $this->url->create());
        return false;
      }

      $authed = false;
      $owner = false;
      $admin = $this->auth->isAdmin();


      if ($this->auth->isAuthed()) {
        $authed = true;

        if ($this->auth->id() == $question->getUser()->getId()) {
          $owner = true;
        }
      }

      // If sorting by rating otherwise sort by newest
      if ($sort == 'rating') {
        $iterator = $question->getAnswers()->getIterator();
        $iterator->uasort(function ($a, $b) {
          return ($a->getRating() < $b->getRating()) ? 1 : -1;
        });
        $answers = new \Doctrine\Common\Collections\ArrayCollection(iterator_to_array($iterator));
      }
      else {
        $answers = $question->getAnswers();
      }

      echo $this->twig->render('questions/view.twig', [
        'question'  => $question,
        'answers'   => $answers,
        'tags'      => $question->getTags(),
        'owner'     => $owner,
        'authed'    => $authed,
        'admin'     => $admin,
      ]);

    }

    /**
     * List questions
     *
     * @return void
     */
    public function listAction()
    {
      $this->theme->setTitle('Questions');

      $questions = $this->entityManager->getRepository('\donami\Question\Question')->findAll();

      echo $this->twig->render('questions/list.twig', [
        'questions' => $questions,
        'authed' => $this->auth->isAuthed(),
      ]);
    }

    /**
     * Get most recent Questions
     *
     * @param  integer $limit  How many rows to fetch
     * @return array
     */
    public function getRecentAction($limit = 10)
    {
      return $this->entityManager
                ->getRepository('\donami\Question\Question')
                ->findBy([], ['date_created' => 'DESC'], $limit);
    }

    /**
     * View action for creating a question
     *
     * @return void
     */
    public function createAction()
    {
      // Make sure that the user is authed
      if (!$this->auth->isAuthed()) {
        $this->response->redirect($this->url->create(''));
      }

      $this->theme->setTitle('Ask a question');

      if (!empty($_POST)) {
          $tags = $this->getTagsFromString($_POST['tags']);

          $this->insert($_POST, $tags);
      }


      echo $this->twig->render('questions/create.twig', []);
    }


    /**
     * Create an array of tags from a string
     *
     * @param  string $string
     * @return array
     */
    private function getTagsFromString($string)
    {
      // Make sure there are no spaces
      $string = str_replace(", ", ",", $string);

      // Pick out the tags in to an array
      $tags = explode(",", $string);

      return $tags;
    }

    /**
     * Get a string from array of tags
     *
     * @param  object $tags
     * @return string
     */
    private function getStringFromTags($tags)
    {
      // Need to create an array since $tags is and object
      $array = [];
      foreach ($tags as $tag) {
        $array[] = $tag->getTitle();
      }

      // Turn the array into one string. Each tag divided by a comma and space
      $string = implode($array, ', ');

      return $string;
    }

    /**
     * Save new question
     * @param  array $data
     * @return void
     */
    private function insert($data, $tags)
    {
      // Make sure that the user is authed
      if (!$this->auth->isAuthed()) {
        die('User not authed');
      }

      $title = trim($data['title']);
      $body = trim($data['body']);

      // Make sure that title is defined
      if (empty($title)) {
        $this->di->message->error('You cannot leave title empty');
        return false;
      }

      // Make sure that body is defined
      if (empty($body)) {
        $this->di->message->error('You cannot leave question field empty');
        return false;
      }

      // Inserting question data
      $question = new \donami\Question\Question;
      $question->setTitle($data['title']);
      $question->setBody($data['body']);

      $user = $this->entityManager->getRepository('\donami\User\User')->find($this->auth->id());

      $question->addUser($user);

      $this->entityManager->persist($question);
      $this->entityManager->flush();

      foreach ($tags as $value) {
        if (!empty($value)) {
          $tag = new \donami\Tag\Tag;
          $tag->setTitle($value);

          $question->addTag($tag);

          $this->entityManager->persist($tag);
          $this->entityManager->flush();
        }
      }

      // Redirect to the created question
      $this->response->redirect($this->url->create('question?id=' . $question->getId()));
    }

    /**
     * The delete action
     *
     * @param  int $questionId
     * @return void
     */
    public function deleteAction($questionId) {
      // If the question was deleted relocate the user
      if ($this->delete($questionId)) {
        $this->di->message->success('The question was removed.');
        $this->response->redirect($this->url->create('questions'));
      }
    }

    /**
     * Remove from Database
     *
     * @param  int $questionId
     * @return boolean
     */
    public function delete($questionId)
    {
      $question = $this->entityManager->getRepository('\donami\Question\Question')->find($questionId);

      $this->entityManager->remove($question);
      $this->entityManager->flush();

      return true;
    }

    /**
     * The edit action
     *
     * @param  int $questionId
     * @return void
     */
    public function editAction($questionId = null)
    {
      $this->theme->setTitle('Edit question');

      if (!empty($_POST)) {
        $questionId = $_POST['id'];

        // If update was succesfull, redirect user
        if ($this->update($_POST['id'], $_POST)) {
          $this->response->redirect($this->url->create('question?id=' . $questionId));
        }
      }

      // Select the question
      if (!$question = $this->entityManager->getRepository('\donami\Question\Question')->find($questionId)) {
        die('Unable to find question');
      }

      // Get the tags as array
      $tags = $question->getTags();

      echo $this->twig->render('questions/edit.twig', [
        'question' => $question,
        'tags'     => $this->getStringFromTags($tags),
      ]);
    }

    /**
     * Update the database with new data
     *
     * @param  int $questionId
     * @return boolean
     */
    private function update($questionId, $data)
    {
      $title = trim($data['title']);
      $body = trim($data['body']);

      // Make sure that title is defined
      if (empty($title)) {
        $this->di->message->error('You cannot leave title empty');
        return false;
      }

      // Make sure that body is defined
      if (empty($body)) {
        $this->di->message->error('You cannot leave question field empty');
        return false;
      }

      // Clear tags before updating
      $question = $this->entityManager->getRepository('\donami\Question\Question')->find($questionId);

      foreach ($question->getTags() as $tag) {

        $question->getTags()->removeElement($tag);
        $this->entityManager->remove($tag);

        $this->entityManager->flush();
      };

      // Get array of tags from the data string
      $tags = $this->getTagsFromString($data['tags']);

      $question->setTitle($data['title']);
      $question->setBody($data['body']);

      $this->entityManager->flush();

      // Add the tags
      foreach ($tags as $value) {
        if (!empty($value)) {
          $tag = new \donami\Tag\Tag;
          $tag->setTitle($value);

          $question->addTag($tag);

          $this->entityManager->persist($tag);
          $this->entityManager->flush();
        }
      }

      return true;
    }

}
