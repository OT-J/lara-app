<script setup>
import { onMounted,ref } from "vue";
let invites = ref([])
let token=ref('')
if(localStorage.getItem('user')!=undefined){
    token = JSON.parse(localStorage.getItem('user')).token
}
onMounted(()=>{

    fetch("http://localhost:8000/api/currentUser",{
        method:'get',
        headers:{
            'Accept':'application/json',
            'Content-Type':'application/json',
            'Authorization': `Bearer ${token}`
            },

    }).then((res)=>res.json()).then((res)=>{
        invites.value = res.invites
        console.log(res.invites);
    })
})

const CancelInvite=(id)=>{
    console.log(id)
    let answer =confirm('are you sure ?')
    if(answer)
    {
        fetch(`http://localhost:8000/api/cancelInvite/${id}`,{
        method:'delete',
        headers:{
            'Accept':'application/json',
            'Content-Type':'application/json',
            'Authorization': `Bearer ${token}`
            },

    }).then((res)=>res.json()).then((res)=>{
            console.log(res);
            fetch("http://localhost:8000/api/currentUser",{
            method:'get',
            headers:{
                'Accept':'application/json',
                'Content-Type':'application/json',
                'Authorization': `Bearer ${token}`
                },

        }).then((res)=>res.json()).then((res)=>{
            invites.value = res.invites
            console.log(res.invites);
        })
    })
    }
}
</script>

<template>
<div class="content">

    <h3 v-if=" invites!=undefined && invites.length>0">Invites</h3>
    <ul>
        <li v-for="(invite) in invites" :key="invite.id">
             Inviting [{{ invite.employee_name }}] to join [{{ invite.company.name }}]  current State [{{ invite.state }}]  
             <a class="button" v-if="invite.state=='in progress'" @click="CancelInvite(invite.id)" >Cancel invitation?</a></li>
    </ul>

</div>
</template>
<style scoped>
    .button{
        cursor: pointer;
        background: red;
        padding: 2px;
        margin: 2px;
        color: white;
        font-size: large;
        border-radius: 5px;
    }
</style>
