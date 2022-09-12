import { createRouter, createWebHashHistory, RouteRecordRaw } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/user_score',
    name: "Score",
    component: () => import('@/views/ScoreView.vue'),
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
