<?php if($object == 'project' && isset($project)): ?>
    
    <div>ID: <?php echo $project->id; ?></div>
    <div>Name: <?php echo $project->name; ?></div>
    <div class="items-container">
    <?php foreach(($project->items() ?? []) as $item): ?>
        <div class="item item-<?= $item->type ?>" id="item_<?= $item->id ?>">
            <span><?= $item->id ?></span>
            <form action="?acrion=update&object=item&id=<?= $item->id ?>" method="post" id="itemUpdateForm_<?= $item->id ?>">
                <label for="name">Name/Title</label>
                <input type="text" name="name" placeholder="Auth Issue" value="<?= $item->name ?>">

                <label for="type">Type</label>
                <span><?= $item->type ?></span>
                <select name="type">
                    <?php foreach(['issue', 'todo', 'idea', 'other'] as $option): ?>
                        <option value="<?= $option ?>"><?= ucfirst($option) ?></option> <!-- ToDo: set the "current one" to active -->
                    <?php endforeach ?>
                </select>

                <label for="description">Description (Optional)</label>
                <textarea name="description"><?= $item->description ?? '' ?></textarea>

                <label for="state">State</label>
                <input type="text" name="state" placeholder="In Work" value="<?= $item->state ?? '' ?>">

                <label for="external_url">Link</label>
                <input type="url" name="external_url" placeholder="http://to.your/github/issue" value="<?= $item->external_url ?? '' ?>">
                
                <input type="submit" value="Update Item">
            </form>
        </div>
    <?php endforeach ?>
    </div>

    <!-- <form action="?acrion=create&object=item&pid=" -->
    <form action="?acrion=store&object=item&pid=<?= $project->id ?>" method="post" id="itemCreationForm">
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

        <input type="submit" value="Add Item">
    </form>
<?php endif ?>