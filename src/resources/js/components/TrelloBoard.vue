<template>
  <div class="trello-board" style="padding: 20px">
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
            <div style="background: white; border-radius: 6px; padding: 12px; margin-bottom: 10px; box-shadow: 0 1px 3px rgba(9,30,66,.15); transition: transform 0.2s, box-shadow 0.2s;"
                 onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 6px rgba(9,30,66,0.1)';"
                 onmouseout="this.style.transform=''; this.style.boxShadow='0 1px 3px rgba(9,30,66,.15)';">
              <div style="font-weight: 600; margin-bottom: 6px; color: #495057;">{{ task.title }}</div>
              <div style="font-size: 0.9em; color: #6c757d; margin-bottom: 8px;">{{ task.content }}</div>
              <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                <span style="font-size: 0.8em; color: #868e96;">{{ formatDate(task.created_at) }}</span>
                <button @click="deleteTask(task.id)"
                        style="background: none; border: none; color: #adb5bd; cursor: pointer; padding: 4px 8px; border-radius: 3px; transition: color 0.2s;"
                        onmouseover="this.style.color='#dc3545';"
                        onmouseout="this.style.color='#adb5bd';">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
          </template>
        </draggable>
        <div v-if="column.tasks.length === 0"
             style="text-align: center; padding: 20px; color: #adb5bd; font-size: 0.9em; border: 1px dashed #dee2e6; border-radius: 4px; margin-top: 10px;">
          No tasks yet
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
        const response = await fetch(`/kanban/${task.id}/status`, {
          method: 'PATCH',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
          },
          body: JSON.stringify({ status: newColumnId })
        })

        if (!response.ok) {
          console.error('status update failed:', response.statusText)
        }
      } catch (error) {
        console.error('Error updating task status:', error)
      }
    }

    const deleteTask = async (id) => {
      try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        const response = await fetch(`/kanban/${id}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': token
          }
        })
        if (response.ok) {
          for (const column of columns.value) {
            column.tasks = column.tasks.filter(t => t.id !== id)
          }
        }
      } catch (error) {
        console.error('Error deleting task:', error)
      }
    }

    return {
      columns,
      columnBorderColors,
      columnTextColors,
      columnBadgeColors,
      onDragEnd,
      deleteTask,
      formatDate
    }
  }
})
</script>
