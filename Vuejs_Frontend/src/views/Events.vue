<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import api from "../api/api";

const router = useRouter();

// reaktív változók
const events = ref([]);
const loading = ref(true);
const error = ref("");

// Inline edit állapot
const editId = ref(null);
const editTitle = ref("");
const editDescription = ref("");
const editOccurrence = ref("");

// Fetch events
const fetchEvents = async () => {
  loading.value = true;
  try {
    const response = await api.get("/events");
    events.value = response.data;
  } catch (e) {
    error.value = e.response?.data?.message || "Failed to load events";
  } finally {
    loading.value = false;
  }
};

// Logout
const logout = () => {
  localStorage.removeItem("token");
  localStorage.removeItem("role");
  router.push("/");
};

const goCreate = () => {
  router.push("/events/create");
};

const help = () => {
  router.push('/chat');
}

// Inline edit funkciók
const startEdit = (event) => {
  editId.value = event.id;
  editTitle.value = event.title;
  editDescription.value = event.description;
  editOccurrence.value = event.occurrence.slice(0,16);
};

const cancelEdit = () => {
  editId.value = null;
  editTitle.value = "";
  editDescription.value = "";
  editOccurrence.value = "";
};

const saveEdit = async (id) => {
  try {
    const occurrenceFormatted = editOccurrence.value.replace("T", " ");
    const payload = {
      title: editTitle.value,
      description: editDescription.value,
      occurrence: occurrenceFormatted
    };
    await api.put(`/events/${id}`, payload);

    const index = events.value.findIndex(e => e.id === id);
    events.value[index] = { ...events.value[index], ...payload };
    cancelEdit();
  } catch (e) {
    alert("Save failed: " + (e.response?.data?.message || ""));
    console.error(e);
  }
};

const deleteEvent = async (id) => {
  if (!confirm("Are you sure you want to delete this event?")) return;
  try {
    await api.delete(`/events/${id}`);
    events.value = events.value.filter(e => e.id !== id);
  } catch (e) {
    alert("Delete failed");
    console.error(e);
  }
};

onMounted(() => fetchEvents());
</script>

<template>
<div class="container my-4">
  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Events</h1>
    <div>
      <button class="btn btn-warning me-2" @click="help">Need help</button>
      <button class="btn btn-success me-2" @click="goCreate">New Event</button>
      <button class="btn btn-secondary" @click="logout">Logout</button>
    </div>
  </div>

  <p v-if="loading">Loading events...</p>
  <p v-if="error" class="text-danger">{{ error }}</p>

  <div v-if="!loading && events.length" class="row g-3">
    <div v-for="event in events" :key="event.id" class="col-lg-3 col-md-4 col-sm-6">
      <div class="card h-100">
        <div class="card-body d-flex flex-column">
          <!-- Inline edit form -->
          <div v-if="editId === event.id">
            <input class="form-control mb-2" v-model="editTitle" placeholder="Title" />
            <input type="datetime-local" class="form-control mb-2" v-model="editOccurrence" />
            <textarea class="form-control mb-2" v-model="editDescription" placeholder="Description"></textarea>
            <div class="d-flex justify-content-between mt-auto">
              <button class="btn btn-primary" @click="saveEdit(event.id)">Save</button>
              <button class="btn btn-secondary" @click="cancelEdit">Cancel</button>
            </div>
          </div>

          <!-- Normal card view -->
          <div v-else>
            <h5 class="card-title">{{ event.title }}</h5>
            <p class="card-text">{{ new Date(event.occurrence).toLocaleString() }}</p>
            <p class="card-text">{{ event.description }}</p>
            <div class="d-flex justify-content-between mt-auto">
              <button class="btn btn-primary" @click="startEdit(event)">Edit</button>
              <button class="btn btn-danger" @click="deleteEvent(event.id)">Delete</button>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <p v-if="!loading && events.length === 0">No events found.</p>
</div>
</template>