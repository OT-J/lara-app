<script setup>
import {ref, onMounted, computed, watch} from 'vue'

let name = ref(null)
let email = ref(null)
let description = ref(null)
let dbCompanies = ref([])

let token=''
if(localStorage.getItem('user')!=undefined){
    token = JSON.parse(localStorage.getItem('user')).token
}
let companies = ref([]);
let filter=ref('')
let tmpCompany = ref({})
let inviteName = ref('')
let inviteMail = ref('')
onMounted(()=>{
    fetch("http://localhost:8000/api/company",{
        method:'get',
        headers:{
            'Accept':'application/json',
            'Content-Type':'application/json',
            'Authorization': `Bearer ${token}`
            },
    }).then((res)=>res.json())
    .then((data)=>{
        companies.value = data
        dbCompanies.value =  data
    })
})
const save = ()=>{
    fetch("http://localhost:8000/api/company",{
        method:'post',
        headers:{
            'Accept':'application/json',
            'Content-Type':'application/json',
            'Authorization': `Bearer ${token}`
            },
        body:JSON.stringify({
                name:name.value,
                email:email.value,
                description:description.value,
            })
    }).then((res)=>res.json()).then((res)=>{
        companies.value = res.data.original
        dbCompanies.value =  res.data.original
        name.value = email.value = description.value = ''

    })
}


const inspect= (item)=>{
    tmpCompany.value = item
}

const update=()=>{
    const companyId = tmpCompany.value.id;
    fetch(`http://localhost:8000/api/company/${companyId}`,{
        method:'put',
        headers:{
            'Accept':'application/json',
            'Content-Type':'application/json',
            'Authorization': `Bearer ${token}`
            },
        body:JSON.stringify({
                name:tmpCompany.value.name,
                mail:tmpCompany.value.mail,
                description:tmpCompany.value.description,
            })
    }).then((res)=>res.json()).then((res)=>{
        if(res.data?.original)
            companies.value = res.data.original
    })
}
const find=()=>{
    companies.value=dbCompanies.value.filter((company)=> 
        company.name.toUpperCase().includes(filter.value.toUpperCase())
    )
    
    let res=dbCompanies.value.filter((company) => {
    // Check if any employee in the company has a matching name
    return company.employees.some((employee) => employee.name.toUpperCase().includes(filter.value.toUpperCase()));
  });
  companies.value=[...companies.value, ...res];
}
const deleteCompany =()=>{
    let answer =confirm('are you sure ?')
    if(answer)
    {
        fetch(`http://localhost:8000/api/company/${tmpCompany.value.id}`, {
        method: 'DELETE',
        headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
        },
        }).then((res)=>res.json()).then((res)=>{
            if(res.data.original)
                companies.value = res.data.original
            alert(res.message)
        })
    }
}
const inviteEmployee =()=>{

    fetch("http://localhost:8000/api/invite",{
        method:'post',
        headers:{
            'Accept':'application/json',
            'Content-Type':'application/json',
            'Authorization': `Bearer ${token}`
            },
        body:JSON.stringify({
                name:inviteName.value,
                email:inviteMail.value,
                company:tmpCompany.value.name,
                company_id:tmpCompany.value.id
            })
    }).then((res)=>res.json()).then((res)=>{
        alert(res.message)
        window.location.reload()
    })
}

// Function to sort the array of objects by a given property
const orderAsc = ref('asc');

// ...

// Function to sort the array of objects by a given property and order
function sortByProperty(array, propertyName, order) {
  // 'order' is now a computed property, so no need to pass it as an argument
  return array.slice().sort((a, b) => {
    // Convert property values to lowercase for case-insensitive sorting (optional)
    const propA = a[propertyName].toString().toLowerCase();
    const propB = b[propertyName].toString().toLowerCase();

    // Determine the sorting direction based on the 'order' computed property
    const compareResult = order.value === 'desc' ? -1 : 1;

    // Compare the property values
    if (propA < propB) {
      return -1 * compareResult;
    } else if (propA > propB) {
      return 1 * compareResult;
    } else {
      return 0;
    }
  });
}

// ...

// Modify the 'sortCompanies' function
const sortCompanies = () => {
  // Update the value of the 'order' computed property
  
  // Now use the updated 'orderAsc' computed property when sorting
  companies.value = sortByProperty(companies.value, 'name', orderAsc);
  orderAsc.value = orderAsc.value === 'asc' ? 'desc' : 'asc';
};
</script>
<template>
    <div class="container">
        <div class="form">
            <form  @submit.prevent="save()">

                <h4>New company </h4>

                <input type="text" name="name" v-model="name" placeholder="Company name">
                <input type="email" name="email" v-model="email" placeholder="Company email">
                <textarea name="description" v-model="description"></textarea>
                <button type="submit" :disabled="!name ||!email">save</button>
            </form>
        </div>
        <div class="list">
            <input type="text" name="filter" v-model="filter" placeholder="Enter company or employee name" @keyup="find()" style="width:300px;margin-top: 70px; margin-left: 1em;">
            <a style="cursor: pointer; margin-left: 2em;" @click="sortCompanies()">sort {{ orderAsc=='asc'?'🔼':'🔽' }}</a>
            <ul>
                <li v-for="(item) in companies"><a href="#" @click="inspect(item)">{{ item.name}} &nbsp;  <span style="float: right; margin-right: 1em;"> employees:{{ item.employees!=undefined? item.employees.length : 0}}</span></a></li>
            </ul>
        </div>

        <div class="form" v-if="tmpCompany.id!== undefined ">
            <form  @submit.prevent="update(tmpCompany.value)">

                <h4>Update {{ tmpCompany.name }}</h4>

                <input type="text" name="name" v-model="tmpCompany.name" placeholder="Company name">
                <input type="email" name="email" v-model="tmpCompany.mail" placeholder="Company email">
                <textarea name="description" v-model="tmpCompany.description"></textarea>
                <button type="submit">update</button>
                <button id="delete" type="button" @click="deleteCompany(tmpCompany.value)">delete</button>
                <ul>
                    <h4 v-if="tmpCompany.employees.length>0">employees</h4>
                    <li v-for="(employee) in tmpCompany.employees" > {{ employee.name }}</li>
                </ul>

                <input type="text" name="name" v-model="inviteName" placeholder="Employee name">
                <input type="email" name="email" v-model="inviteMail" placeholder="Employee email">
                <button id="invite" type="button" @click="inviteEmployee()">invite</button>
            </form>
        </div>
    </div>
</template>
<style scoped>
.container{
    display: flex;
    justify-content: center;
    width: 90%;
}
form{
    width: 80%;
    padding: 1em;
}
input,textarea{
    margin:1rem auto;
    padding: 1rem;
    display: block;
    font-size: larger;
    border: 1px solid #ddd;
    border-radius: 1em;
}
button{
    background:#069c12 ;
    color: white;
    width: 100%;
    margin:1rem auto;
    padding: 1rem;
    cursor: pointer;
    font-size: larger;
    border-radius: 1em;
}
.list ul{
    list-style: none;
}
#delete{
    background-color: #810d0d;
}
#invite{
    background-color: #1453c7;
}

</style>
