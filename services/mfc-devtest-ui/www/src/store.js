import Vue from 'vue';
import axios from 'axios';

import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    status: '',
    token: localStorage.getItem('token') || '',
    user: JSON.parse(localStorage.getItem('user')) || {},
    error: '',
  },
  mutations: {
    auth_request(state) {
      state.status = 'loading';
    },
    auth_success(state, data) {
      state.status = 'success';
      state.token = data.token;
      state.user = data.user;
    },
    auth_error(state) {
      state.status = 'error';
    },
    api_error(state, err) {
      state.status = 'api_error';
      state.error = err;
    },
    app_error(state, err) {
      state.status = 'app_error';
      state.error = err;
    },
    logout(state) {
      state.status = '';
      state.token = '';
    },
  },
  getters: {
    isLoggedIn: state => !!state.token,
    authStatus: state => state.status,
    err: state => state.error,
    user: state => state.user,
  },
  actions: {

    login({
      commit,
    }, user) {
      return new Promise((resolve, reject) => {
        commit('auth_request');
        axios({ url: `${process.env.VUE_APP_API_URL}/auth/login`, data: user, method: 'POST' }).then((resp) => {
          const token = `Bearer ${resp.data.token}`;
          const user = resp.data.user;
          localStorage.setItem('token', token);
          localStorage.setItem('user', JSON.stringify(user));
          axios.defaults.headers.common.Authorization = token;
          commit('auth_success', { token, user });
          resolve(resp);
        }).catch((err) => {
          if (err.response.status === 403) {
            commit('auth_error');
          } else {
            commit('api_error', err.toString());
          }
          localStorage.removeItem('token');
          localStorage.removeItem('user');
          reject(err);
        });
      });
    },
    logout({ commit }) {
      return new Promise((resolve, reject) => {
        commit('auth_request');
        axios({ url: `${process.env.VUE_APP_API_URL}/auth/logout`, method: 'POST' }).then((resp) => {
          commit('logout');
          localStorage.removeItem('token');
          localStorage.removeItem('user');
          delete axios.defaults.headers.common.Authorization;
          resolve(resp);
        }).catch((err) => {
          commit('auth_error');
          localStorage.removeItem('token');
          localStorage.removeItem('user');
          reject(err);
        });
      });
    },
    invalidateSession({ commit }) {
      return new Promise((resolve, reject) => {
        commit('logout');
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        delete axios.defaults.headers.common.Authorization;
        resolve();
      });
    },
    apiError({
      commit,
    }, err) {
      return new Promise((resolve, reject) => {
        commit('api_error', err.toString());
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        delete axios.defaults.headers.common.Authorization;
        resolve();
      });
    },
    authError({
      commit,
    }, err) {
      return new Promise((resolve, reject) => {
        commit('auth_error', err.toString());
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        delete axios.defaults.headers.common.Authorization;
        resolve();
      });
    },
    appError({
      commit,
    }, err) {
      return new Promise((resolve, reject) => {
        commit('app_error', err.toString());
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        delete axios.defaults.headers.common.Authorization;
        resolve();
      });
    },
  },
});
