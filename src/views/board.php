<?php if($object == 'project' && isset($project)): ?>
    
    <div>ID: <?php echo $project->id; ?></div>
    <div>Name: <?php echo $project->name; ?></div>

    <?php foreach(($project->items() ?? []) as $item): ?>

    <?php endforeach ?>

    <!-- <form action="?acrion=create&object=item&pid=" -->
    <form action="?acrion=store&object=item&id=<?= $project->id ?>" method="post">
        <label for="name">Name/Title</label>
        <input type="text" name="name" placeholder="Auth Issue">

        <label for="type">Type</label>
        <select name="type">
            <?php foreach(['issue', 'todo', 'idea', 'other'] as $option): ?>
                <option value="<?= $option ?>"><?= ucfirst($option) ?></option>
            <?php endforeach ?>
        </select>

        <label for="description">Description (Optional)</label>
        <textarea name="description"></textarea>

        <label for="external_url">Link</label>
        <input type="url" name="external_url" placeholder="http://to.your/github/issue">
    </form>
<?php endif ?>