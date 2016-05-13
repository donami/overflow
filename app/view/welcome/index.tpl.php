<h1>Welcome</h1>

<div class="box">

  <div class="title">Most recent questions</div>

  <div class="content">

    <?php if (empty($recentQuestions)): ?>

      <p>No recent questions found</p>

    <?php else: ?>

      <?php foreach ($recentQuestions as $question): ?>

        <div>

          <a href="<?php echo $this->url->create('question?id=' . $question->id)?>"><?php echo $question->title; ?></a>

        </div>

      <?php endforeach; ?>

    <?php endif; ?>

  </div>

</div>


<div class="box">

  <div class="title">Most active users</div>

  <div class="content">

    <?php if (empty($activeUsers)): ?>

      <p>No users found</p>

    <?php else: ?>

      <?php foreach ($activeUsers as $user): ?>

        <div>

          <a href="<?php echo $this->url->create('user?id=' . $user->id)?>"><?php echo $user->username; ?></a>

        </div>

      <?php endforeach; ?>

    <?php endif; ?>

  </div>

</div>

<div class="box">

  <div class="title">Popular tags</div>

  <div class="content">

    <?php if (empty($popularTags)): ?>

      <p>No tags found</p>

    <?php else: ?>

      <?php foreach ($popularTags as $tag): ?>

          <a class="tag" href="<?php echo $this->url->create('tag?id=' . $tag->id)?>"><?php echo $tag->title; ?></a>

      <?php endforeach; ?>

    <?php endif; ?>

  </div>

</div>
