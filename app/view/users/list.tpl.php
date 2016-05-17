<div>

  <h1>Display user list</h1>

  <div class="box">

    <div class="title">Users</div>

    <div class="content">
      <?php if (!empty($users)): ?>

        <?php foreach($users as $user): ?>

          <div>
            <a href="<?php echo $this->url->create('user?id=' . $user->getId()) ?>"><?php echo $user->getUsername() ?></a>
          </div>

        <?php endforeach; ?>

      <?php else: ?>

        <p>No users found</p>

      <?php endif; ?>
    </div>

  </div>

</div>
