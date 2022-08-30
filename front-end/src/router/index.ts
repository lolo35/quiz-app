import { createRouter, createWebHashHistory, RouteRecordRaw } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: "/quiz",
    name: "quiz",
    component: () => import('../views/QuizView.vue')
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
