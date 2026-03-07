<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h2 class="card-title mb-4 text-center">Create Event</h2>

            <form @submit.prevent="submit">
              <!-- Title -->
              <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input
                  id="title"
                  type="text"
                  class="form-control"
                  v-model="title"
                  required
                />
              </div>

              <!-- Description -->
              <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea
                  id="description"
                  class="form-control"
                  v-model="description"
                  rows="3"
                ></textarea>
              </div>

              <!-- Date / Time -->
              <div class="mb-3">
                <label for="occurrence" class="form-label">Date / Time</label>
                <input
                  id="occurrence"
                  type="datetime-local"
                  class="form-control"
                  v-model="occurrence"
                  required
                />
              </div>

              <!-- Buttons -->
              <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" @click="cancel">Cancel</button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import api from "../api/api";

const router = useRouter();

const title = ref("");
const description = ref("");
const occurrence = ref("");

// Submit form
const submit = async () => {
  try {
    await api.post("/events", {
      title: title.value,
      description: description.value,
      occurrence: occurrence.value.replace("T", " "),
    });
    router.push("/events");
  } catch (e) {
    alert("Save failed: " + e.response?.data?.message || "");
    console.error(e);
  }
};

// Cancel and go back
const cancel = () => {
  router.push("/events");
};
</script>