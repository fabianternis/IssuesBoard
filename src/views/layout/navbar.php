<nav>
    <a class="nav-link" href="/">Home</a>
    <?php if($auth->check()): ?>
        <a class="nav-link" href="/dashboard">Dashboard</a>
        <span>user: <span class="username"><?= $auth->user()->username ?></span></span>
        <a class="logout" href="?action=logout">Log out</a>

        <!-- <a href="<?= create_url_with_attributes(['action' => 'privacy', 'object' => 'settings']) ?>">Settings</a> -->
    <?php else: ?>
        <a class="nav-link" href="/auth">Auth</a>
    <?php endif ?>
    
    <!-- <?php if($auth->check()): ?>
        <a href="<?= create_url_with_attributes(['action' => 'logout']) ?>">Log out</a>
    <?php endif ?> -->

    <!-- ToDo: STYLES ... -->

    <!-- <div style="width: 10vw"></div> -->
    <a style="margin-left: 10vw" href="https://github.com/fabianternis/IssuesBoard/issues/new">REPORT A BUG/Issue or request Feature(s)</a>
</nav>