const apiBase = '/api/tasks';

function showMessage(text, type = 'success') {
    const box = document.getElementById('messageBox');
    box.textContent = text;
    box.className = `message ${type}`;
    box.style.display = 'block';

    setTimeout(() => {
        box.style.display = 'none';
    }, 4000);
}

async function apiRequest(url, options = {}) {
    const response = await fetch(url, {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            ...(options.headers || {})
        },
        ...options
    });

    const data = await response.json();

    if (!response.ok) {
        let message = data.message || 'Something went wrong.';

        if (data.errors) {
            const firstKey = Object.keys(data.errors)[0];
            if (firstKey && data.errors[firstKey][0]) {
                message = data.errors[firstKey][0];
            }
        }

        throw new Error(message);
    }

    return data;
}

async function loadTasks() {
    try {
        const status = document.getElementById('statusFilter').value;
        const url = status ? `${apiBase}?status=${encodeURIComponent(status)}` : apiBase;
        const data = await apiRequest(url, { method: 'GET' });
        renderTasks(data.tasks || []);
    } catch (error) {
        showMessage(error.message, 'error');
    }
}

function renderTasks(tasks) {
    const container = document.getElementById('tasksContainer');

    if (!tasks.length) {
        container.innerHTML = `<div class="empty">No tasks found.</div>`;
        return;
    }

    let html = `
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Due Date</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    `;

    tasks.forEach(task => {
        const nextStatus = getNextStatus(task.status);

        html += `
            <tr>
                <td data-label="ID">${task.id}</td>
                <td data-label="Title">${task.title}</td>
                <td data-label="Due Date">${task.due_date}</td>
                <td data-label="Priority">
                    <span class="badge priority-${task.priority}">${task.priority}</span>
                </td>
                <td data-label="Status">
                    <span class="badge status-${task.status}">${task.status}</span>
                </td>
                <td data-label="Actions">
                    <div class="actions">
                        ${
                            nextStatus
                            ? `<button class="btn-success" onclick="updateStatus(${task.id}, '${nextStatus}')">Move to ${nextStatus}</button>`
                            : ''
                        }
                        <button
                            class="btn-danger"
                            onclick="deleteTask(${task.id})"
                            ${task.status !== 'done' ? 'disabled' : ''}
                        >
                            Delete
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });

    html += `</tbody></table>`;
    container.innerHTML = html;
}

function getNextStatus(currentStatus) {
    if (currentStatus === 'pending') return 'in_progress';
    if (currentStatus === 'in_progress') return 'done';
    return null;
}

async function updateStatus(id, status) {
    try {
        const data = await apiRequest(`${apiBase}/${id}/status`, {
            method: 'PATCH',
            body: JSON.stringify({ status })
        });

        showMessage(data.message || 'Task updated successfully.');
        loadTasks();
    } catch (error) {
        showMessage(error.message, 'error');
    }
}

async function deleteTask(id) {
    const confirmed = confirm('Are you sure you want to delete this task?');
    if (!confirmed) return;

    try {
        const data = await apiRequest(`${apiBase}/${id}`, {
            method: 'DELETE'
        });

        showMessage(data.message || 'Task deleted successfully.');
        loadTasks();
    } catch (error) {
        showMessage(error.message, 'error');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const taskForm = document.getElementById('taskForm');

    if (taskForm) {
        taskForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const payload = {
                title: document.getElementById('title').value,
                due_date: document.getElementById('due_date').value,
                priority: document.getElementById('priority').value
            };

            try {
                const data = await apiRequest(apiBase, {
                    method: 'POST',
                    body: JSON.stringify(payload)
                });

                showMessage(data.message || 'Task created successfully.');
                taskForm.reset();
                loadTasks();
            } catch (error) {
                showMessage(error.message, 'error');
            }
        });
    }

    loadTasks();
});

async function loadReport() {
    const date = document.getElementById('reportDate').value;

    if (!date) {
        showMessage('Please select a date for the report.', 'error');
        return;
    }

    try {
        const data = await apiRequest(`${apiBase}/report?date=${encodeURIComponent(date)}`, {
            method: 'GET'
        });

        document.getElementById('reportOutput').textContent = JSON.stringify(data, null, 2);
        showMessage('Report loaded successfully.');
    } catch (error) {
        showMessage(error.message, 'error');
    }
}
