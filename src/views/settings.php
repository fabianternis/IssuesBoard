<?php

$page = $_GET['page'];
// no switch(), useing if() and co. ... instead
$pages = ['none', 'danger_zone']; // for a nav ...

// ToDo: add &page and /seettings to the action-attributes ...
?>
<div class="settings-nav">
    <?php foreach($pages as $page): ?>
        <a class="settings-nav-link" href="TODO"></a>
    <?php endforeach; ?>
</div>
<div class="settings-page">
    <?php if($page == 'none'): ?>
        <h3>No "none" here</h3>
    <?php elseif($page == 'danger_zone'): ?>
        <?php if(Auth()->user()->get_deletion_status() == 'open'): ?>
            <form action="?action=request_deletion&object=settings" method="post">
                <label for="deletion_notice_accepted">I accept the conditions of this action ... Account will be deleted in 28days. Ultil then the deletion can be stopped at any time. Recovery aftr 28 Days pass may not be possible. Deletion may never actually be fulfilled or data may remain somewhere. Opening a new account with the same username may not be possible.</label>
                <input type="checkbox" name="deletion_notice_accepted" id="deletion_notice_accepted">
            </form>
        <?php elseif(Auth()->user()->get_deletion_status() == 'pending'): ?>
            <form action="?action=abort_deletion&object=settings" method="post">
                <label for="recovery_notice_accepted">I accept the conditions of this action ... Delition will be aborted ...</label>
                <input type="checkbox" name="recovery_notice_accepted" id="recovery_notice_accepted">
            </form>
        <?php else: ?>
            <h3>It seems like this account has been deleted!</h3>
        <?php endif; ?>
    <?php else: ?>
        none (or <?= $page ?? '"NULL"' ?>)
    <?php endif; ?>
</div>
<br>
<div class="disclaimer wip-disclaimer">This is still WorkInProgress ... visit <a href="https://github.com/fabianternis/issuesboard">GitHub</a> for more info.</div>