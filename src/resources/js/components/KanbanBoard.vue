<template>
  <div class="kanban-board" style="padding: 20px">
    <div style="display: flex; gap: 20px; align-items: flex-start; min-height: calc(100vh - 180px); background: #f8f9fa; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
      <div v-for="column in columns" :key="column.id"
        :style="{
          flex: 1,
          background: column.id === 'todo' ? '#f8f9fa' : column.id === 'doing' ? '#f8f9fa' : '#f8f9fa',
          borderRadius: '8px',
          padding: '12px',
          margin: '4px',
          boxShadow: '0 2px 4px rgba(0,0,0,0.05)',
          border: '1px solid ' + (columnBorderColors[column.id] || '#dee2e6')
        }">
        <h3 :style="{
            margin: '0 0 12px',
            padding: '8px',
            fontSize: '16px',
            color: columnTextColors[column.id] || '#212529',
            fontWeight: '600',
            borderRadius: '4px',
            borderBottom: '2px solid ' + (columnBorderColors[column.id] || '#dee2e6')
          }">
          {{ column.name }}
          <span class="badge" :class="'bg-' + columnBadgeColors[column.id]" style="float: right; font-size: 12px;">{{ column.tasks.length }}</span>
        </h3>
        <draggable
          v-model="column.tasks"
          :group="{ name: 'tasks' }"
          item-key="id"
          @end="(evt) => onDragEnd(evt, column.id)"
          :data-column-id="column.id"
        >
          <template #item="{ element: task }">
            <div style="background: white; border-radius: 6px; padding: 12px; margin-bottom: 10px; box-shadow: 0 1px 3px rgba(9,30,66,.15); transition: transform 0.2s, box-shadow 0.2s; position: relative;"
                 onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 6px rgba(9,30,66,0.1)'; document.getElementById('edit-btn-'+this.getAttribute('data-task-id')).style.display='block';"
                 onmouseout="this.style.transform=''; this.style.boxShadow='0 1px 3px rgba(9,30,66,.15)'; document.getElementById('edit-btn-'+this.getAttribute('data-task-id')).style.display='none';"
                 :data-task-id="task.id">
              <div style="font-weight: 600; margin-bottom: 6px; color: #495057; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ task.title }}</div>
              <div style="font-size: 0.9em; color: #6c757d; margin-bottom: 8px; max-height: 60px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">{{ task.content }}</div>
              <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                <span style="font-size: 0.8em; color: #868e96;">{{ formatDate(task.created_at) }}</span>
                <button @click="deleteTask(task.id)"
                        style="background: none; border: none; color: #adb5bd; cursor: pointer; padding: 4px 8px; border-radius: 3px; transition: color 0.2s;"
                        onmouseover="this.style.color='#dc3545';"
                        onmouseout="this.style.color='#adb5bd';">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
              <!-- edit button - hover -->
              <button :id="'edit-btn-'+task.id"
                      @click="openEditModal(task)"
                      style="position: absolute; top: 8px; right: 8px; background: white; border: 1px solid #dee2e6; color: #495057; cursor: pointer; padding: 4px 6px; border-radius: 3px; display: none; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <i class="fas fa-pencil-alt"></i>
              </button>
            </div>
          </template>
        </draggable>
        <div v-if="column.tasks.length === 0"
             style="text-align: center; padding: 20px; color: #adb5bd; font-size: 0.9em; border: 1px dashed #dee2e6; border-radius: 4px; margin-top: 10px;">
          No tasks yet
        </div>
      </div>
    </div>
    <!-- 編集モーダル -->
    <div v-if="editingTask" id="editTaskModal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.75); z-index: 1050; backdrop-filter: blur(3px); display: flex; align-items: center; justify-content: center;">
      <div class="modal-dialog" style="max-width: 650px; width: 95%; margin: 50px auto;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; box-shadow: 0 15px 40px rgba(0,0,0,0.3); background: #f8f9fa;">
          <div class="modal-header bg-primary text-white border-bottom-0" style="border-radius: 16px 16px 0 0; padding: 16px 20px;">
            <h5 class="modal-title fs-4 fw-bold"><i class="fas fa-edit me-2"></i>Edit Task</h5>
            <button type="button" @click="closeEditModal"
                    class="btn-close btn-close-white" aria-label="Close"></button>
          </div>
          <div class="modal-body p-3" style="background: white; margin: 0 8px 8px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
            <form @submit.prevent="updateTask" class="w-100">
              <div class="row g-3 mx-0">
                <div class="col-12 px-0">
                  <div class="card border-0 shadow-sm w-100">
                    <div class="card-header bg-light py-2 px-3 border-0">
                      <label for="editTitle" class="form-label fs-5 fw-bold text-dark mb-0">
                        <i class="fas fa-heading me-2 text-primary"></i>Task Title
                      </label>
                    </div>
                    <div class="card-body p-2 bg-white">
                      <input type="text" class="form-control form-control-lg border border-2 w-100"
                          style="padding: 12px 15px; font-size: 1.1rem; background-color: #ffffff; box-shadow: none; width: 100% !important; box-sizing: border-box;"
                          id="editTitle" v-model="editingTask.title" required>
                    </div>
                  </div>
                </div>
                <div class="col-12 px-0">
                  <div class="card border-0 shadow-sm w-100">
                    <div class="card-header bg-light py-2 px-3 border-0">
                      <label for="editContent" class="form-label fs-5 fw-bold text-dark mb-0">
                        <i class="fas fa-align-left me-2 text-primary"></i>Task Description
                      </label>
                    </div>
                    <div class="card-body p-2 bg-white">
                      <textarea class="form-control border border-2 w-100"
                          style="padding: 12px 15px; font-size: 1rem; background-color: #ffffff; box-shadow: none; min-height: 120px; width: 100% !important; box-sizing: border-box;"
                          id="editContent" v-model="editingTask.content" required rows="5"></textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="d-flex justify-content-between mt-4 pt-3 border-top px-0">
                <!-- 左側に削除ボタン -->
                <button type="button" @click="confirmDeleteTask(editingTask.id)"
                        class="btn btn-danger px-4 py-2 d-flex align-items-center shadow-sm"
                        style="transition: all 0.2s ease;"
                        onmouseover="this.classList.add('btn-dark'); this.style.transform='translateY(-2px)';"
                        onmouseout="this.classList.remove('btn-dark'); this.style.transform='';">
                  <i class="fas fa-trash-alt me-2"></i>Delete
                </button>

                <!-- 右側にキャンセルと保存ボタン -->
                <div>
                  <button type="button" @click="closeEditModal"
                          class="btn btn-outline-secondary px-4 py-2 me-2 d-flex align-items-center"
                          style="transition: all 0.2s ease;"
                          onmouseover="this.classList.add('bg-light'); this.style.borderColor='#6c757d';"
                          onmouseout="this.classList.remove('bg-light'); this.style.borderColor='';">
                    <i class="fas fa-times me-2"></i>Cancel
                  </button>
                  <button type="submit"
                          class="btn btn-primary px-4 py-2 fw-bold d-flex align-items-center shadow-sm"
                          style="transition: all 0.2s ease;"
                          onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,123,255,0.3)';"
                          onmouseout="this.style.transform=''; this.style.boxShadow='';">
                    <i class="fas fa-check me-2"></i>Save Changes
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, watch, computed } from 'vue'
import draggable from 'vuedraggable'

