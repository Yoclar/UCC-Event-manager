<template>
  <div class="container py-4">

    <button class="btn btn-secondary logout-btn" @click="logout">Logout</button>

    <div class="row">
        

      <div class="col-4">
        <div class="card shadow">

          <div class="card-header bg-dark text-white d-flex gap-4">
            <span @click="activeTab = 'active'" :class="{ 'fw-bold': activeTab === 'active' } "style="cursor:pointer">
              Active Chats
            </span>

            <span @click="activeTab = 'closed'" :class="{ 'fw-bold': activeTab === 'closed' }" style="cursor:pointer">
              Previous Chats
            </span>
          </div>


          <ul class="list-group list-group-flush" v-if="activeTab === 'active'">
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


          <ul class="list-group list-group-flush" v-if="activeTab === 'closed'">
            <li 
              v-for="chat in previousChats" 
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
      

      <div class="col-8">
        <div class="card shadow" v-if="selectedChat">

          <div class="card-header bg-primary text-white d-flex justify-content-between">
            <span>Chat with {{ selectedChat.user.name }}</span>


            <button v-if="selectedChat.status !== 'closed'" class="btn btn-warning btn-sm" @click="closeChat">
              Close Chat
            </button>
          </div>

          <div class="card-body chat-body">
            <div v-for="(m, index) in selectedChat.messages" :key="index"class="mb-2">
              <div :class="['p-2 rounded', m.sender === 'agent' ? 'bg-primary text-white ms-auto' : 'bg-light']" style="max-width: 70%">
                <strong>{{ m.sender }}:</strong> {{ m.text }}
              </div>
            </div>
          </div>

          <div class="card-footer d-flex gap-2" v-if="selectedChat.status === 'active'">
            <input v-model="replyText" class="form-control" placeholder="Type your reply..." @keyup.enter="sendReply">
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
    const previousChats = ref([])
    const selectedChat = ref(null)
    const replyText = ref("")
    const activeTab = ref("active")
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

    const loadPreviousChats = async () => {
      const res = await api.get("/agent/previous-chats")
      previousChats.value = res.data
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

    const closeChat = async () => {
      if (!selectedChat.value) return

      await api.post("/agent/close", {
        chat_id: selectedChat.value.id
      })


      selectedChat.value.status = "closed"

      previousChats.value.push(selectedChat.value)


      chats.value = chats.value.filter(c => c.id !== selectedChat.value.id)

      selectedChat.value = null
    }

    onMounted(() => {
      loadChats()
      loadPreviousChats()
      refreshInterval = setInterval(() => {
        loadChats()
        loadPreviousChats()
      }, 5000)
    })

    onUnmounted(() => {
      clearInterval(refreshInterval)
    })

    return {
      chats,
      previousChats,
      selectedChat,
      replyText,
      activeTab,
      selectChat,
      sendReply,
      closeChat,
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
