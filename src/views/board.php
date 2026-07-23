<?php if($object == 'project' && isset($project)): ?>
    <div id="board-data" data-project-id="<?= $project->id ?>" class="display-none"></div>
    <div class="project-info">
        <ul class="info-list">
            <li><strong>Project ID:</strong> <?= htmlspecialchars($project->id) ?></li>
            <li><strong>Name:</strong> <?= htmlspecialchars($project->name) ?></li>
            <li>
                <strong>Description:</strong> 
                <?= htmlspecialchars($project->description ?? 'No description provided.') ?>
            </li>
            <li>
                <strong>Repo URL:</strong> 
                <?php if(!empty($project->repo_url)): ?>
                    <a href="<?= htmlspecialchars($project->repo_url) ?>" target="_blank"><?= htmlspecialchars($project->repo_url) ?></a>
                <?php else: ?>
                    N/A
                <?php endif; ?>
            </li>
        </ul>
        
        <a href="?action=edit&object=project&id=<?= urlencode($project->id) ?>" class="btn btn-edit">
            <button type="button">Edit Project details</button>
        </a>

        <form action="?action=addUser&object=project&id=<?= urlencode($project->id) ?>" class="add-user-form" method="post">
            <label for="user">Username/user_id</label>
            <input type="text" name="user" placeholder="012345678-9ab1-2345-6789-10cdefghi1kl">
        </form>
    </div>


    <div id="time-container" class="none">Time until auto-save: <span id="time-display"></span></div>

    <div class="items-container">
    
    <!-- <?= $project->items->count().' Items' ?> -->

    <!-- <?php foreach($project->items as $item): ?>
        <?= $item->name ?>
    <?php endforeach ?> -->
    <!-- <?php $types = ['issue', 'idea', 'todo', 'other']; ?>

    <table>
        <th>
            <?php foreach($types as $type): ?>
                <td><?= $type ?></td>
            <?php endforeach ?>
        </th>
        wtf?
    </table> -->

    <?php 
        $types = ['issue', 'idea', 'todo', 'other']; 
        
        $groupedItems = array_fill_keys($types, []);
        // $groupedItems = array_fill_keys(['issue', 'idea', 'todo', 'other'], []);
        
        foreach ($project->items as $item) {
            $itemType = in_array($item->type, $types) ? $item->type : 'other';
            $groupedItems[$itemType][] = $item;
        }
    ?>
    <div class="board">
        <?php foreach ($types as $type): ?>
            <div class="board-column column-<?= $type ?>">
                <h3> <?= $type ?> <!-- (<?= count($groupedItems[$type]) /* ToDo: use JS instead (for instant(realtime) updates ... )*/ ?> --></h3>
                
                <!-- ToDo: Styles (some classes set already)  - Still same ToDo ... (bit of progress) -->
                <div class="column-items">
                    <?php foreach ($groupedItems[$type] as $item): ?>
                        <div class="item item-<?= $item->type ?> state-<?= $item->state ?>" id="item_<?= $item->id ?>" draggable="true" data-item-id="<?= $item->id ?>">
                            <form action="?action=update&object=item&id=<?= $item->id ?>" method="post" id="itemUpdateForm_<?= $item->id ?>">
                                <span class="id">ID: <?= $item->id ?></span>
                                
                                <label for="name_<?= $item->id ?>"></label>
                                <input class="item-inpt" type="text" id="name_<?= $item->id ?>" name="name" placeholder="Auth Issue" value="<?= htmlspecialchars($item->name) ?>">

                                <label for="type_<?= $item->id ?>">Type</label>
                                <select class="item-inpt" id="type_<?= $item->id ?>" name="type">
                                    <?php foreach ($types as $option): ?>
                                        <option value="<?= $option ?>" <?= $item->type === $option ? 'selected' : '' ?>><?= ucfirst($option) ?></option>
                                    <?php endforeach ?>
                                </select>

                                <label for="description_<?= $item->id ?>">Description</label>
                                <textarea class="item-inpt" id="description_<?= $item->id ?>" name="description"><?= htmlspecialchars($item->description ?? '') ?></textarea>

                                <label for="state_<?= $item->id ?>">State</label>
                                <input class="item-inpt" type="text" id="state_<?= $item->id ?>" name="state" placeholder="In Work" value="<?= htmlspecialchars($item->state ?? '') ?>">

                                <label for="url_<?= $item->id ?>">Link</label>
                                <input class="item-inpt" type="url" id="url_<?= $item->id ?>" name="external_url" placeholder="http://to.your/github/issue" value="<?= htmlspecialchars($item->external_url ?? '') ?>">

                                <label for="order_index_<?= $item->id ?>">Index</label>
                                <input class="item-inpt" type="number" name="order_index" id="order_index_<?= $item->id ?>" value="<?= htmlspecialchars($item->order_index ?? 0) ?>">
                            </form>
                        </div>
                    <?php endforeach ?>
                    <!-- giving it more spaaaaace ... -->
                </div>
            </div>
        <?php endforeach ?>
    </div>

    <!-- <?php foreach($project->items as $item): ?>
        <div class="item item-<?= $item->type ?>" id="item_<?= $item->id ?>">
            <form action="?action=update&object=item&id=<?= $item->id ?>" method="post" id="itemUpdateForm_<?= $item->id ?>">
                <span><?= $item->id ?></span>
                <label for="name">Name/Title</label>

                <input type="text" name="name" placeholder="Auth Issue" value="<?= $item->name ?>">

                <label for="type">Type</label>
                <span><?= $item->type ?></span>
                <select name="type">
                    <?php foreach(['issue', 'todo', 'idea', 'other'] as $option): ?>
                        <option value="<?= $option ?>" <?= $item->type === $option ? 'selected' : '' ?>><?= ucfirst($option) ?></option>
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
    <?php endforeach ?> -->
    </div>

    <!-- <form action="?acrion=store&object=item&pid=<?= $project->id ?>" method="post" id="itemCreationForm"> -->
    <form action="?action=store&object=item&id=<?= $project->id ?>" method="post" id="itemCreationForm">
        <!-- NO F***ing way, i wrote ?artion and THAT WAS THE ONLY PROBLEM -->
        <label for="name">Name/Title</label>
        <input type="text" name="name" placeholder="Auth Issue" required>

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

        <label for="order_index">Index</label>
        <input type="number" name="order_index" value="0" required>

        <input type="submit" value="Add Item">
    </form>
<?php endif ?>

<script src="board.js"></script>

<button id="button-save">Save Changes <span class="now">NOW</span></button>