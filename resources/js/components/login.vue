<script setup>
import {ref} from "vue"
import router from '../router/index.js'
const email = ref(null)
const password = ref(null)

if (!localStorage.getItem('user') ){
    router.push('/')
}
else {
    if(JSON.parse(localStorage.getItem('user')).user?.role =='employee'){
        router.push('/employee')
    }else{
        router.push('/admin')
    }
}


const login=()=>{

    fetch("http://localhost:8000/api/login",{
        method:"POST",
        headers:{
            'Content-Type': 'application/json'
            },
            body: JSON.stringify({
            "email":email.value,
            "password":password.value
        })
    }).then((response)=>{{
    // Check if the response status is successful (2xx range)
    if (response.ok) {
      return response.json(); // Convert the response body to JSON
    } else {
        alert('Invalid credentials');
      // If the response status is not successful, handle the error
      throw new Error('Request failed with status ' + response.status);
    }
  }}).then((data)=>{
        // save authenticated user to localstorage
        let datastr = JSON.stringify({
            user:data.user,
            token: data.token
        })
        localStorage.setItem('user',datastr);

        router.push('/')
        window.location.reload();
    })
}


</script>

<template>
<div class="container">
    <div class="form">
        <form  @submit.prevent="login()">

            <h1>Authentification</h1>
            <input type="email"  v-model="email" placeholder="Email" name="email"><br>
            <input type="password" v-model="password" placeholder="Password" name="password"><br>
            <button type="submit" >Login</button>
        </form>
    </div>
</div>
</template>
<style scoped>

</style>