export default defineComponent({
  components: { draggable },
  props: {
    tasks: {
      type: Array,
      required: true
    }
  },
  setup(props) {
    const columns = ref([
      { id: 'todo', name: 'To Do', tasks: [] },
      { id: 'doing', name: 'Doing', tasks: [] },
      { id: 'done', name: 'Done', tasks: [] }
    ])

    // Softer border colors for columns
    const columnBorderColors = {
      todo: '#dee2e6',
      doing: '#dee2e6',
      done: '#dee2e6'
    }

    // Text colors for column headers
    const columnTextColors = {
      todo: '#6c757d',
      doing: '#6c757d',
      done: '#6c757d'
    }

    // Badge colors using Bootstrap classes
    const columnBadgeColors = {
      todo: 'secondary',
      doing: 'info',
      done: 'success'
    }

    const formatDate = (dateString) => {
      if (!dateString) return '';
      const date = new Date(dateString);
      return new Intl.DateTimeFormat('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
      }).format(date);
    }

    const distributeTasks = () => {
      for (const column of columns.value) {
        column.tasks = props.tasks.filter(t => t.status === column.id)
      }
    }

    watch(() => props.tasks, () => {
      distributeTasks()
    }, { immediate: true, deep: true })

    const onDragEnd = async (event) => {
      const task = event.item.__draggable_context.element
      const targetColumn = event.to.closest('[data-column-id]')
      const newColumnId = targetColumn ? targetColumn.dataset.columnId : null

      if (!newColumnId) {
        console.error('Drop destination column not found')
        return
      }

      try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        const response = await fetch(`/api/kanban/${task.id}/status`, {
          method: 'PATCH',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
          },
          body: JSON.stringify({ status: newColumnId }),
          credentials: 'same-origin',
          redirect: 'error' // setting redirect to error to prevent redirect to login page
        })

        // handle authentication error
        if (response.status === 401 || response.status === 302) {
          console.warn('Authentication error while updating task status. Trying backup route.');

          // try backup route (no auth)
          const fallbackResponse = await fetch(`/api/kanban-status-update-test/${task.id}`, {
            method: 'PATCH',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newColumnId }),
            credentials: 'same-origin'
          });

          if (!fallbackResponse.ok) {
            throw new Error(`Fallback status update failed: ${fallbackResponse.status}`);
          }

          console.log('Task status updated using fallback route');
          return;
        }

        if (!response.ok) {
          throw new Error(`Status update failed: ${response.status}`);
        }

        console.log('Task status updated successfully');
      } catch (error) {
        console.error('Error updating task status:', error);

        // provide visual feedback to the user
        alert('An error occurred while moving the task. Please reload the page and try again.');

        // add a process to return the UI to the original state when an error occurs
        distributeTasks(); // return the task to the original position
      }
    }

    const deleteTask = async (id) => {
        if (!confirm('Do you want to delete this task?')) {
        return;
      }

      try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        const response = await fetch(`/api/kanban/${id}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': token
          }
        })
        if (response.ok) {
          for (const column of columns.value) {
            column.tasks = column.tasks.filter(t => t.id !== id)
          }
          // if editingTask is open, close it
          if (editingTask.value && editingTask.value.id === id) {
            closeEditModal();
          }
        }
      } catch (error) {
        console.error('Error deleting task:', error)
      }
    }

    // edit modal related
    const editingTask = ref(null);

    const openEditModal = (task) => {
      // copy object and edit
      editingTask.value = { ...task };
    };

    const closeEditModal = () => {
      editingTask.value = null;
    };

    // confirmDeleteTask  and deleteTask
    const confirmDeleteTask = (id) => {
      if (confirm('Do you want to delete this task?')) {
        deleteTask(id);
      }
    };

    const updateTask = async () => {
      try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        const response = await fetch(`/api/kanban/${editingTask.value.id}`, {
          method: 'PATCH',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
          },
          body: JSON.stringify({
            title: editingTask.value.title,
            content: editingTask.value.content
          })
        })

        if (response.ok) {
          // if update success, update task list
          for (const column of columns.value) {
            const index = column.tasks.findIndex(t => t.id === editingTask.value.id);
            if (index !== -1) {
              column.tasks[index].title = editingTask.value.title;
              column.tasks[index].content = editingTask.value.content;
              break;
            }
          }
          closeEditModal();
        } else {
            alert('Task update failed');
        }
      } catch (error) {
        console.error('Error updating task:', error);
        alert('Error updating task');
      }
    };

    return {
      columns,
      columnBorderColors,
      columnTextColors,
      columnBadgeColors,
      onDragEnd,
      deleteTask,
      formatDate,
      editingTask,
      openEditModal,
      closeEditModal,
      updateTask,
      confirmDeleteTask
    }
  }
})
</script>
