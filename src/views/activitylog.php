<?php
$objects_list = ['projects', 'items', 'media'];
?>
<div>
    <nav>
        <ul>
            <?php foreach($objects_list as $_object): ?>
                <li>
                    <a href="<?= create_url_with_attributes(['object' => $_object], '/activity') ?>" class="<?= ($object == $_object) ? 'active' : '' ?>"><?= ucfirst($_object) ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <div>
        <?php foreach($log_items as $item): ?>
            <div>
                <?php if(!isset($object)): ?>
                    <span class="object-name"><?= /*ucfirst($oject)*/ ucfirst($item->object_type) ?></span>
                <?php endif; ?>
                <span class="action action-<?= $item->action ?>"><?= ucfirst($item->action) ?></span>

                <span class="date"><?= to_relative_time($item->performed_at) ?></span>
                
            </div>
        <?php endforeach; ?>
    </div>
</div>