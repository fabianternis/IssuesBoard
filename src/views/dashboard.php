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
        <a class="item" href="<?= create_url_with_attributes(['action' => 'show', 'obkect' => 'project', 'id' => urlencode($project->id)]) ?>">
            <span class="name"><?= htmlspecialchars($project->id) ?></span>
            <?php if(!empty($project->description)): ?>

                <p class="description"><?= htmlspecialchars($project->description) ?></p>
            <?php endif ?>
        </a>
        
        <span class="owner_name">by "<?= htmlspecialchars($project->owner->username ?? 'Unknown') ?>"</span>
    <?php endforeach ?>
    </div>

    <!-- <a href="<?= create_url_with_attributes(['action' => 'create', 'obkect' => 'project']) ?>">Create new Project</a> -->
<?php else: ?>
    you seem to have no Projects.
<?php endif ?>

<a href="<?= create_url_with_attributes(['action' => 'create', 'obkect' => 'project']) ?>">Create <?= Auth()->user()->hasProjects() ? 'a new Project'  : 'one' ?></a>