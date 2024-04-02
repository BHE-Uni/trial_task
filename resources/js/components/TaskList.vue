<script setup>
import { ref, computed, onMounted } from 'vue'
import { notification } from 'ant-design-vue'
import axios from 'axios'

const API_URL = 'https://trail_task.test/api/tasks';

// Task data
const tasks = ref([]);

const fetchTasks = async () => {
  try {
    const response = await axios.get(API_URL);
    tasks.value = response.data.map(task => ({
      ...task,
      completed: !!task.completed
    }));
  } catch (error) {
    console.error("API Error:", error.response ? error.response.data : error);
    openNotification('Failed to fetch tasks');
  }
};

// New task model
const newTask = ref({ title: '', description: '' });

// empty form check
const canAddTask = computed(() => newTask.value.title.trim() && newTask.value.description.trim());

// Add a new task
const addTask = async () => {
  if (!canAddTask.value) {
    openNotification('Please complete the form before adding a task');
    return;
  }

  try {
    const response = await axios.post(API_URL, {
      title: newTask.value.title,
      description: newTask.value.description,
      completed: false,
    });

    console.log("API Response:", response.data);
    tasks.value.push(response.data);

    newTask.value.title = '';
    newTask.value.description = '';
    openNotification('New Task Added');
  } catch (error) {
    console.error("API Error:", error.response ? error.response.data : error);
    openNotification('Failed to add task');
  }
};

const startEditing = (task) => {
  task.isEditing = true;
  task.editTitle = task.title;
  task.editDescription = task.description;
};

const saveEdit = async (task) => {
  try {
    const updatedTask = await axios.patch(`${API_URL}/${task.id}`, {
      title: task.editTitle,
      description: task.editDescription,
      completed: task.completed
    });

    task.title = updatedTask.data.title;
    task.description = updatedTask.data.description;
    task.isEditing = false; // Exit editing mode
    openNotification(`Task "${task.title}" updated successfully.`);
  } catch (error) {
    console.error("API Error:", error.response ? error.response.data : error);
    openNotification('Failed to update task.');
  }
};


// Mark the completion status of a task
const toggleComplete = async (task) => {
  const updatedTask = { ...task, completed: !task.completed };
  try {
    await axios.patch(`${API_URL}/${task.id}`, {
      title: updatedTask.title,
      description: updatedTask.description,
      completed: updatedTask.completed
    });
    const index = tasks.value.findIndex(t => t.id === task.id);
    if (index !== -1) {
      tasks.value[index] = updatedTask;
    }
    openNotification(`Task "${task.title}" marked as ${updatedTask.completed ? 'completed' : 'incomplete'}.`);
  } catch (error) {
    console.error("API Error:", error.response ? error.response.data : error);
    openNotification('Failed to update task status');
  }
};

// Delete a task
const deleteTask = async (taskId) => {
  try {
    await axios.delete(`${API_URL}/${taskId}`);
    tasks.value = tasks.value.filter(task => task.id !== taskId);
    openNotification('Task Deleted Successfully');
  } catch (error) {
    console.error("API Error:", error.response ? error.response.data : error);
    openNotification('Failed to delete task');
  }
};

// Toaster
const openNotification = (message) => {
  notification.open({
    message: `${message}`,
    duration: 1
  });
}

onMounted(async () => {
  await fetchTasks();
  tasks.value.forEach(task => task.isEditing = false);
});
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
            <a-list-item-meta>
              <template v-if="!task.isEditing">
                <div :title="task.title" :description="task.description">
                  {{ task.title }} - {{ task.description }}
                </div>
                <a-space>
                  <a-button type="default" @click="toggleComplete(task)" :disabled="task.completed">Mark Complete</a-button>
                  <a-button type="primary" @click="startEditing(task)">Edit</a-button>
                  <a-button type="danger" @click="deleteTask(task.id)">Delete</a-button>
                </a-space>
              </template>
              <template v-else>
                <a-input v-model="task.editTitle" placeholder="Edit Task Title" />
                <a-textarea v-model="task.editDescription" placeholder="Edit Task Description" />
                <a-space>
                  <a-button type="primary" @click="saveEdit(task)">Save</a-button>
                  <a-button @click="task.isEditing = false">Cancel</a-button>
                </a-space>
              </template>
            </a-list-item-meta>
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
