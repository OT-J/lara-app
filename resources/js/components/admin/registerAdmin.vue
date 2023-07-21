<script setup>
import {ref, onMounted, computed, watch} from 'vue'
let admins = ref([])
let dbAdmins = ref([])
let filter=ref('')
let token=''

let name=ref('')
let email=ref('')
let password=ref('')

if(localStorage.getItem('user')!=undefined){
    token = JSON.parse(localStorage.getItem('user')).token
}

onMounted(()=>{
    fetch("http://localhost:8000/api/user",{
        method:'post',
        headers:{
            'Accept':'application/json',
            'Content-Type':'application/json',
            'Authorization': `Bearer ${token}`
            },
    }).then((res)=>res.json())
    .then((data)=>{
        admins.value = data
        dbAdmins.value =  data
    })
})

const find=()=>{
    admins.value=dbAdmins.value.filter(admin=> admin.name.toUpperCase().includes(filter.value.toUpperCase())  )
}

const save = ()=>{

    fetch("http://localhost:8000/api/register",{
        method:'post',
        headers:{
            'Accept':'application/json',
            'Content-Type':'application/json',
            'Authorization': `Bearer ${token}`
            },
        body:JSON.stringify({
                name:name.value,
                email:email.value,
                password:password.value,
                role:"Admin"
            })
    }).then((res)=>res.json()).then((res)=>{
        fetch("http://localhost:8000/api/user",{
        method:'post',
        headers:{
            'Accept':'application/json',
            'Content-Type':'application/json',
            'Authorization': `Bearer ${token}`
            },
        }).then((res)=>res.json())
        .then((data)=>{
            admins.value = data
            dbAdmins.value =  data
            name.value = email.value = password.value = '';
        })

    })
}
</script>
<template>
    <div class="container">

        <div class="form">
            <form  @submit.prevent="save()">

                <h4>New Admin </h4>

                <input type="text" name="name" v-model="name" placeholder="Admin name">
                <input type="email" name="email" v-model="email" placeholder="Admin email">
                <input type="password" name="password" v-model="password" placeholder="Admin password">
                <button type="submit" :disabled="!name ||!email">save</button>
            </form>
        </div>
        <div class="list">

            <input type="text" name="filter" v-model="filter" placeholder="Enter admin name" @keyup="find()" style="width:300px;margin-top: 70px; margin-left: 1em;">
            <ul>
                <li v-for="(item) in admins">{{ item.name}} </li>
            </ul>
        </div>
    </div>
</template>

<style scoped>
input,button{
    display: block;
    font-size: larger;
}

</style>
