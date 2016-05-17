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
      $title = $this->request->getGet('tag');

      // Fetch the tag from database
      $tag = $this->entityManager->getRepository('\donami\Tag\Tag')->findBy(['title' => $title]);

      $questions = [];

      if (!empty($tag)) {
        foreach ($tag as $value) {
          if (!empty($value)) {
            foreach ($value->getQuestions() as $q) {
              $questions[] = $q;
            }
          }
        }
      }

      // Create the view
      $this->views->add('tags/view', [
        'tag' => $tag,
        // 'questions' => $tag->getQuestions(),
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

      $queryBuilder = $this->entityManager->createQueryBuilder();

      $query = $queryBuilder
                ->select('t')
                ->from('\donami\Tag\Tag', 't')
                ->groupBy('t.title')
                ->getQuery();

      $tags = $query->getResult();

      // Create the view
      $this->views->add('tags/list', [
        'tags' => $tags,
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

      $queryBuilder = $this->entityManager->createQueryBuilder();

      $query = $queryBuilder
                ->select('t')
                ->from('\donami\Tag\Tag', 't')
                ->groupBy('t.title')
                ->getQuery();

      return $query->getResult();
    }


}
