<script setup>
import { ref } from 'vue';
import axios from 'axios';

axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://localhost:8000';
axios.defaults.headers.post['Content-Type'] = 'application/json';
axios.defaults.headers.post['Accept'] = 'application/json';

const user = ref(null);
async function onLogin() {
    await axios.get(`/sanctum/csrf-cookie`);
    // await axios.post('/login', {
    //     email: form.value.email,
    //     password: form.value.password
    // });

    let response = await axios.get('/api/user');
    user.value = response.data;
}

const form = ref({
    email: '',
    password: '',
})

const price = ref({
    min: 0,
    max: 1000
});
</script>

<template>
    <div>
        <p>{{ user }}</p>
        <h1>Login</h1>
        <form @submit.prevent="onLogin">
            <div>
                <label for="email">Email :</label>
                <input type="email" id="email" v-model="form.email">
            </div>
            <div>
                <label for="password">Password :</label>
                <input type="password" id="password" v-model="form.password">
            </div>
            <div>
                <button>Login</button>
            </div>
        </form>
    </div>
</template>

<style scoped>
.logo {
    height: 6em;
    padding: 1.5em;
    will-change: filter;
    transition: filter 300ms;
}

.logo:hover {
    filter: drop-shadow(0 0 2em #646cffaa);
}

.logo.vue:hover {
    filter: drop-shadow(0 0 2em #42b883aa);
}
</style>
