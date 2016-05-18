<?php

namespace donami\Tag;

/**
 * Testing Tag.
 *
 */
class TagTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test
     *
     * @return void
     *
     */
    public function testSetTitle()
    {
        $tag = new \donami\Tag\Tag;
        $title = 'someTitle';

        $tag->setTitle($title);

        $this->assertEquals($title, $tag->getTitle(), 'Tag title does not match');
    }


    /**
     * Test
     *
     * @return void
     */
    public function testAddQuestion()
    {
      $question = new \donami\Question\Question;

      $tag = new \donami\Tag\Tag;

      $tag->addQuestion($question);

      $this->assertTrue($tag->getQuestions()->contains($question), 'Question was not added to the tag');
    }


    /**
     * Test
     *
     * @return void
     */
    public function testGetQuestions()
    {
      $tag = new \donami\Tag\Tag;

      $this->assertEquals( get_class($tag->getQuestions() ), 'Doctrine\Common\Collections\ArrayCollection', 'Attached questions is not ArrayCollection');
    }
}
