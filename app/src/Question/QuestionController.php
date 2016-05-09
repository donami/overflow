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
    public function viewAction($questionId)
    {
      $this->theme->setTitle('View question');

      $this->db->select()->from('questions')->where('id = ' . $questionId);
      $this->db->execute();
      $res = $this->db->fetchOne();

      $this->views->add('questions/view', [
        'question' => $res,
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

      $this->db->select()->from('questions');
      $res = $this->db->executeFetchAll();

      $this->views->add('questions/index', [
        'questions' => $res,
      ]);
    }

}
