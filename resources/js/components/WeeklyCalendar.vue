<template>
  <div class="weekly-calendar">
    <h2>Weekly Calendar</h2>

    <div class="job-selection">
      <h3>Schedule Job</h3>
      <div class="select-container">
        <select v-model="selectedVacancy" class="vacancy-select">
          <option disabled value="">Select a vacancy</option>
          <option v-for="vacancy in vacancies" :key="vacancy.id" :value="vacancy.id">{{ vacancy.job.job_name }}</option>
        </select>
        <select v-model="selectedUser" class="user-select">
          <option disabled selected value="">Select a user</option>
          <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
        </select>
      </div>
      <button class="schedule-btn" @click="scheduleVacancy(selectedVacancy, selectedUser)">Schedule</button>
    </div>

    <div class="calendar">
      <div class="week">
        <div class="day" v-for="(day, index) in calendarData" :key="index">
          <div class="day-header">{{ daysOfWeek[index] }}</div>
          <div class="day-body">
            <div v-if="day.scheduledVacancies.length === 0" class="no-vacancies">No vacancies scheduled</div>
            <div v-else class="scheduled-vacancies">
              <div class="vacancy" v-for="scheduledVacancy in day.scheduledVacancies" :key="scheduledVacancy.id">
                <span>{{ scheduledVacancy.user.name }}</span>
                <span>({{ scheduledVacancy.vacancy.job.job_name }})</span>
                <span>Status: {{ scheduledVacancy.status }}</span>
                <div>
                  <button class="cancel-btn" @click="cancelScheduledVacancy(scheduledVacancy.id)">Cancel</button>
                  <button class="edit-btn" @click="editScheduledVacancy(scheduledVacancy)">Edit</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Modal -->
    <div v-if="editModalActive" class="modal" :class="{ 'is-active': editModalActive }">
      <div class="modal-background"></div>
      <div class="modal-content">
        <div class="box">
          <h3>Edit Scheduled Vacancy</h3>
          <div class="field">
            <label class="label">Vacancy</label>
            <select class="input" v-model="editedVacancy.vacancy_id">
              <option v-for="vacancy in vacancies" :key="vacancy.id" :value="vacancy.id">{{ vacancy.job.job_name }}</option>
            </select>
          </div>
          <div class="field">
            <label class="label">User</label>
            <select class="input" v-model="editedVacancy.user_id">
              <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
            </select>
          </div>
          <div class="field">
            <label class="label">Status</label>
            <select class="input" v-model="editedVacancy.status">
              <option value="scheduled">Scheduled</option>
              <option value=cancelled>Canceled</option>
            </select>
          </div>
          <button class="button is-danger" @click="saveEditedVacancy">Save Changes</button>
          <button class="button" @click="closeEditModal">Cancel</button>
        </div>
      </div>
      <button class="modal-close is-large" aria-label="close" @click="closeEditModal"></button>
    </div>
  </div>
</template>


<script>
import axios from 'axios';

