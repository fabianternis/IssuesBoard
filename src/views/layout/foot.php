<?php
    $commitHash = getCommitId();
    $safeCommitHash = $commitHash ? htmlspecialchars((string)$commitHash, ENT_QUOTES, 'UTF-8') : null;
?>


<footer>
    <div>
        <?php if ($safeCommitHash): ?>
            <span class="commit-id">
                <a href="https://github.com/fabianternis/IssuesBoard/commit/<?= $safeCommitHash ?>">
                    <?= $safeCommitHash ?>
                </a>
            </span>
        <?php else: ?>
            <span class="commit-id error">UNKNOWN Commit (Git execution failed) </span>
        <?php endif; ?>
    </div>

    <img src="https://hackatime.hackclub.com/api/v1/badge/U0B8JTZDTKQ/fabianternis/IssuesBoard">
</footer>
</html>