<template>
    <div class="jobs">
        <h2>Jobs</h2>
        <button @click="showAddJobForm = true" class="btn btn-primary">Add Job</button>

        <div v-if="showAddJobForm" class="form-container">
            <h3>Add Job</h3>
            <div class="form-group">
                <label for="job_name">Job Name</label>
                <input v-model="newJob.job_name" id="job_name" placeholder="Job Name" class="form-control">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select v-model="newJob.status" id="status" class="form-control">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button @click="addJob" class="btn btn-success">Save</button>
            <button @click="showAddJobForm = false" class="btn btn-secondary">Cancel</button>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Job Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="job in jobs" :key="job.id">
                    <td v-if="!job.editing">{{ job.job_name }}</td>
                    <td v-if="!job.editing">{{ job.status }}</td>
                    <td v-if="!job.editing">
                        <button @click="editJob(job)" class="btn btn-warning">Edit</button>
                        <button v-if="!job.deleted_at" @click="deleteJob(job.id)" class="btn btn-danger">Delete</button>
                        
                        <!-- Restore button for soft-deleted jobs -->
                        <button v-if="job.deleted_at" @click="restoreJob(job.id)" class="btn btn-success">Restore</button>
                    </td>
                    <td v-if="job.editing">
                        <input v-model="job.job_name" class="form-control">
                    </td>
                    <td v-if="job.editing">
                        <select v-model="job.status" class="form-control">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </td>
                    <td v-if="job.editing">
                        <button @click="updateJob(job)" class="btn btn-success">Save</button>
                        <button @click="cancelEdit(job)" class="btn btn-secondary">Cancel</button>
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
            jobs: [],
            showAddJobForm: false,
            newJob: {
                job_name: '',
                status: 'active'
            }
        }
    },
    methods: {
        fetchJobs() {
            axios.get('/api/v1/jobs')
                .then(response => {
                    this.jobs = response.data;
                })
                .catch(error => {
                    console.error('Error fetching jobs:', error);
                });
        },
        addJob() {
            axios.post('/api/v1/jobs', this.newJob)
                .then(() => {
                    this.fetchJobs();
                    this.showAddJobForm = false;
                    this.newJob = { job_name: '', status: 'active' };
                })
                .catch(error => {
                    console.error('Error adding job:', error);
                });
        },
        editJob(job) {
            job.editing = true; // Enter editing mode for the clicked job
        },
        updateJob(job) {
            axios.put(`/api/v1/jobs/update-job/${job.id}`, job)
                .then(() => {
                    job.editing = false; // Exit editing mode after update
                })
                .catch(error => {
                    console.error('Error editing job:', error);
                });
        },
        cancelEdit(job) {
            job.editing = false; // Exit editing mode without saving changes
        },
        deleteJob(jobId) {
            axios.delete(`/api/v1/jobs/delete-job/${jobId}`)
                .then(() => {
                    this.fetchJobs();
                })
                .catch(error => {
                    console.error('Error deleting job:', error);
                });
        },
        restoreJob(jobId) {
            // Restore soft-deleted job by ID
            axios.put(`/api/v1/jobs/restore/${jobId}`)
                .then(() => {
                    this.fetchJobs(); // Refresh job list after restoration
                })
                .catch(error => {
                    console.error('Error restoring job:', error);
                });
        },
    },
    mounted() {
        this.fetchJobs();
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
