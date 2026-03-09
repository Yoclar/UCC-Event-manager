<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">

        <div class="card shadow-sm">
          <div class="card-header text-center">
            <h4>Set New Password</h4>
          </div>

          <div class="card-body">

            <div v-if="success" class="alert alert-success">
              Password successfully reset! Redirecting to login...
            </div>

            <form @submit.prevent="submit">
              <div class="mb-3">
                <label class="form-label">New Password</label>
                <input
                  type="password"
                  v-model="password"
                  class="form-control"
                  required
                />
              </div>

              <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input
                  type="password"
                  v-model="password_confirmation"
                  class="form-control"
                  required
                />
              </div>

              <button class="btn btn-success w-100" :disabled="loading">
                <span v-if="loading">Saving...</span>
                <span v-else>Reset Password</span>
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
  props: ["token", "email"],

  data() {
    return {
      password: "",
      password_confirmation: "",
      loading: false,
      success: false,
    };
  },

  methods: {
    async submit() {
      this.loading = true;

      try {
        await api.post("http://127.0.0.1:8000/api/reset-password", {
          token: this.token,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation,
        });

        this.success = true;

        setTimeout(() => {
          this.$router.push("/");
        }, 2000);

      } catch (err) {
        console.error(err);
      }

      this.loading = false;
    },
  },
};
</script>
