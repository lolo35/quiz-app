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
  },
  {
    path: "/score",
    name: "score",
    component: () => import('../views/ScoreView.vue')
  },
  {
    /**Checks for finished quiz and asks for confirmation in case */
    path: "/check",
    name: "check",
    component: () => import("../views/CheckStartedQuiz.vue")
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
