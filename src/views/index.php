    <h1>Welcome to IssuesBoard</h1>
    <h2>A Project by <a href="https://github.com/fabianternis">Fabian Ternis</a> for <a href="https://macondo.hackclub.com">Macondo</a>(A HackClub Program)</h2>
    <?php if(!$auth->check()) {include __DIR__ . '/forms/signup.php'; include __DIR__ . '/forms/login.php'; }; // show forms when not authenticated ?>
