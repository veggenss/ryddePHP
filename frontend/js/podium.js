const podiumDiv = document.getElementById('podium-div');
const userDetail = document.getElementById('user-task-details');

async function getMemberTasks(){
    fetch('/utvikling/ryddePHP/backend/Handlers/TaskHandler.php', {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: 'getMemberTasks' })
    })
    .then(res => res.json())
    .then(data => {
        podiumDiv.innerHTML = '';
        console.log(data);
        data.taskData.forEach(user => {
            renderPodium(user);
        })
    })
}

function renderPodium(user){
    //tell antall oppgaver gjort og total poeng
    let totalCompTasks = 0;
    user.tasks.forEach(task => { totalCompTasks++ });

    const userPlace = document.createElement('div');
    userPlace.classList.add('user-placement');
    userPlace.innerHTML = `
        <p class="user-username">${user.username}</p>
        <p class="user-secondary">Fullførte Oppgaver: <span id="userTotalTasks">${totalCompTasks}</span></p>
        <p class="user-secondary">Total Poeng: <span id="userPoints">${user.sumPoints}</span></p>`;

    const userDetail = document.createElement('div');
    userDetail.classList.add('user-task-details');

    const detailHeader = document.createElement('h3');
    detailHeader.classList.add('user-detail-header');
    detailHeader.textContent = 'Oppgaver';

    userDetail.appendChild(detailHeader);

    if(user.tasks.length === 0){
        userDetail.innerHTML = `
            <div class="task-item">
                <h3>Ingen fullførte oppgaver</h3>
            </div>`;
    }
    else{
        user.tasks.forEach(task => {
            renderTaskDetails(task, userDetail);
        })
    }

    userPlace.appendChild(userDetail);
    podiumDiv.appendChild(userPlace);
}

//nesten kopi av funksjonen i displayTasks
function renderTaskDetails(task, userDetail){

    //regex for å rydde opp timestamp
    let compTimestamp = null;
    if(task.completed_date){
       compTimestamp = task.completed_date.replace(/T.*$/, '')
    }

    const wrapper = document.createElement('div');
    wrapper.classList.add('task-item');
    wrapper.dataset.id = task.id;

    //venstre-div
    const taskLeft = document.createElement('div');
    taskLeft.classList.add('task-left');

    const taskName = document.createElement('h3')
    taskName.classList.add('task-name');
    taskName.textContent = task.taskName;

    const taskDetails = document.createElement('div')
    taskDetails.classList.add('task-details');
    taskDetails.innerHTML = `
    <span class="task-label">Opprettet:</span>
    <span class="task-value">${compTimestamp}</span>
    <span class="task-label">Av:</span>
    <span class="task-value">${task.author_name}</span>
    <span class="task-label">Vanskelighet:</span>
    <span class="task-value diff-${task.taskDifficulty}">${task.taskDifficulty}</span>`;

    const taskDescriptor = document.createElement('div');
    taskDescriptor.classList.add('task-descriptor');

    const taskDescriptor_h3 = document.createElement('h3');
    taskDescriptor_h3.textContent = "Beskrivelse";
    taskDescriptor.appendChild(taskDescriptor_h3);

    const taskDescription = document.createElement('p');
    taskDescription.textContent = task.taskDescription;

    //høyre-div
    const taskRight = document.createElement('div');
    taskRight.classList.add('task-right');

    const taskStatus = document.createElement('h2');
    taskStatus.classList.add('task-status');
    taskStatus.classList.add('completed');
    taskStatus.textContent = "Fullført";

    const categoryDiv = document.createElement('div');
    categoryDiv.classList.add('category-div');
    categoryDiv.innerHTML = `
        <span class="category task-label">Kategori:</span>
        <span class="category task-value">${task.category}</span>
        `;

    taskRight.appendChild(categoryDiv);
    taskRight.appendChild(taskStatus); //nester her sånn at task knappene havner under status indikatoren

    //nesting
    wrapper.appendChild(taskLeft);
    wrapper.appendChild(taskRight);

    taskLeft.appendChild(taskName);
    taskLeft.appendChild(taskDetails);
    taskLeft.appendChild(taskDescriptor);

    taskDescriptor.appendChild(taskDescription);

    userDetail.appendChild(wrapper);
}

document.addEventListener('click', (e) => {
    const userDiv = e.target.closest('.user-placement');
    if (!userDiv) return;

    const taskDiv = userDiv.querySelector('.user-task-details');
    if (!taskDiv) return;

    taskDiv.style.display = taskDiv.style.display === 'block' ? 'none' : 'block';
})

window.onload = getMemberTasks();