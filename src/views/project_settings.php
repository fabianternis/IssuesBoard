<?php

$settings = $project->settings;

if (is_string($settings)) {
    $settings = json_decode($settings, true) ?? [];
} elseif (!is_array($settings)) {
    $settings = [];
}

$allSeeMembers = !empty($settings['all_see_members']);
$showMemberEmails = !empty($settings['show_member_emails']);
$types = $settings['types'] ?? '';

?>

<?php // considering a foreach() on this ... ?>


<form id="project-settings-form" action="<?= create_url_with_attributes(['action' => 'storeSettings', 'object' => 'project', 'id' => $project->id]) ?>" method="post">
    <!-- <label for="allow_all_to_see_users">Allow all projectmembers to see the memberslist</label>
    <input type="checkbox" name="allow_all_to_see_users" id="allow_all_to_see_users"> -->

    <label for="all_see_members">Allow all projectmembers to see the memberslist</label>
    <input type="checkbox" name="all_see_members" id="all_see_members" value="1" <?= $allSeeMembers ? 'checked' : '' ?>>

    <label for="show_member_emails">Show projectmember's Emails</label>
    <input type="checkbox" name="show_member_emails" id="show_member_emails" value="1" <?= $showMemberEmails ? 'checked' : '' ?>>

    <label for="types">Item Types (comma-seperated)</label>
    <!-- Considering to move the tpes to a column directly -->
    <textarea name="types" id="types"><?= htmlspecialchars($types, ENT_QUOTES, 'UTF-8') ?></textarea>

    <input type="submit" value="Save Settings">
</form>
