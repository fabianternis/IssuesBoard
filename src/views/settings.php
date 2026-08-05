<?php

// die("hello"); // This was debug becuase $_page in bootstrap.php:37 broke head.php:6 ...

// $page = $_GET['page'];
// // no switch(), useing if() and co. ... instead
// $pages = ['none', 'danger_zone']; // for the nav ...

// // ToDo: add &page and /seettings to the action-attributes ... // HALF DONE ...
// ?\>

$_page = $_GET['page'] ?? 'none';
$pages = ['none', 'danger_zone', 'privacy', 'info'];

$user = Auth()->user();

$raw_settings = $user->settings ?? [];
if (is_string($raw_settings)) { $raw_settings = json_decode($raw_settings, true) ?? []; }

// $settings = $user->settings['privacy'] ?? [];
$settings = $raw_settings['privacy'] ?? [];

$show_email_owner = !empty($settings['show_email_owner']);
$show_email_all = !empty($settings['show_email_all']);
$hidden_mode = !empty($settings['hidden_mode']);

$hasForm = in_array($_page, ['danger_zone', 'privacy', 'info']);

$success_state = $_GET['success'] ?? null;
$error = $_GET['error'] ?? null;
?>

<div class="settings-nav">
    <?php foreach($pages as $page_from_list): ?>
        <a class="settings-nav-link <?= $_page === $page_from_list ? 'active' : '' ?>" href="<?= create_url_with_attributes(['page' => $_page_from_list, 'object' => 'settings', 'action' => 'show_']) ?>">
            <?= htmlspecialchars($page_from_list) ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="settings-page">
    <?php //$hasForm = true ?>
    <?php if($_page == 'none'): ?>
        <h3>No "none" here</h3>
    <?php elseif($_page == 'danger_zone'): ?>
        <?php if($user->get_deletion_status() == 'open'): ?>
            <form id="settings-form" action="<?= create_url_with_attributes(['action' => 'request_deletion', 'object' => 'settings']) ?>" method="post">
                <label for="deletion_notice_accepted">
                    I accept the conditions of this action... Account will be deleted in 28 days. Until then the deletion can be stopped at any time. Recovery after 28 days pass may not be possible. Deletion may never actually be fulfilled or data may remain somewhere. Opening a new account with the same username may not be possible.
                </label>
                <input type="checkbox" name="deletion_notice_accepted" id="deletion_notice_accepted">
                <!-- <?php if(isset($error)): ?>
                    <div class="error" data-error-message="<?= $error ?>"><?= $error ?></div>
                <?php endif; ?> -->
            <!-- </form> -->
        <?php elseif($user->get_deletion_status() == 'pending'): ?>
            <form id="settings-form" action="<?= create_url_with_attributes(['action' => 'abort_deletion', 'object' => 'settings', 'page' => $_page]) ?>" method="post">
                <label for="recovery_notice_accepted">
                    I accept the conditions of this action... Deletion will be aborted...
                </label>
                <input type="checkbox" name="recovery_notice_accepted" id="recovery_notice_accepted">
            <!-- </form> -->
        <?php else: ?>
            <h3>It seems like this account has been deleted!</h3>
            <h4>Contact the Platform Administrator for more information (if this is the "official deployment" it should be "IssuesBoard@fabian.ternis.dev")</h4>
        <?php endif; ?>
    <?php elseif($_page == 'privacy'): ?>
        <form id="settings-form" action="<?= create_url_with_attributes(['action' => 'privacy', 'object' => 'settings', 'page' => $_page]) ?>" method="post">
            <label for="show_email_owner">Show my email to the owner of a Project</label>
            <input type="checkbox" name="show_email_owner" id="show_email_owner" <?= $show_email_owner ? 'checked' : '' ?>>
            
            <label for="show_email_all">Show my email to all project-members</label>
            <input type="checkbox" name="show_email_all" id="show_email_all" <?= $show_email_all ? 'checked' : '' ?>>

            <label for="hidden_mode">Hide "confidential" info by blurring it out by default ("no leak mode")</label>
            <input type="checkbox" name="hidden_mode" id="hidden_mode" <?= $hidden_mode ? 'checked' : '' ?>>
            <small for="hidden_mode">No 100% guarantee...</small>
        <!-- </form> -->
    <?php elseif($_page == 'info'): ?>
        <h3>Info Settings</h3>
        <form id="settings-form" action="<?= create_url_with_attributes(['action' => 'info', 'object' => 'settings', 'page' => $_page]) ?>" method="post">
        <!-- </form> -->
    <?php elseif($_page == 'todo'): ?>
        <div><span>Stuff ToDo</span></div>
    <?php else: ?>
        none (or <?= htmlspecialchars($page) ?>)
    <?php endif; ?>
    <?php if(isset($error)): ?>
        <div class="error" data-error-message="<?= $error ?>"><?= $error ?></div>
    <?php endif; ?>
    <?php if($hasForm): ?>
        <input type="submit" form="settings-form" value="Save Settings">
        </form>
    <?php endif; ?>
</div>

<br>
<hr><br><br>

<div class="disclaimer wip-disclaimer">This is still WorkInProgress ... visit <a href="https://github.com/fabianternis/issuesboard">GitHub</a> for more info.</div>