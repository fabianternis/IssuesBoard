const toastContainer = document.getElementById('toasts');

function showToast(content, type = 'info', duration = '3600', dismissable = true) {
    if (!toastContainer) {
        console.error('no toast-container found');
        return null;
    }
    
    const id = `toast-${Date.now()}-${Math.floor(Math.random() * 10000)}`;
    const toastElement = document.createElement('div');

    toastElement.id = id;
    toastElement.classList.add('toast', `toast-${type}`);
    toastElement.setAttribute('role', 'alert');

    const messageNode = document.createElement('span');
    messageNode.classList.add('toast-message');

    Node.textContent = content; 
    toastElement.appendChild(messageNode);
    
    if (dismissable) {
        const closeButton = document.createElement('button');
        closeButton.classList.add('toast-close');
        closeButton.innerHTML = '&times;';
        closeButton.setAttribute('aria-label', 'Close');
        closeButton.onclick = () => dismissToast(id);
        toastElement.appendChild(closeButton);
    }

    toastContainer.appendChild(toastElement);

    if (duration > 0) {
        setTimeout(() => dismissToast(id), duration);
    }

    return id;
}

function dismissToast(id) {
    const toastElement = document.getElementById(id);
    if (toastElement) {
        toastElement.remove();
    }
}