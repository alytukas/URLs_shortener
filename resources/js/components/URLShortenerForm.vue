<template>
    <div>
      <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="/">URL Shortener</a>
        </div>
      </nav>

      <div class="container mt-4">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <form @submit.prevent="submitUrl" class="mb-3">
              <div class="input-group">
                <input type="text" class="form-control" v-model="originalUrl" placeholder="Enter URL" required>
                <button type="submit" class="btn btn-primary">Shorten</button>
              </div>
            </form>

            <div v-if="shortUrl" class="alert alert-success">
              <strong>Short URL:</strong>
              <a :href="shortUrl" target="_blank">{{ shortUrl }}</a>
            </div>

            <div v-if="errorMessage" class="alert alert-danger">
              {{ errorMessage }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script>
  import axios from 'axios';

  export default {
    data() {
      return {
        originalUrl: '',
        shortUrl: '',
        errorMessage: ''
      };
    },
    methods: {
      async submitUrl() {
        try {
          const response = await axios.post('/shorten', { original_url: this.originalUrl });
          this.shortUrl = response.data.short_url;
          this.errorMessage = '';
        } catch (error) {
          this.errorMessage = error.response.data.error || 'An error occurred';
        }
      }
    }
  }
  </script>

  <style scoped>
  </style>
