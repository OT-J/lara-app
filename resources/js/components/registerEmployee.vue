<script setup>
import {ref, onMounted} from 'vue'
import router from '../router'

let formData=ref({})

onMounted(()=>{

  //TODO::add new history

    let url = window.location.search.replace(' ','_').replace('%20',' ').replace('?','')

    let query = url.split('&')
    const obj = {};

    for (let i = 0; i < query.length; i++) {
        const element = query[i];
        let [key, value] = element.split('=');
        obj[key] = value;
    }
    formData.value=obj
    //check if invitation exists
    fetch(`http://localhost:8000/api/invite/${obj.invite_id}`,{
        method:'GET',
        headers:{
            'Content-Type':'application/json'
            }
    }).then((res)=>res.json()).then(
        (data) => {
            if(data[0]==false)//invitation not exists
            {
                router.push('/')
            }
            else{  //TODO::update invitation status if exists
                if(data.state!==undefined && data.state =='in progress'){
                    
                    fetch(`http://localhost:8000/api/invite`,{
                        method:'PUT',
                        headers:{
                            'Content-Type':'application/json'
                        },
                        body:JSON.stringify({
                            id:obj.invite_id,
                            updater:obj.name,
                            state:'Valid'
                        })
                    }).then((res)=>res.json()).then(res=>{console.log(res)})
                }
            }
        }
    )

})

const confirm=()=>{
    //TODO::register new employee
    formData.value.role='employee'
    fetch('http://localhost:8000/api/register',{
        method:"POST",
        headers:{
            "content-type":"application/json"
        },
        body:JSON.stringify(formData.value)
    }).then((res)=>res.json()).then(data=>{
        let datastr = JSON.stringify({
            user:data.user,
            token: data.token
        })
        //update 
         localStorage.setItem('user',datastr);
         window.location.href= '/employee'
    
        
        
    })
}
</script>
<template>
    <div class="container">
        <h1>Confirm your registration</h1><hr>
        <form  @submit.prevent="confirm()">

            <input type="text" v-model="formData.name"  name="name" placeholder="Employee name" >
            <input type="text" v-model="formData.email"  name="email" placeholder="Employee email" >
            <input type="password" v-model="formData.password"  name="password" placeholder="Employee password" >

            <input type="text" v-model="formData.phone"  name="phone" placeholder="Employee phone" >
            <input type="text" v-model="formData.address"  name="address" placeholder="Employee address" >
            <input type="date" v-model="formData.birthday"  name="birthdate" placeholder="Employee birthday" >
            <button type="submit">confirm</button>
        </form>
    </div>
</template>
<style scoped>
input,button,textarea{
    display: block;
    margin: 1em;
}
.container{
    width: 50vh;
    margin: auto;
}
</style>
