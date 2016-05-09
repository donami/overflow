<div>
  <h1>Display user list</h1>

  <div>
    <?php if (!empty($users)): ?>

      <?php foreach($users as $user): ?>

        <div>
          <a href="<?php echo $this->url->create('user?id=' . $user->id) ?>"><?php echo $user->username ?></a>
        </div>

      <?php endforeach; ?>

    <?php else: ?>

      <p>No users found</p>

    <?php endif; ?>
  </div>
</div>