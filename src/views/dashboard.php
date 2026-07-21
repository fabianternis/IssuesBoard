<h1>Welcome, <?= Auth()->user()->username ?></h1>

<?php if(Auth()->user()->hasProjects()): ?>
    you have <?= Auth()->user()->projects()->count() ?> Projects.
    <div class="projects-grid">
    <?php foreach(Auth()->user()->ownedProjects as $project): ?>
        <a class="item" href="?action=show&object=project&id=<?= $project->id ?>">
            <span class="name"><?= $project->id ?></span>
            <?php if(isset($project->description)): ?>
                <p class="description"><?= $project->description ?></p>
            <?php endif ?>
        </a>
    <?php endforeach ?>
    <?php foreach(Auth()->user()->projects as $project): ?>
        <a class="item" href="?action=show&object=project&id=<?= urlencode($project->id) ?>">
            <span class="name"><?= htmlspecialchars($project->id) ?></span>
            <?php if(!empty($project->description)): ?>
                <p class="description"><?= htmlspecialchars($project->description) ?></p>
            <?php endif ?>
        </a>
        <span class="owner_name">by "<?= htmlspecialchars($project->owner->username ?? 'Unknown') ?>"</span>
    <?php endforeach ?>
    </div>
<?php else: ?>
    you seem to have no Projects.
    <a href="?action=create&object=project">Create one</a>
<?php endif ?>

<a href="?action=create&object=project">Create new Project</a>