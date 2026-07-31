<?php if($object == 'project' && isset($project)): ?>
    <div id="board-data" data-project-id="<?= $project->id ?>" class="display-none"></div>
    <div class="project-info">
        <ul class="info-list">
            <li>
                <strong>Project ID:</strong> <?= htmlspecialchars($project->id) ?>
            </li>
            <li>
                <strong>Name:</strong> <?= htmlspecialchars($project->name) ?>
            </li>
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
            <?php if((Auth()->id() == $project->user_id || false /* per-project settings if everyone can see the list of user ... ('settings': JSON) */ ) && $project->user->count() > 0): //owner ?>
                <li>
                    Users
                    <ul>
                        <?php foreach($project->users as $project_user): ?>
                            <li><?= $project_user['username'] ?><?= (($project_user['settings']['show_email_stuff_TODO'] ?? false) && $project->getSetting('show_member_emails')) ? "(<i>{$project_user['email']}</i>)" : ''  // ToDo: Add settings for taht and actually implement them ... ?> <?php if((Auth()->id() == $project->user_id)): ?><a href="<?php //echo(create_url_with_attribute(['action' => 'removeuser', 'object' => 'project', 'id' => $project['id']], 'board')) ?>">Remove (not functional rn)</a><?php endif; ?><!-- May use "confirmation" (e.g. modal/alert() ...) --></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endif; ?>
            <br>
        </ul>
        
        <a href="<?= create_url_with_attributes(['action' => 'edit', 'object' => 'project', 'id' => urlencode($project->id)]) ?>" class="btn btn-edit">
            <button type="button">Edit Project details</button>
        </a>
        <a href="<?= create_url_with_attributes(['action' => 'settings', 'object' => 'project', 'id' => urlencode($project->id)]) ?>" class="btn btn-settings">
            <button type="button">Chnage Project Settings</button>
        </a>

        <form action="<?= create_url_with_attributes(['action' => 'addUser', 'object' => 'project', 'id' => urlencode($project->id)]) ?>" class="add-user-form" method="post">
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


    <button id="button-save">Save Changes <span class="now">NOW</span></button>


    <div class="board">
        <?php foreach ($types as $type): ?>
            <div class="board-column column-<?= $type ?>">
                <h3> <?= $type ?> <!-- (<?= count($groupedItems[$type]) /* ToDo: use JS instead (for instant(realtime) updates ... )*/ ?> --></h3>
                <!-- ToDo: Styles (some classes set already)  - Still same ToDo ... (bit of progress) -->
                
                <div class="column-items">
                    <?php foreach ($groupedItems[$type] as $item): ?>
                        <!-- ToDo: consider storing teh collapsed-state (maybe also per-user ...) -->
                        <div class="item item-<?= $item->type ?> state-<?= $item->state ?>" id="item_<?= $item->id ?>" draggable="true" data-item-id="<?= $item->id ?>">
                            <input type="checkbox" name="collapse_toggle" id="collapse_toggle<?= $item->id ?>" class="collapse-toggle">
                            <form action="<?= create_url_with_attributes(['action' => 'update', 'object' => 'item', 'id' => urlencode($project->id)]) ?>" method="post" id="itemUpdateForm_<?= $item->id ?>">
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
                                <textarea clss="item-inpt" id="description_<?= $item->id ?>" name="description"><?= htmlspecialchars($item->description ?? '') ?></textarea>

                                <label for="state_<?= $item->id ?>">State</label>
                                <input class="item-inpt" type="text" id="state_<?= $item->id ?>" name="state" placeholder="In Work" value="<?= htmlspecialchars($item->state ?? '') ?>">

                                <label for="url_<?= $item->id ?>">Link</label>
                                <input class="item-inpt" type="url" id="url_<?= $item->id ?>" name="external_url" placeholder="http://to.your/github/issue" value="<?= htmlspecialchars($item->external_url ?? '') ?>">

                                <label for="order_index_<?= $item->id ?>">Index</label>
                                <input class="item-inpt" type="number" name="order_index" id="order_index_<?= $item->id ?>" value="<?= htmlspecialchars($item->order_index ?? 0) ?>">

                                <?php if(!empty($project->repo_url)): ?>
                                    <label for="commit_id_<?= $item->id ?>">Commit ID</label>
                                    <input class="item-inpt" type="text" name="commit_id" id="commit_id_<?= $item->id ?>" value="<?= htmlspecialchars($item->commit_id ?? '') ?>">
                                <?php endif; ?>


                            </form>
                            <?php //if(!empty($project->hasMedia())): ?>
                            <?php if($item->hasMedia()): ?>
                            <!-- ToDO: Also add "delete" and for for uploading new ... -->
                                <?php //foreach($project->media() as $media): ?>
                                <?php //foreach($item->media() as $media): ?>
                                <?php foreach($item->media as $media): ?>
                                    <!-- HOW STUPID AM I? – i used $project instead of $item ... -->
                                    <div class="media-item">
                                        <img src="<?= $media->url ?>">
                                        <a href="<?= create_url_with_attributes(['action' => 'deleteMedia', 'object' =>'item', 'id' => $item->id, 'mid' => $media->id]) ?>" class="delete-media">Delete</a>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <form action="<?= create_url_with_attributes(['action' => 'uploadMedia', 'object' => 'item', 'id' => $item->id]) ?>" id="upload-media-form" method="post" enctype="multipart/form-data">
                                <label for="media_upload_<?= $item->id ?>">Add New Media</label>
                                <input type="file" name="media_upload" id="media_upload_<?= $item->id ?>">
                                <span>supported: svg, png, jpg(=jpeg), webp, ...</span>
                                <input type="submit" value="Upload selected media">
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

    <button id="button-save-2">Save Changes <span class="now">NOW</span></button>


    
    <!-- <form action="?acrion=store&object=item&pid=<?= $project->id ?>" method="post" id="itemCreationForm"> -->
    <form action="<?= create_url_with_attributes(['action' => 'store', 'object' =>'item', 'id' => $project->id]) ?>" method="post" id="itemCreationForm" enctype="multipart/form-data">
        <h3>Create new item</h3>

        <!-- NO F***ing way, i wrote ?artion and THAT WAS THE ONLY PROBLEM -->
        <label for="name">Name/Title</label>
        <input type="text" name="name" placeholder="Auth Issue" required>

        <label for="type">Type</label>
        <select name="type">
            <?php foreach($types as $option): ?>
                <option value="<?= $option ?>"><?= ucfirst($option) ?></option>
            <?php endforeach ?>
        </select>

        <label for="description">Description (Optional)</label>
        <textarea name="description"></textarea>

        <label for="external_url">External Link</label>
        <input type="url" name="external_url" placeholder="http://to.your/github/issue">

        <label for="order_index">Index (smaller = upper(closer to teh top of the list)) (Optional)</label>
        <input type="number" name="order_index" value="0" required>

        <?php if(!empty($project->repo_url)): ?>
            <label for="commit_id">Commit ID (Optional)</label>
            <input class="item-inpt" type="text" name="commit_id">
        <?php endif; ?>

        <?php if(true): // Setting  "allow_media" or sth. ?>
            <!-- <label for="evidence"> -->
            <label for="image">
                <!-- Image (e.g. Evicence of Faliure) -->
                Image (e.g. Screenshot of UI-error)
            </label>
            <input type="file" name="image" id="image">
            <note>please do not uplaod any <i>illegal</i> images.</note>
            <br>
        <?php endif; ?>

        <input type="submit" value="Add Item">
    </form>
<?php else: ?>
    <h1>Seems like there was a big mistake made somewhere, developng this application</h1>
<?php endif; ?>



<script src="board.js"></script>