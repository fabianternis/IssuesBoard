<?php

$page = $_GET['page'];
// no switch(), useing if() and co. ... instead
?>
<div class="settings-page">
    <?php if($page == 'some'): ?>
        some
    <?php elseif($page == 'other'): ?>
        other
    <?php elseif($page == 'whatever'): ?>
        whatever
    <?php else: ?>
        none (or <?= $page ?? '"NULL"' ?>)
    <?php endif; ?>
</div>
<br>
<div class="disclaimer wip-disclaimer">This is still WorkInProgress ... visit <a href="https://github.com/fabianternis/issuesboard">GitHub</a> for more info.</div>