<template>
  <div class="container py-4">

    <button class="btn btn-secondary logout-btn" @click="logout">Logout</button>

    <div class="row">
        
      <!-- CHAT LISTA -->
      <div class="col-4">
        <div class="card shadow">
          <div class="card-header bg-dark text-white">
            Waiting Chats
          </div>

          <ul class="list-group list-group-flush">
            <li 
              v-for="chat in chats" 
              :key="chat.id"
              class="list-group-item list-group-item-action"
              :class="{ active: selectedChat && selectedChat.id === chat.id }"
              @click="selectChat(chat)"
            >
              <strong>{{ chat.user.name }}</strong>
              <br>
              <small>Messages: {{ chat.messages.length }}</small>
            </li>
          </ul>
        </div>
      </div>
      
      <!-- CHAT ABLAK -->
      <div class="col-8">
        <div class="card shadow" v-if="selectedChat">
          <div class="card-header bg-primary text-white">
            Chat with {{ selectedChat.user.name }}
          </div>

          <div class="card-body chat-body">
            <div 
              v-for="(m, index) in selectedChat.messages" 
              :key="index"
              class="mb-2"
            >
              <div
                :class="[
                  'p-2 rounded',
                  m.sender === 'agent'
                    ? 'bg-primary text-white ms-auto'
                    : 'bg-light'
                ]"
                style="max-width: 70%"
              >
                <strong>{{ m.sender }}:</strong> {{ m.text }}
              </div>
            </div>
          </div>

          <div class="card-footer d-flex gap-2">
            <input 
              v-model="replyText"
              class="form-control"
              placeholder="Type your reply..."
              @keyup.enter="sendReply"
            >
            <button class="btn btn-primary" @click="sendReply">Send</button>
          </div>
        </div>

        <div v-else class="text-center text-muted mt-5">
          <h5>Select a chat to begin</h5>
        </div>
      </div>

    </div>

  </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from "vue"
import { useRouter } from "vue-router"
import api from "../api/api"

export default {
  setup() {
    const router = useRouter()

    const chats = ref([])
    const selectedChat = ref(null)
    const replyText = ref("")
    let refreshInterval = null

    const logout = () => {
      clearInterval(refreshInterval)
      localStorage.removeItem("token")
      localStorage.removeItem("role")
      router.push("/")
    }

    const loadChats = async () => {
      try {
        const res = await api.get("/agent-dashboard")
        chats.value = res.data
      } catch (error) {
        clearInterval(refreshInterval)
        router.push("/")
      }
    }

    const selectChat = (chat) => {
      selectedChat.value = chat
    }

    const sendReply = async () => {
      if (!replyText.value || !selectedChat.value) return

      await api.post("/agent/reply", {
        chat_id: selectedChat.value.id,
        text: replyText.value
      })

      selectedChat.value.messages.push({
        sender: "agent",
        text: replyText.value
      })

      replyText.value = ""
    }

    onMounted(() => {
      loadChats()
      refreshInterval = setInterval(loadChats, 5000)
    })

    onUnmounted(() => {
      clearInterval(refreshInterval)
    })

    return {
      chats,
      selectedChat,
      replyText,
      selectChat,
      sendReply,
      logout
    }
  }
}
</script>

<style>
.chat-body {
  height: 400px;
  overflow-y: auto;
}

.logout-btn {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 9999;
}
</style>
