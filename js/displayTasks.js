const taskList = document.getElementById('task-div');
let tasks = [];

//tegner elementene
function renderTasks(task, session){

    const wrapper = document.createElement('div');
    wrapper.classList.add('task-item');
    wrapper.dataset.id = task.id;

    //venstre-div
    const taskLeft = document.createElement('div');
    taskLeft.classList.add('task-left');

    const taskName = document.createElement('h3')
    taskName.classList.add('task-name');
    taskName.textContent = task.name;

    const taskDetails = document.createElement('div')
    taskDetails.classList.add('task-details');
    taskDetails.innerHTML = `
    <span class="task-label">Opprettet:</span>
    <span class="task-value">${task.added_date}</span>
    <span class="task-label">Av:</span>
    <span class="task-value">${task.author_name}</span>
    <span class="task-label">Vanskelighet:</span>
    <span class="task-value diff-${task.difficulty}">${task.difficulty}</span>`;

    const taskDescriptor = document.createElement('div');
    taskDescriptor.classList.add('task-descriptor');

    const taskDescriptor_h3 = document.createElement('h3');
    taskDescriptor_h3.textContent = "Beskrivelse";
    taskDescriptor.appendChild(taskDescriptor_h3);

    const taskDescription = document.createElement('p');
    taskDescription.textContent = task.description;

    //høyre-div
    const taskRight = document.createElement('div');
    taskRight.classList.add('task-right');

    const taskStatus = document.createElement('h2');
    taskStatus.classList.add('task-status');

    if (!(task.completorUser === null)){
        taskStatus.classList.add('completed');
        taskStatus.textContent = "Fullført";
    }
    else{
        taskStatus.classList.add('pending');
        taskStatus.textContent = "Pågående";
    };

    const categoryDiv = document.createElement('div');
    categoryDiv.classList.add('category-div');
    categoryDiv.innerHTML = `
        <span class="category task-label">Kategori:</span>
        <span class="category task-value">${task.category_name}</span>
        `;

    taskRight.appendChild(categoryDiv);
    taskRight.appendChild(taskStatus); //nester her sånn at task knappene havner under status indikatoren

    const rDetailDiv = document.createElement('div');
    rDetailDiv.classList.add('right-detail-div');
    if (session == task.author_id && task.completed_date === null) {
        const taskBtnCom = document.createElement('button');
        taskBtnCom.classList.add('task-btn');
        taskBtnCom.classList.add('complete');
        taskBtnCom.textContent = "Fullfør";
        taskBtnCom.value = task.id;

        const taskBtnDel = document.createElement('button');
        taskBtnDel.classList.add('task-btn');
        taskBtnDel.classList.add('delete');
        taskBtnDel.textContent = "Slett";
        taskBtnDel.value = task.id;

        rDetailDiv.appendChild(taskBtnDel);
        rDetailDiv.appendChild(taskBtnCom);
        taskRight.appendChild(rDetailDiv);
    }
    else if (task.completed_date === null) {
        rDetailDiv.classList.add('task-details');
        rDetailDiv.innerHTML = `<span class="task-label">Ingen har fullført oppgaven endå</span>`;

        taskRight.appendChild(rDetailDiv);
    }
    else{
        rDetailDiv.classList.add('task-details');
        rDetailDiv.innerHTML = `
            <span class="task-label">Fullført av:</span>
            <span class="task-value">${task.completorUser}</span>
            <span class="task-label">Dato:</span>
            <span class="task-value">${task.completed_date}</span>
            `;
        taskRight.appendChild(rDetailDiv);
    };

    //nesting
    wrapper.appendChild(taskLeft);
    wrapper.appendChild(taskRight);

    taskLeft.appendChild(taskName);
    taskLeft.appendChild(taskDetails);
    taskLeft.appendChild(taskDescriptor);

    taskDescriptor.appendChild(taskDescription);

    taskList.appendChild(wrapper);
};

function loadTasks(){
    fetch('tasks/TaskHandler.php', {
        method: "POST",
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'loadTasks' })
    })
    .then(res => res.json())
    .then(taskResponse => {
        taskList.innerHTML = '';
        tasks = taskResponse;
        console.log("taskData:", taskResponse);

        taskResponse.taskData.forEach(task => {
            renderTasks(task, taskResponse.session);
        });
    });
};

//Oppgave markering
document.addEventListener('click', async (e) => {
    // e.preventDefault();

    if(e.target.classList.contains("complete")){
        btn = e.target;

        const task = tasks.taskData.find(t => t.id == btn.value);
        if (!task) {
            alert("Error: Kunne ikke finne taskId");
            return;
        };

        completorUsername = prompt("Skriv inn brukernavnet til personen som fullførte oppgaven");
        if (!completorUsername) return;

        fetch("tasks/TaskHandler.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ action: 'completeTask', taskId: task.id, completorUsername: completorUsername })
        })
        .then(res => res.json())
        .then(response => {
            if(response.success){
                alert(response.message);
                loadTasks();
            }
            else{
                alert(response.message);
            };
        });
    };

    if(e.target.classList.contains("delete")){
        btn = e.target;

        const task = tasks.taskData.find(t => t.id == btn.value);

        if (!task) {
            alert("Error: Kunne ikke finne taskId");
            return;
        };

        if (!confirm("Er du sikker på at du vil slette oppgaven?")) return;

        const response = await fetch("tasks/TaskHandler.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ taskId: task.id, action: 'deleteTask' })
        })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                alert(response.message);
                loadTasks();
            }
            else {
                alert(response.message);
            };
        });
    };
});

window.onload = loadTasks();