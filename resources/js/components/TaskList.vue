<script setup>
import { ref, computed } from 'vue';
import { notification } from 'ant-design-vue';

// Task data
const tasks = ref([
  { id: 1, title: 'Learn Vue.js', description: 'Read the Vue.js documentation', completed: false },
  { id: 2, title: 'Explore Ant Design', description: 'Check out Ant Design components', completed: false },
]);

// New task model
const newTask = ref({ title: '', description: '' });

// empty form check
const canAddTask = computed(() => newTask.value.title.trim() && newTask.value.description.trim());

// Add a new task
function addTask() {
  if (!canAddTask.value) {
    console.log("Cannot add task, conditions not met.");
    return;
  }
  const nextId = tasks.value.length ? Math.max(...tasks.value.map(t => t.id)) + 1 : 1;
  const newTaskToAdd = { ...newTask.value, id: nextId, completed: false };
  tasks.value.push(newTaskToAdd);
  openNotification('New Task Added');

  newTask.value = { title: '', description: '' };
}

// Mark the completion status of a task
function toggleComplete(taskId) {
  const task = tasks.value.find(t => t.id === taskId);
  if (task) {
    task.completed = !task.completed;
    openNotification('Task Marked Complete');
  }
}

// Delete a task
function deleteTask(taskId) {
  tasks.value = tasks.value.filter(t => t.id !== taskId);
  openNotification('Task Deleted');
}

// Toaster
const openNotification = (message) => {
  notification.open({
    message: `${message}`,
    duration: 1
  });
}
</script>

<template>
  <a-layout class="layout">
    <a-row type="flex" justify="center" align="middle" class="row">
      <a-col :span="24" class="column">
        <h2 class="title">Task Manager</h2>
        <a-form @submit.prevent="addTask" class="task-form">
          <a-form-item>
            <a-input v-model:value="newTask.title" placeholder="Enter Task Title" />
          </a-form-item>
          <a-form-item>
            <a-textarea v-model:value="newTask.description" placeholder="Enter Task Description" />
          </a-form-item>
          <a-form-item>
              <a-button type="primary" htmlType="submit" :disabled="!canAddTask">Add Task</a-button>
          </a-form-item>
        </a-form>
        <a-list
          item-layout="horizontal"
          :dataSource="tasks"
          class="task-list"
        >
          <a-list-item v-for="task in tasks" :key="task.id">
            <a-list-item-meta :title="task.title" :description="task.description" />
            <a-space>
              <a-button type="default" @click="toggleComplete(task.id)" :disabled="task.completed">Mark Complete</a-button>
              <a-button type="danger" @click="deleteTask(task.id)">Delete</a-button>
            </a-space>
          </a-list-item>
        </a-list>
      </a-col>
    </a-row>
  </a-layout>
</template>

<style scoped>
.layout {
  padding: 40px;
  background: #f0f2f5;
}

.row {
  margin-top: 20px;
}

.column {
  background: #ffffff;
  padding: 24px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.title {
  text-align: center;
  margin-bottom: 24px;
}

.task-form {
  margin-bottom: 24px;
}

.task-list {
  max-height: 400px;
  overflow-y: auto;
}

.a-button:hover {
  opacity: 0.8;
}
</style>
