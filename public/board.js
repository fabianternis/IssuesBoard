document.addEventListener('DOMContentLoaded', () => {
    const items = document.querySelectorAll('.item');
    const dropzones = document.querySelectorAll('.column-items');
    const timerContainer = document.getElementById('time-container');
    const timerDisplay = document.getElementById('time-display');
    
    let countdownInterval = null;

    items.forEach(item => {
        item.addEventListener('dragstart', handleDragStart);
        item.addEventListener('dragend', handleDragEnd);

        const form = item.querySelector('form');
        if (form) {
            form.addEventListener('input', (e) => {
                if (e.target.type !== 'submit') {
                    executeCountdownAndSubmit(form);
                }
            });
        }
    });

    dropzones.forEach(zone => {
        zone.addEventListener('dragover', handleDragOver);
        zone.addEventListener('dragenter', handleDragEnter);
        zone.addEventListener('dragleave', handleDragLeave);
        zone.addEventListener('drop', handleDrop);
    });

    function handleDragStart(e) {
        e.dataTransfer.setData('text/plain', e.target.id);
        e.dataTransfer.effectAllowed = 'move';
        setTimeout(() => e.target.classList.add('dragging'), 0);
    }

    function handleDragEnd(e) {
        e.target.classList.remove('dragging');
    }

    function handleDragOver(e) {
        e.preventDefault(); 
        e.dataTransfer.dropEffect = 'move';
    }

    function handleDragEnter(e) {
        e.preventDefault();
        e.currentTarget.classList.add('drag-over-active');
    }

    function handleDragLeave(e) {
        e.currentTarget.classList.remove('drag-over-active');
    }

    function handleDrop(e) {
        e.preventDefault();
        const dropzone = e.currentTarget;
        dropzone.classList.remove('drag-over-active');

        const itemId = e.dataTransfer.getData('text/plain');
        const draggedElement = document.getElementById(itemId);

        if (!draggedElement) return;

        dropzone.appendChild(draggedElement);

        const columnParent = dropzone.closest('.board-column');
        const match = columnParent.className.match(/column-(\w+)/);
        
        if (match && match[1]) {
            const newType = match[1];
            
            const selectElement = draggedElement.querySelector('select[name="type"]');
            if (selectElement) {
                selectElement.value = newType;
            }
            
            draggedElement.className = draggedElement.className.replace(/item-\w+/, `item-${newType}`);
        }

        executeCountdownAndSubmit(draggedElement);
    }

    function executeCountdownAndSubmit(targetElement) {
        if (countdownInterval) {
            clearInterval(countdownInterval);
        }

        let timeLeft = 5;

        if (timerContainer && timerDisplay) {
            timerContainer.classList.remove('none');
            // timerDisplay.innerHTML = "Time Left: "+timeLeft;
            timerDisplay.textContent = timeLeft;
        }

        countdownInterval = setInterval(() => {
            timeLeft -= 1;
            
            if (timerDisplay) {
                timerDisplay.textContent = timeLeft;
            }

            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                
                if (timerDisplay) {
                    timerDisplay.parentElement.classList.add('none');
                }

                const formNode = targetElement.tagName === 'FORM' ? targetElement : targetElement.querySelector('form') || targetElement.closest('form');

                if (formNode) {
                    formNode.submit();
                } else {
                    console.error('Target resolution failed: No <form> element located in relation to the dropped node.');
                }
            }
        }, 1000);
    }
});