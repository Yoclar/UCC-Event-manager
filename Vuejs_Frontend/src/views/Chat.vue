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

        <div
          :class="[
            'p-2 rounded',
            m.sender === 'user'
              ? 'bg-primary text-white ms-auto user-msg'
              : 'bg-light bot-msg'
          ]"
        >
          {{ m.text }}
        </div>

      </div>

    </div>

    <div class="card-footer d-flex gap-2">
      <input
        v-model="input"
        @keyup.enter="sendMessage"
        class="form-control"
        placeholder="Type your message..."
      />

      <button
        class="btn btn-primary"
        @click="sendMessage"
      >
        Send
      </button>
    </div>

  </div>

</div>
</template>

<script>

import api from "../api/api";


export default {
  data(){
    return {
      input:"",
      messages:[]
    }
  },

  methods:{
    async sendMessage() {
  if (!this.input) return

  this.messages.push({
    sender: 'user',
    text: this.input
  })

  const userInput = this.input
  this.input = ""

  const res = await api.post('/chat', {
    message: userInput
  })

  setTimeout(() => {
    this.messages.push({
      sender: 'bot',
      text: res.data.reply
    })
  }, 500)
}
  }
}
</script>

<style>

.chat-body{
  height:400px;
  overflow-y:auto;
}

.user-msg{
  max-width:60%;
}

.bot-msg{
  max-width:60%;
}

</style>