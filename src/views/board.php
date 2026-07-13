<?php if($object == 'project' && isset($project)): ?>
    
    <div>ID: <?php echo $project->id; ?></div>
    <div>Name: <?php echo $project->name; ?></div>

    <?php foreach(($project->items() ?? []) as $item): ?>

    <?php endforeach ?>
<?php endif ?>