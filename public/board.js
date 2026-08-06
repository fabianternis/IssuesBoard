document.addEventListener('DOMContentLoaded', () => {
    const items = document.querySelectorAll('.item');
    const dropzones = document.querySelectorAll('.column-items');
    const timerContainer = document.getElementById('time-container');
    const timerDisplay = document.getElementById('time-display');
    // const boardData = document.getElementById('board-data');
    // const projectId = boardData ? boardData.dataset.projectId : null;
    const saveButton = document.getElementById('button-save');
    const saveButton2 = document.getElementById('button-save-2');
    // ToDo: add a querySelectorAll for '.save-button' ... would be way better
    const columns = document.querySelectorAll('.board-column');

    let countdownTimeout = null;
    let countdownInterval = null;

    function getProjectId() {
        const boardData = document.getElementById('board-data');
        return boardData ? boardData.dataset.projectId : null;
    }

    function syncCollapseState(itemElement, toggleInput) {
        if (toggleInput.checked) {
            itemElement.classList.add('collapsed');
        } else {
            itemElement.classList.remove('collapsed');
        }
    }

    items.forEach(item => {
        item.addEventListener('dragstart', handleDragStart);
        item.addEventListener('dragend', handleDragEnd);

        item.querySelectorAll('.item-inpt').forEach(input => {
            input.addEventListener('input', scheduleBatchSave);
            input.addEventListener('change', scheduleBatchSave);
        });


        const collapseToggle = item.querySelector('.collapse-toggle');
        if (collapseToggle) {
            syncCollapseState(item, collapseToggle);
            
            collapseToggle.addEventListener('change', (e) => {
                syncCollapseState(item, e.target);
            });

            collapseToggle.addEventListener('mousedown', (e) => e.stopPropagation());
        }
    });

    columns.forEach(column => {
        const bulkToggle = column.querySelector('input[name="collapse_all"]');
        if (!bulkToggle) return;

        bulkToggle.addEventListener('change', (e) => {
            const isChecked = e.target.checked;
            const columnItems = column.querySelectorAll('.item');

            columnItems.forEach(item => {
                const itemToggle = item.querySelector('.collapse-toggle');
                if (itemToggle) {
                    itemToggle.checked = isChecked;
                    syncCollapseState(item, itemToggle);
                }
            });
        });
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

        const updatedItems = dropzone.querySelectorAll('.item');
        updatedItems.forEach((el, index) => {
            const indexInput = el.querySelector('input[name="order_index"]');
            if (indexInput) indexInput.value = index;
        });

        scheduleBatchSave();
    }

//     function executeCountdownAndSubmit(targetElement) {
//         if (countdownInterval) {
//             clearInterval(countdownInterval);
//         }

//         let timeLeft = 5;

//         if (timerContainer && timerDisplay) {
//             timerContainer.classList.remove('none');
//             // timerDisplay.innerHTML = "Time Left: "+timeLeft;
//             timerDisplay.textContent = timeLeft;
//         }

//         countdownInterval = setInterval(() => {
//             timeLeft -= 1;
            
//             if (timerDisplay) {
//                 timerDisplay.textContent = timeLeft;
//             }

//             if (timeLeft <= 0) {
//                 clearInterval(countdownInterval);
                
//                 if (timerDisplay) {
//                     timerDisplay.parentElement.classList.add('none');
//                 }

//                 const formNode = targetElement.tagName === 'FORM' ? targetElement : targetElement.querySelector('form') || targetElement.closest('form');

//                 if (formNode) {
//                     formNode.submit();
//                 } else {
//                     console.error('Target resolution failed: No <form> element located in relation to the dropped node.');
//                 }
//             }
//         }, 1000);
//     }

    function scheduleBatchSave() {
        const projectId = getProjectId();

        if (!projectId) {
            console.error('Project ID missing ... (whyever / however this could have happened (should not be possible with the current state of teh PHP) ...')
            return;
        }

        if (countdownInterval) {
            clearInterval(countdownInterval);
        }
        if (countdownTimeout) {
            clearTimeout(countdownTimeout);
        }

        let timeLeft = 3; // bc. Why not

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
            }
        }, 1000);

        countdownTimeout = setTimeout(() => {
            if (timerContainer) {
                timerContainer.classList.add('none');
            }

            executeBatchSave();
        }, 3000);
    }

    function executeBatchSave(e) {
        // e.preventDefault(); // is that needed here ?? – will check taht soon (or never)

        if (e && typeof e.preventDefault === 'function') {
            e.preventDefault();
        }

        const projectId = getProjectId();
        const itemNodes = document.querySelectorAll('.item');
        const payload = {
            // project_id: projectId,
            items: []
        };

        itemNodes.forEach(node => {
            const itemId = node.dataset.itemId;
            if (!itemId) return;

            const itemData = { id: itemId };
            node.querySelectorAll('.item-inpt').forEach(input => { itemData[input.name] = input.value; });

            payload.items.push(itemData);
        })

        fetch('?action=batchUpdate&object=project&id=' + projectId , {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        }).then(response => {
            if (!response.ok) console.error('Response on BatchUpdate was not ok');
            return response.json();
        }).then(data => {
            console.log('Board Sync successful ...');
        }).catch(error => {
            console.error('Board Sync Error:' + error);
        });
    }

    saveButton.addEventListener('click', executeBatchSave);
    saveButton2.addEventListener('click', executeBatchSave);


    // function discardChangesAndGetUpdates() {
    function discardChangesAndGetLatest() {
        // ToDo: add countdown with "refresh now"-button that lets user abort ...
        window.location.reload();
        // ToDo: forgot it
    }

    // Source - https://stackoverflow.com/a/45090910
// Posted by Tomer Wolberg, modified by community. See post 'Timeline' for change history
// Retrieved 2026-08-02, License - CC BY-SA 4.0
});

// Credits:  https://stackoverflow.com/questions/45088541/how-do-i-upload-an-image-with-html-and-have-it-preview-on-the-site-with-js
function preview(file, image_id){
    if (file) {
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onloadend = function () {

            element = document.getElementById(image_id);
            element.src = reader.result;
            // why is it called "element" anyway?
            element.classList.add('shown');
        }
    }
}

// ToDo: make it possible to remove image form form ...
// ToDo: add upload-preview to ALL image-uploads ...


// DONE: btn that onClick saves batch .. (eventListener ...)
// ToDo: "auto order_index change" (dragging ...)
// ToDo: Add the "type-{$type}"-class also via JS instead of php...
// ToDo: just send the diff, to allow simultamiou(or however it is written) edits
// ToDo: CMD+S-listener to direct save (like the "save button" ...)
// DONE: collapse/expand items ... (better overview, UX and co. ...)
// ToDO: "order_index" seems not to get stored ...


// DONE: make auto-save fucntion again !!! :) – but took some time ...

// todo: add up/down-buttons for items (to change order_index quickly and bake UX better than it currently is ...)