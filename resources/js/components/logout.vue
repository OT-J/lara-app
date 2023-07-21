<script setup>
import router from '../router/index.js'
//check if already connected
if (!localStorage.getItem('user') ){
    router.push('/')
}
else {
    if(JSON.parse(localStorage.getItem('user')).user?.role =='employee'){
        router.push('/employee')
    }
    else{
        router.push('/admin')
    }
}

const logout=()=>{
    let token=JSON.parse(localStorage.getItem('user')).token
    fetch("/api/logout",{
        method: "POST",
        headers:{
            'Accept':'application/json',
            'Authorization': `Bearer ${token}`
            },
    }).then(()=>{
        localStorage.removeItem("user")
        router.push('/')
    })
}
</script>
<template>
    <a class="logout-btn" href="#"  @click="logout()">Logout</a>
</template>


<style>
.logout-btn {
  background-color: #f44336;
  color: #fff;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
</style>
