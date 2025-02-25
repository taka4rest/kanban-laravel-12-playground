import { createApp } from 'vue'
import KanbanBoard from './components/KanbanBoard.vue'

const kanbanRoot = document.getElementById('kanban-root')
if (kanbanRoot) {
  const tasks = JSON.parse(kanbanRoot.dataset.tasks || '[]')

  const app = createApp(KanbanBoard, {
    tasks: tasks
  })

  app.mount('#kanban-root')
}
