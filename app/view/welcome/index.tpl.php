<h1>Welcome</h1>

<div>
  <h3>Most recent questions</h3>

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

<div>
  <h3>Most active users</h3>

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

<div>
  <h3>Popular tags</h3>

  <?php if (empty($popularTags)): ?>

    <p>No tags found</p>

  <?php else: ?>

    <?php foreach ($popularTags as $tag): ?>

      <div>

        <a href="<?php echo $this->url->create('tag?id=' . $tag->id)?>"><?php echo $tag->title; ?></a>

      </div>

    <?php endforeach; ?>

  <?php endif; ?>

</div>
