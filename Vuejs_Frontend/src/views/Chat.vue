<template>
<div class="container py-4">

  <div class="card shadow">

    <div class="card-header bg-primary text-white">
      Helpdesk Chat
    </div>

    <div class="card-body chat-body">

      <div
        v-for="(m,index) in messages"
        :key="index"
        class="mb-2"
      >
        <strong>
          {{ m.sender === 'user' ? 'you' : m.sender }}:
        </strong>
        {{ m.text }}
      </div>

    </div>

    <div class="card-footer d-flex gap-2">
      <input v-model="input" @keyup.enter="sendMessage" class="form-control" placeholder="Type your message..."/>

      <button class="btn btn-primary" @click="sendMessage">
        Send
      </button>
    </div>

  </div>

</div>
</template>

<script>
import api from "../api/api"

export default {
  data(){
    return {
      input:"",
      messages:[],
      refreshInterval: null
    }
  },

  methods:{

    async loadChat() {
      const res = await api.get("/chat")
      this.messages = res.data.messages
    },

    async sendMessage() {
      if (!this.input) return

      const userInput = this.input
      this.input = ""

      await api.post('/chat', {
        message: userInput
      })


    }
  },

  async mounted() {
    await this.loadChat()

    this.refreshInterval = setInterval(() => {
      this.loadChat()
    }, 2000)
  },

  unmounted() {
    clearInterval(this.refreshInterval)
  }
}
</script>

<style>
.chat-body{
  height:400px;
  overflow-y:auto;
}
</style>
