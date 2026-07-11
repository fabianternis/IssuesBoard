<h1>Welcome, <?= Auth()->user()->username ?></h1>

<?php if(Auth()->user()->hasProjects()): ?>
    you have <?= count(Auth()->user()->projects()) ?> Projects;
<?php else: ?>
    you seem to have no Projects.
    <a href="?action=create&object=project">Create one</a>
<?php endif ?>