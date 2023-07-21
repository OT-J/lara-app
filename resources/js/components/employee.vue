<script setup>
import {ref,onMounted} from 'vue'
import logout from './logout.vue'

let user=ref({})
let company=ref({})
onMounted(()=>{
    user.value = JSON.parse(localStorage.getItem('user')).user
    let token = JSON.parse(localStorage.getItem('user')).token
    //get user company
    fetch(`http://localhost:8000/api/company/${user.value.company_id}`,{
        method: 'GET',
        headers:{Authorization:`Bearer ${token}`}
    }).then(response=>{
        if(response.ok){
            return response.json()
        }
        else{
            console.error(response)
        }
    }).then(response=>{
        company.value = response[0]
    })
})
const update=()=>{
    let token = JSON.parse(localStorage.getItem('user')).token
    fetch(`http://localhost:8000/api/user`,{
        method:'PUT',
        body:JSON.stringify({...user.value}),
        headers:{'Content-Type':'application/json',
            Authorization:`Bearer ${token}`
        }
    }).then(response=>{
        if(response.ok){
            return response.json()
        }
        else{
            console.error(response)
        }
    }).then(employee=>{
        user.value =employee[0]
        localStorage.setItem('user',JSON.stringify({user:user.value,token:token}))
        window.location.reload()
    })
}

</script>

<template>
    <div>
        <logout/>
        <h1>Employee space</h1>
        <h3>Personal Info</h3>
        <!-- personal info here -->
        <h3>Member of Company {{ company.name }}</h3>
        <h4>Company details</h4>
        <p>Mail : {{ company.mail }}</p>
        <p>Description :{{ company.description }}</p>
        <h4>Company members</h4>
        <ul>
            <ul v-for="(member, index) in company.employees">{{ member.name }}</ul>
        </ul>
        <div class="container">
            <form action="" @submit.prevent="update()">
                <ul>

                    <li>
                        <input type="text" name="name" v-model="user.name">
                    </li>
                    <li>
                        <input type="text" name="email" disabled :value=" user.email ">
                    </li>
                    <li>
                        <input type="text" name="phone" placeholder="Phone" v-model="user.phone">
                    </li>
                    <li>
                        <input type="text" name="address" placeholder="Address" v-model="user.address">
                    </li>
                    <li>
                        <input type="date" name="birth_date" placeholder="Birth day" v-model="user.birth_date">
                    </li>
                    <li>
                        <button type="submit">update</button>
                    </li>
                </ul>
            </form>
    </div>
    </div>
</template>
<style>
input{
    display: block;
}
label{
    margin: auto;
}

</style>