export default {
  data() {
    return {
      calendarData: [],
      vacancies: [],
      users: [],
      selectedVacancy: "",
      selectedUser: "",
      daysOfWeek: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
      editModalActive: false,
      editedVacancy: null,

    };
  },
  mounted() {
    this.fetchCalendarData();
    this.fetchVacancies();
    this.fetchUsers();
  },
  methods: {
    fetchCalendarData() {
      axios.get('/api/v1/scheduled-vacancies')
        .then(response => {
          // Initialize calendarData with days of the week
          this.calendarData = this.daysOfWeek.map(day => ({
            day,
            scheduledVacancies: [],
          }));

          response.data.forEach(scheduledVacancy => {
            const startDate = new Date(scheduledVacancy.vacancy.start_date);
            const dayOfWeek = startDate.getDay(); // 0 (Sunday) to 6 (Saturday)

            if (dayOfWeek >= 0 && dayOfWeek < this.daysOfWeek.length) {
              this.calendarData[dayOfWeek].scheduledVacancies.push(scheduledVacancy);
            }
          });
        })
        .catch(error => {
          console.error('Error fetching scheduled vacancies:', error);
        });
    },
    fetchVacancies() {
      axios.get('/api/v1/vacancies/active')
        .then(response => {
          this.vacancies = response.data;
        })
        .catch(error => {
          console.error('Error fetching vacancies:', error);
        });
    },
    fetchUsers() {
      axios.get('/api/v1/users/active')
        .then(response => {
          this.users = response.data;
        })
        .catch(error => {
          console.error('Error fetching users:', error);
        });
    },
    scheduleVacancy(vacancyId, userId) {
      axios.post('/api/v1/scheduled-vacancies', { vacancy_id: vacancyId, user_id: userId })
        .then(response => {
          this.fetchCalendarData(); // Refresh calendar after scheduling
          this.selectedVacancy = "";
          this.selectedUser = "";
        })
        .catch(error => {
          console.error('Error scheduling vacancy:', error);
        });
    },
    cancelScheduledVacancy(scheduledVacancyId) {
      axios.delete(`/api/v1/scheduled-vacancies/${scheduledVacancyId}`)
        .then(response => {
          this.fetchCalendarData();
        })
        .catch(error => {
          console.error('Error canceling scheduled vacancy:', error);
        });
    },
    editScheduledVacancy(scheduledVacancy) {
      this.editedVacancy = { ...scheduledVacancy }; 
      this.editModalActive = true;
    },
    saveEditedVacancy() {
      axios.put(`/api/v1/scheduled-vacancies/${this.editedVacancy.id}`, this.editedVacancy)
        .then(response => {
          this.fetchCalendarData();
          this.closeEditModal();
        })
        .catch(error => {
          console.error('Error saving edited vacancy:', error);
        });
    },
    closeEditModal() {
      this.editedVacancy = null;
      this.editModalActive = false;
    },
  },
};
</script>

<style scoped>
.weekly-calendar {
  margin: auto;
  padding: 5px
}

.calendar {
  display: flex;
  background: #f0f0f0;
  border-radius: 5px;
  overflow-x: auto;
}

.week {
  display: flex;
  flex-wrap: wrap;
}

.day {
  flex: 1 0 calc(14.28% - 10px);
  margin: 5px;
  background: #fff;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.day-header {
  padding: 10px;
  background: #ddd;
  border-top-left-radius: 5px;
  border-top-right-radius: 5px;
}

.day-body {
  padding: 10px;
}

.no-vacancies {
  color: #999;
  padding: 10px;
}

.scheduled-vacancies {
  margin-top: 5px;
}

.vacancy {
  margin-bottom: 5px;
  padding: 10px;
  background: #f0f0f0;
  border: 1px solid #ccc;
  border-radius: 5px;
  display: flex;
  justify-content: space-between;
  gap:5px;
  flex-direction: column;
}

.cancel-btn,
.edit-btn,
.schedule-btn {
  margin-right: 5px;
  border: none;
  cursor: pointer;
  border-radius: 4px;
}

.cancel-btn {
  background-color: #f44336;
  color: white;
}

.cancel-btn:hover {
  background-color: #d32f2f;
}

.edit-btn {
  background-color: #4caf50;
  color: white;
}

.edit-btn:hover {
  background-color: #45a049;
}

.schedule-btn {
  margin-top: 5px;
  background-color: #2196F3;
  color: white;
}

.schedule-btn:hover {
  background-color: #0b7dda;
}

.job-selection {
  padding: 50px 0;
  max-width: 400px;
}

.select-container {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.vacancy-select,
.user-select {
  flex: 1;
  margin-right: 10px;
  padding: 8px;
  box-sizing: border-box;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: none;
  justify-content: center;
  align-items: center;
}

.modal.is-active {
  display: flex;
}

.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
  max-width: 400px;
  width: 100%;
}

.modal-background {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-close {
  position: absolute;
  top: 15px;
  right: 15px;
  background: none;
  border: none;
  cursor: pointer;
}
.field{
  padding: 5px;
}

.field .label {
  margin-right: 10px;
}

</style>
