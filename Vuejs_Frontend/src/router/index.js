import { createRouter, createWebHistory } from "vue-router"

import Login from "../views/Login.vue"
import Events from "../views/Events.vue"
import CreateEvent from "@/views/CreateEvent.vue"
import ForgotPassword from "@/views/ForgotPassword.vue"
import ResetPassword from "@/views/ResetPassword.vue"
import Chat from "@/views/Chat.vue"
import AgentDashboard from "@/views/AgentDashboard.vue"

const routes = [
  { path: "/", component: Login },

  { path: "/forgot-password", component: ForgotPassword },

  {
    path: "/reset-password",
    name: "reset-password",
    component: ResetPassword,
    props: route => ({
      token: route.query.token,
      email: route.query.email
    })
  },

  { path: "/events", component: Events, meta: { requiresAuth: true } },
  { path: "/events/create", component: CreateEvent, meta: { requiresAuth: true } },
  { path: "/chat", component: Chat, meta: { requiresAuth: true } },

  
  { path: "/agent-dashboard", component: AgentDashboard, meta: { requiresAuth: true } }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from) => {
  const token = localStorage.getItem("token")
  const role = localStorage.getItem("role")


  if (to.meta.requiresAuth && !token) {
    return "/"
  }


  if (to.path === "/agent-dashboard" && role !== "agent") {
    return "/"
  }


  return true
})

export default router
