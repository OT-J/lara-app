<script setup>
import logout from './logout.vue'
import registerAdmin from './admin/registerAdmin.vue'
import newCompany from './admin/newCompany.vue'
import invites from './admin/invites.vue'
import history from './admin/history.vue'

</script>

<template>
    <div>
        <logout/>
        <h1>Admin space</h1>

        <div class="content">
            <registerAdmin/>
            <newCompany/>
            <invites/>
            <history/>
        </div>

    </div>
</template>
