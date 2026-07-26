<nav>
    <a class="nav-link" href="/">Home</a>
    <?php if($auth->check()): ?>
        <a class="nav-link" href="/dashboard">Dashboard</a>
        <span>user: <span class="username"><?= $auth->user()->username ?></span></span>
        <a class="logout" href="?action=logout">Log out</a>
    <?php else: ?>
        <a class="nav-link" href="/auth">Auth</a>
    <?php endif ?>
</nav>