<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">

        <div class="card shadow-sm">
          <div class="card-header text-center">
            <h4>Password Reset</h4>
          </div>

          <div class="card-body">

            <div v-if="success" class="alert alert-success">
              Password reset email sent!
            </div>

            <form @submit.prevent="submit">
              <div class="mb-3">
                <label class="form-label">Email address</label>
                <input
                  type="email"
                  v-model="email"
                  class="form-control"
                  required
                />
              </div>

              <button class="btn btn-primary w-100" :disabled="loading">
                <span v-if="loading">Sending...</span>
                <span v-else>Send Reset Link</span>
              </button>
            </form>

          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import api from "../api/api"; 

export default {
  data() {
    return {
      email: "",
      loading: false,
      success: false,
    };
  },
  methods: {
    async submit() {
      this.loading = true;
      this.success = false;

      try {
        await api.post("http://127.0.0.1:8000/api/forgot-password", {
            email: this.email,
        });

        this.success = true;
      } catch (err) {
        console.error(err);
      }

      this.loading = false;
    },
  },
};
</script>
