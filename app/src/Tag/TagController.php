<?php
namespace donami\Tag;
/**
 * Question controller
 *
 */
class TagController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    /**
     * Get a single tag
     *
     * @return void
     */
    public function viewAction($tagId)
    {
      $this->theme->setTitle('View tag');

      // Get the tag ID from url
      $tagId = $this->request->getGet('id');

      // Fetch the tag from database
      $this->db->select()->from('tags')->where('id = ' . $tagId);
      $this->db->execute();
      $res = $this->db->fetchOne();

      // Get questions with this tag
      $this->db->select("Q.title, Q.id")
          ->from('questions AS Q')
          ->leftJoin('questions_tags AS QT', 'Q.id = QT.question_id')
          ->leftJoin('tags AS T', 'T.id = QT.tag_id')
          ->where('QT.tag_id = ' . $tagId);

      $questions = $this->db->executeFetchAll();

      // Create the view
      $this->views->add('tags/view', [
        'tag' => $res,
        'questions' => $questions,
      ]);
    }

    /**
     * List tags
     *
     * @return void
     */
    public function listAction()
    {
      $this->theme->setTitle('Tags');

      // Fetch tags from database
      $this->db->select()->from('tags');
      $res = $this->db->executeFetchAll();

      // Create the view
      $this->views->add('tags/list', [
        'tags' => $res,
      ]);
    }

    /**
     * Get most popular tags
     *
     * @param  integer $limit
     * @return array
     */
    public function getPopularAction($limit = 10)
    {
      $this->db
        ->select('
          QT.tag_id AS id,
          COUNT(QT.tag_id) AS count,
          T.title
        ')
        ->from('questions_tags AS QT')
        ->join('tags AS T', 'T.id = QT.tag_id')
        ->groupBy('tag_id')
        ->orderBy('count DESC')
        ->limit($limit);

      $res = $this->db->executeFetchAll();

      return $res;
    }

}
