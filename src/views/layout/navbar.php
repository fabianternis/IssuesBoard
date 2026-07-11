<nav>
    <?php if($auth->check()): ?>
        <span class="username"><?= $auth->user()->username ?></span>
        <a class="logout" href="?action=logout">Log out</a>
    <?php endif ?>
</nav>