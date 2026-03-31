<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link rel="stylesheet" href="{{ asset('css/tasks.css') }}">
</head>
<body>
<div class="container">
    <h1>Task Management App</h1>

    <div id="messageBox" class="message"></div>

    <div class="card">
        <h2>Create Task</h2>
        <form id="taskForm">
            <div class="grid">
                <div>
                    <label for="title">Title</label>
                    <input type="text" id="title" required>
                </div>
                <div>
                    <label for="due_date">Due Date</label>
                    <input type="date" id="due_date" required>
                </div>
                <div>
                    <label for="priority">Priority</label>
                    <select id="priority" required>
                        <option value="">Select priority</option>
                        <option value="low">low</option>
                        <option value="medium">medium</option>
                        <option value="high">high</option>
                    </select>
                </div>
            </div>
            <br>
            <button type="submit">Create Task</button>
        </form>
    </div>

    <div class="card">
        <h2>Filter Tasks</h2>
        <div class="grid">
            <div>
                <label for="statusFilter">Status</label>
                <select id="statusFilter">
                    <option value="">All</option>
                    <option value="pending">pending</option>
                    <option value="in_progress">in_progress</option>
                    <option value="done">done</option>
                </select>
            </div>
            <div class="align-end">
                <button type="button" onclick="loadTasks()">Apply Filter</button>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Tasks</h2>
        <div id="tasksContainer" class="empty">Loading tasks...</div>
    </div>

    <div class="card">
        <h2>Daily Report</h2>
        <div class="grid">
            <div>
                <label for="reportDate">Date</label>
                <input type="date" id="reportDate">
            </div>
            <div class="align-end">
                <button type="button" class="btn-secondary" onclick="loadReport()">Generate Report</button>
            </div>
        </div>
        <br>
        <pre id="reportOutput">No report loaded.</pre>
    </div>
</div>

<script src="{{ asset('js/tasks.js') }}"></script>
</body>
</html>
