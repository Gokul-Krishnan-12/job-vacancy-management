<template>
    <div class="vacancies">
        <h2>Vacancies</h2>
        <button @click="showAddVacancyForm = true" class="btn btn-primary">Add Vacancy</button>

        <div v-if="showAddVacancyForm" class="form-container">
            <h3>{{ editingVacancy ? 'Edit Vacancy' : 'Add Vacancy' }}</h3>
            <div class="form-group">
                <label for="job_id">Job</label>
                <select v-model="form.job_id" id="job_id" class="form-control">
                    <option v-for="job in jobs" :key="job.id" :value="job.id">{{ job.job_name }}</option>
                </select>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input v-model="form.start_date" type="date" id="start_date" class="form-control">
            </div>
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input v-model="form.end_date" type="date" id="end_date" class="form-control">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea v-model="form.description" id="description" class="form-control"></textarea>
            </div>
            <div class="form-group" v-if="editingVacancy">
                <label for="status">Status</label>
                <select v-model="form.status" id="status" class="form-control">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button v-if="!editingVacancy" @click="addVacancy" class="btn btn-success">Save</button>
            <button v-if="editingVacancy" @click="updateVacancy" class="btn btn-success">Update</button>
            <button @click="cancelForm" class="btn btn-secondary">Cancel</button>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Job</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="vacancy in vacancies" :key="vacancy.id">
                    <td>{{ vacancy.job.job_name }}</td>
                    <td>{{ vacancy.start_date }}</td>
                    <td>{{ vacancy.end_date }}</td>
                    <td>{{ vacancy.description }}</td>
                    <td>{{ vacancy.status }}</td>
                    <td>
                        <button @click="editVacancy(vacancy)" class="btn btn-warning">Edit</button>
                        <button v-if="!vacancy.deleted_at" @click="deleteJob(vacancy.id)" class="btn btn-danger">Delete</button>
                        
                        <!-- Restore button for soft-deleted jobs -->
                        <button v-if="vacancy.deleted_at" @click="restoreJob(vacancy.id)" class="btn btn-success">Restore</button>
                        <button @click="sendMail(vacancy.id)" class="btn btn-info">Send Mail</button>
                    </td>
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
            vacancies: [],
            jobs: [],
            showAddVacancyForm: false,
            editingVacancy: null,
            form: {
                id: null,
                job_id: null,
                start_date: '',
                end_date: '',
                description: '',
                status: 'active'
            }
        }
    },
    methods: {
        fetchVacancies() {
            axios.get('/api/v1/vacancies')
                .then(response => {
                    this.vacancies = response.data;
                })
                .catch(error => {
                    console.error('Error fetching vacancies:', error);
                });
        },
        fetchJobs() {
            axios.get('/api/v1/jobs/active')
                .then(response => {
                    this.jobs = response.data;
                })
                .catch(error => {
                    console.error('Error fetching jobs:', error);
                });
        },
        addVacancy() {
            axios.post('/api/v1/vacancies/add', this.form)
                .then(() => {
                    this.fetchVacancies();
                    this.showAddVacancyForm = false;
                    this.resetForm();
                })
                .catch(error => {
                    console.error('Error adding vacancy:', error);
                });
        },
        editVacancy(vacancy) {
            this.editingVacancy = vacancy.id;
            this.form = {
                id: vacancy.id,
                job_id: vacancy.job_id,
                start_date: vacancy.start_date,
                end_date: vacancy.end_date,
                description: vacancy.description,
                status: vacancy.status
            };
            this.showAddVacancyForm = true;
        },
        updateVacancy() {
            axios.put(`/api/v1/vacancies/${this.form.id}`, this.form)
                .then(() => {
                    this.fetchVacancies();
                    this.showAddVacancyForm = false;
                    this.resetForm();
                })
                .catch(error => {
                    console.error('Error updating vacancy:', error);
                });
        },
        deleteJob(vacancyId) {
            axios.delete(`/api/v1/vacancies/delete-vacancy/${vacancyId}`)
                .then(() => {
                    this.fetchVacancies();
                })
                .catch(error => {
                    console.error('Error deleting vacancy:', error);
                });
        },
        restoreJob(vacancyId) {
            // Restore soft-deleted vacancy by ID
            axios.put(`/api/v1/vacancies/restore/${vacancyId}`)
                .then(() => {
                    this.fetchVacancies(); // Refresh job list after restoration
                })
                .catch(error => {
                    console.error('Error restoring vacancy:', error);
                });
        },
        sendMail(vacancyId) {
            axios.post(`/api/v1/vacancies/${vacancyId}/send-mail`)
                .then(() => {
                    alert('Mail sent successfully');
                })
                .catch(error => {
                    console.error('Error sending mail:', error);
                });
        },
        cancelForm() {
            this.showAddVacancyForm = false;
            this.resetForm();
        },
        resetForm() {
            this.form = {
                id: null,
                job_id: null,
                start_date: '',
                end_date: '',
                description: '',
                status: 'active'
            };
            this.editingVacancy = null;
        }
    },
    mounted() {
        this.fetchVacancies();
        this.fetchJobs();
    }
}
</script>

<style scoped>
.vacancies {
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
    display: inline-block;
    padding: 8px 12px;
    margin: 5px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-success {
    background-color: #28a745;
    color: white;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-warning {
    background-color: #ffc107;
    color: black;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.btn-info {
    background-color: #17a2b8;
    color: white;
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
