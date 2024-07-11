<template>
    <div class="jobs">
        <h2>Users</h2>
       

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td v-if="!user.editing">{{ user.name }}</td>
                    <td v-if="!user.editing">{{ user.status }}</td>
                    <td v-if="!user.editing">{{ user.email }}</td>
                    
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            users: [],
            showAddUserForm: false,
        }
    },
    methods: {
        fetchUsers() {
            axios.get('/api/v1/users')
                .then(response => {
                    this.users = response.data;
                })
                .catch(error => {
                    console.error('Error fetching jobs:', error);
                });
        },
       

    },
    mounted() {
        this.fetchUsers();
    }
}
</script>

<style scoped>
.jobs {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    color: #333;
}

.btn {
    margin-right: 10px;
}

.form-container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-control {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.table th,
.table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.table th {
    background-color: #f2f2f2;
    color: #333;
}

.table td button {
    margin-right: 5px;
}
</style>
