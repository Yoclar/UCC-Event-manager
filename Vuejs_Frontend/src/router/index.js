import { createRouter, createWebHistory } from "vue-router"

import Login from "../views/Login.vue"
import Events from "../views/Events.vue"
import CreateEvent from "@/views/CreateEvent.vue"

const routes = [
  {
    path: "/",
    component: Login
  },
  {
    path: "/events",
    component: Events,
    meta: { requiresAuth: true }
  },
  {
      path: "/events/create",
      component: CreateEvent,
      meta: { requiresAuth: true }
  }
  
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from) => {
  const token = localStorage.getItem("token")

  if (to.meta.requiresAuth && !token) {
    return "/" // redirect loginra
  }

  // ha minden rendben → folytatás
  return true
})

export default router