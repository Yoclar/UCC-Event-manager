<script setup>
import { ref } from "vue"
import { useRouter } from "vue-router"
import api from "../api/api"

// reaktív változók
const email = ref("")
const password = ref("")
const error = ref("")

const router = useRouter()

const login = async () => {
    try {
        const response = await api.post("/login", {
            email: email.value,
            password: password.value
        })

        // token mentése
        localStorage.setItem("token", response.data.token)

        // redirect
        router.push("/events")

    } catch (e) {
        // hibakezelés
        error.value = e.response?.data?.message || "Login failed"
    }
}

const forgot_password = () => {
    router.push("/forgot-password")
}


</script>

<template>
  <div class="container mt-5" style="max-width: 400px;">
    <h1 class="mb-4 text-center">Login</h1>

    <div class="mb-3">
      <label for="email" class="form-label">Email address</label>
      <input type="email" id="email" class="form-control" v-model="email" />
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" id="password" class="form-control" v-model="password" />
    </div>

    <div class="mb-3 text-end">
          <button class="btn btn-info" @click="forgot_password">Forgot password?</button>
    </div>

    <button class="btn btn-primary w-100" @click="login">Login</button>

    <p v-if="error" class="text-danger mt-3">{{ error }}</p>
  </div>
</template>