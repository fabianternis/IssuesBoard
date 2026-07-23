<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= config('app.name') ?? 'IssuesBoard' ?> - <?= $page['name'] ?? '' ?></title>
    <link rel="stylesheet" href="app.css">
    <!-- <link rel="stylesheet" href="https://unpkg.com/mvp.css">  -->
</head>

<div id="toasts"></div>

<div class="toast-test-controls">
    <button onclick="showToast('SOme info', 'info')">
        Info
    </button>
    
    <button onclick="showToast('Some success', 'success')">
        Success
    </button>
    
    <button onclick="showToast('Something important', 'warning')">
        Warn
    </button>
    
    <button onclick="showToast('Something failed!', 'error')">
        Error
    </button>
    
    <button onclick="showToast('Will stay there a bit longer ...', 'info', 0, true)">
        Persistent
    </button>
    
    <button onclick="showToast('THis may not be dismissed ...', 'error', 5000, false)">
        Non-Dismissable
    </button>
</div>

<script src="toasts.js"></script>