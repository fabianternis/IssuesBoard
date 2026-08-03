<?php

$page = $_GET['page'];
// no switch(), useing if() and co. ... instead
$pages = ['none', 'danger_zone']; // for the nav ...

// ToDo: add &page and /seettings to the action-attributes ...
?>
<div class="settings-nav">
    <?php foreach($pages as $page_from_list): ?>
        <a class="settings-nav-link" href="<?= create_url_with_attributes(['page' => $page_from_list]) ?>"><?= $page_from_list ?></a>
    <?php endforeach; ?>
</div>

<div class="settings-page">
    <?php //$hasForm = true ?>
    <?php if($page == 'none'): ?>
        <h3>No "none" here</h3>
    <?php elseif($page == 'danger_zone'): ?>
        <?php if(Auth()->user()->get_deletion_status() == 'open'): ?>
            <form action="<?= create_url_with_attributes([ 'action' => 'request_deletion', 'object' => 'settings']) ?>" method="post">
                <label for="deletion_notice_accepted">I accept the conditions of this action ... Account will be deleted in 28days. Ultil then the deletion can be stopped at any time. Recovery aftr 28 Days pass may not be possible. Deletion may never actually be fulfilled or data may remain somewhere. Opening a new account with the same username may not be possible.</label>
                <input type="checkbox" name="deletion_notice_accepted" id="deletion_notice_accepted">
            </form>
        <?php elseif(Auth()->user()->get_deletion_status() == 'pending'): ?>
            <form action="<?= create_url_with_attributes([ 'action' => 'abort_deletion', 'object' => 'settings', 'page' => $page]) ?>" method="post">
                <label for="recovery_notice_accepted">I accept the conditions of this action ... Delition will be aborted ...</label>
                <input type="checkbox" name="recovery_notice_accepted" id="recovery_notice_accepted">
            </form>
        <?php elseif($page == 'privacy'): ?>
            <form action="<?= create_url_with_attributes([ 'action' => 'privacy', 'object' => 'settings', 'page' => $page]) ?>" method="post">
                
                <label for="show_email_owner">Show my email to teh owner of a Project</label>
                <input type="checkbox" name="show_email_owner" id="show_email_owner">
                <label for="show_email_all">Show my email to all project-members</label>
                <input type="checkbox" name="show_email_all" id="show_email_all">
                <label for="hidden_mode">Hide "confidential" info by bluring it out by default ("no leak mode")</label>
                <input type="checkbox" name="hidden_mode" id="hidden_mode">
                <note for="hidden_mode">No 100% guarantee ...</note>

                <!-- <input type="submit" value="Save Settings">
            </form> -->
        <?php else: ?>
            <h3>It seems like this account has been deleted!</h3>
        <?php endif; ?>
    <?php elseif($page == 'info'): ?>
        </h3>Info Settings</h3>
        <form action="<?= create_url_with_attributes([]) ?>">

        </form>
    <?php else: ?>
        none (or <?= $page ?? '"NULL"' ?>)
    <?php endif; ?>

    <?php if(!$hasForm == false): ?>
                <input type="submit" value="Save Settings">
            </form>
    <?php endif; ?>
</div>
<br>
<div class="disclaimer wip-disclaimer">This is still WorkInProgress ... visit <a href="https://github.com/fabianternis/issuesboard">GitHub</a> for more info.</div>