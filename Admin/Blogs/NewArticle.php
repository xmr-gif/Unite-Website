<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Task Manager</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>

<body>
    <div class="flex min-h-screen bg-gray-50">
        <!-- Sidebar -->
        <div class="py-5 px-6 w-1/4 h-screen border-r border-gray-200">
            <img src="/placeholder.svg" class="w-20 mb-10" alt="Logo">

            <div class="space-y-2">
                <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                    <span class="flex items-center gap-2">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        Dashboard
                    </span>
                </div>
                <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                    <span class="flex items-center gap-2">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        Users
                    </span>
                </div>
                <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                    <span class="flex items-center gap-2">
                        <i data-lucide="lightbulb" class="w-4 h-4"></i>
                        Subjects
                    </span>
                </div>
                <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                    <span class="flex items-center gap-2">
                        <i data-lucide="book-open" class="w-4 h-4"></i>
                        Choices
                    </span>
                </div>
                <div class="px-4 py-2 bg-slate-200 rounded-md cursor-pointer">
                    <span class="flex items-center gap-2">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        Blogs
                    </span>
                </div>
            </div>

            <div class="absolute bottom-8 space-y-2 w-[calc(25%-48px)]">
                <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                    <span class="flex items-center gap-2">
                        <i data-lucide="settings" class="w-4 h-4"></i>
                        Settings
                    </span>
                </div>
                <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                    <span class="flex items-center gap-2">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Log Out
                    </span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="h-screen bg-gray-100 py-5 w-3/4 px-7 overflow-y-auto">
            <div>
                <div class="flex justify-end w-full mb-5">
                    <div class="border border-zinc-400 text-zinc-600 h-10 w-10 flex justify-center items-center rounded-xl cursor-pointer">
                        <i data-lucide="plus" class="h-4 w-4"></i>
                    </div>
                    <img src="/placeholder.svg" alt="Profile" class="h-10 w-10 ml-3 rounded-full">
                </div>

                <!-- Team Task Manager -->
                <div class="bg-white px-9 py-4 rounded-3xl h-[72vh] flex flex-col">
                    <div class="flex justify-between border-b border-zinc-200 pt-4 pb-5 mb-5">
                        <h2 class="text-2xl font-medium">Team Task Manager</h2>
                        <div class="flex gap-2">
                            <span class="bg-blue-500 text-white px-2 py-2 rounded-full text-xs font-bold">Tasks: <span id="taskCount">4</span></span>
                            <span class="bg-green-500 text-white px-2 py-2 rounded-full text-xs font-bold">Completed: <span id="completedCount">1</span></span>
                        </div>
                    </div>

                    <div class="mb-6">
                        <form id="taskForm" class="flex gap-2">
                            <input
                                id="newTaskInput"
                                placeholder="Add a new task..."
                                class="flex-1 border border-gray-300 rounded px-4 py-2"
                            />
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded flex items-center gap-2">
                                <i data-lucide="plus" class="h-4 w-4"></i>
                                Add Task
                            </button>
                        </form>
                    </div>

                    <div class="flex-1 overflow-y-auto">
                        <h3 class="font-semibold text-lg mb-2">Task List</h3>

                        <div id="taskList" class="space-y-3">
                            <!-- Tasks will be added here by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Initial task data
        const tasks = [
            { id: 1, title: 'Design team dashboard', status: 'In Progress', assignee: 'John Doe', priority: 'High' },
            { id: 2, title: 'Create user documentation', status: 'To Do', assignee: 'Sarah Smith', priority: 'Medium' },
            { id: 3, title: 'Fix navigation bug', status: 'Done', assignee: 'Mike Johnson', priority: 'High' },
            { id: 4, title: 'Update API endpoints', status: 'In Progress', assignee: 'Emma Wilson', priority: 'Low' },
        ];

        // Function to render tasks
        function renderTasks() {
            const taskList = document.getElementById('taskList');
            taskList.innerHTML = '';

            if (tasks.length === 0) {
                taskList.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        No tasks available. Add some tasks to get started!
                    </div>
                `;
                return;
            }

            tasks.forEach(task => {
                const taskElement = document.createElement('div');
                taskElement.className = 'bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex justify-between items-center';

                const priorityClass = getPriorityColor(task.priority);
                const statusClass = getStatusColor(task.status);

                taskElement.innerHTML = `
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <h4 class="font-medium ${task.status === 'Done' ? 'line-through text-gray-400' : ''}">
                                ${task.title}
                            </h4>
                            <span class="${statusClass} text-xs px-2 py-1 rounded-full">
                                ${task.status}
                            </span>
                            <span class="${priorityClass} text-xs px-2 py-1 rounded-full">
                                ${task.priority}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500">Assigned to: ${task.assignee}</p>
                    </div>

                    <div>
                        ${task.status !== 'Done' ? `
                            <button
                                class="bg-green-50 hover:bg-green-100 border border-green-200 text-green-700 text-sm px-3 py-1 rounded flex items-center gap-1"
                                onclick="completeTask(${task.id})"
                            >
                                <i data-lucide="check" class="h-4 w-4"></i>
                                Mark Done
                            </button>
                        ` : ''}
                    </div>
                `;

                taskList.appendChild(taskElement);
            });

            // Update counts
            document.getElementById('taskCount').textContent = tasks.length;
            document.getElementById('completedCount').textContent = tasks.filter(t => t.status === 'Done').length;

            // Initialize any new Lucide icons in the added tasks
            lucide.createIcons();
        }

        // Helper functions for colors
        function getPriorityColor(priority) {
            switch(priority) {
                case 'High': return 'bg-red-100 text-red-800';
                case 'Medium': return 'bg-yellow-100 text-yellow-800';
                case 'Low': return 'bg-green-100 text-green-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        }

        function getStatusColor(status) {
            switch(status) {
                case 'Done': return 'bg-green-100 text-green-800';
                case 'In Progress': return 'bg-blue-100 text-blue-800';
                case 'To Do': return 'bg-gray-100 text-gray-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        }

        // Function to complete a task
        function completeTask(id) {
            tasks.forEach(task => {
                if (task.id === id) {
                    task.status = 'Done';
                }
            });

            renderTasks();
            showToast('Task completed', 'Task has been marked as done');
        }

        // Form submit handler to add new task
        document.getElementById('taskForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const input = document.getElementById('newTaskInput');
            const value = input.value.trim();

            if (value === '') {
                showToast('Task cannot be empty', 'Please enter a task description', true);
                return;
            }

            const newTask = {
                id: tasks.length + 1,
                title: value,
                status: 'To Do',
                assignee: 'Unassigned',
                priority: 'Medium'
            };

            tasks.push(newTask);
            input.value = '';
            renderTasks();

            showToast('Task added', 'New task has been added to the list');
        });

        // Simple toast notification
        function showToast(title, message, isError = false) {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 px-6 py-4 rounded-lg shadow-lg ${isError ? 'bg-red-500' : 'bg-green-500'} text-white transition-opacity duration-500`;

            toast.innerHTML = `
                <h4 class="font-medium">${title}</h4>
                <p class="text-sm">${message}</p>
            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 500);
            }, 3000);
        }

        // Initial render
        renderTasks();
    </script>
</body>
</html>
