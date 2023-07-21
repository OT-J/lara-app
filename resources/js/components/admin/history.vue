<script setup>
import { ref,onMounted,watch} from 'vue'
let histories = ref([])
let token=ref('')
if(localStorage.getItem('user')!=undefined){
    token = JSON.parse(localStorage.getItem('user')).token
}
onMounted(()=>{
    fetch("http://localhost:8000/api/history",{
        method:'get',
        headers:{
            'Accept':'application/json',
            'Content-Type':'application/json',
            'Authorization': `Bearer ${token}`
            },
        }).then((res)=>res.json())
        .then((data)=>{
            histories.value = data

        })
})
</script>
<template>
    <div class="content">
        <h3 v-if="histories.length>0">History</h3>
        <ul>
            <li v-for="(history) in histories">{{ history.message }}</li>
        </ul>
    </div>
    </template>
