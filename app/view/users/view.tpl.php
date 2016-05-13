<div class="user-container">

  <div class="information">

    <div class="image">
      <img class="profile" src="http://cdn.devilsworkshop.org/files/2013/01/enlarged-facebook-profile-picture.jpg" alt="Profile picture">
    </div>

    <h1><?php echo $user->username ?></h1>

    <ul>
      <li><i class="fa fa-star fa-lg fa-fw"></i> &nbsp; Points: 10</li>
      <li><i class="fa fa-question fa-lg fa-fw"></i> &nbsp; Questions: <?php echo $user->questions ?></li>
      <li><i class="fa fa-lightbulb-o fa-lg fa-fw"></i> &nbsp; Answers: <?php echo $user->answers ?></li>
      <li><i class="fa fa-comments fa-lg fa-fw"></i> &nbsp; Comments: <?php echo $user->posts ?></li>
    </ul>

  </div>

  <div class="main">

    <div class="box">

      <div class="title">Questions by this user</div>

      <div class="content">

        <?php if (empty($questions)): ?>

          <p><em>No questions by this user yet</em></p>

        <?php else: ?>

          <?php foreach ($questions as $question): ?>

            <div>

              <a href="<?php echo $this->url->create('question?id=' . $question->id) ?>"><?php echo $question->title ?></a>

            </div>

          <?php endforeach; ?>

        <?php endif; ?>

      </div>

    </div>



    <div class="box">

      <div class="title">Answers by this user</div>

      <div class="content">
        <?php if (empty($answers)): ?>

          <p><em>No answers by this user yet</em></p>

        <?php else: ?>

          <?php foreach ($answers as $answer): ?>

            <div>

              <a href="<?php echo $this->url->create('question?id=' . $answer->question_id) ?>"><?php echo $answer->question_title ?></a>

            </div>

          <?php endforeach; ?>

        <?php endif; ?>

      </div>

    </div>


    <div class="box">
      <div class="title">Questions answered by this user</div>
      <div class="content">
        <?php if (empty($bestAnswers)): ?>

          <p><em>No answers has been accepted as the best answer yet</em></p>

        <?php else: ?>

          <?php foreach ($bestAnswers as $answer): ?>

            <div>

              <a href="<?php echo $this->url->create('question?id=' . $answer->id) ?>"><?php echo $answer->title ?></a>

            </div>

          <?php endforeach; ?>

        <?php endif; ?>

      </div>
    </div>

  </div>


</div>
