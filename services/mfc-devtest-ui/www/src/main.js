import Vue from 'vue';
import VueRouter from 'vue-router';

import Axios from 'axios';
import BootstrapVue from 'bootstrap-vue';
import App from './components/App.vue';
import Home from './components/Home.vue';
import Login from './components/Login/Login.vue';
import Companies from './components/Company/CompanyList.vue';
import CompanyForm from './components/Company/CompanyForm.vue';
import Employees from './components/Employee/EmployeeList.vue';
import EmployeeForm from './components/Employee/EmployeeForm.vue';
import Users from './components/User/UserList.vue';
import UserForm from './components/User/UserForm.vue';

import store from './store';

import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import 'vue2-dropzone/dist/vue2Dropzone.min.css'

Vue.config.productionTip = false;
Vue.use(VueRouter);

Vue.prototype.$http = Axios;
const token = localStorage.getItem('token');
if (token) {
  Vue.prototype.$http.defaults.headers.common.Authorization = token;
}

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home
  }, {
    path: '/login',
    name: 'login',
    component: Login
  }, {
    path: '/companies',
    meta: {
      requiresAuth: true
    },
    name: 'companies',
    component: Companies
  }, {
    path: '/companies/create',
    meta: {
      requiresAuth: true
    },
    name: 'create_company',
    component: CompanyForm
  }, {
    path: '/companies/edit',
    meta: {
      requiresAuth: true
    },
    name: 'edit_company',
    component: CompanyForm,
    props: route => ({
      id: Number(route.query.id)
    })
  }, {
    path: '/employees',
    meta: {
      requiresAuth: true
    },
    name: 'employees',
    component: Employees
  }, {
    path: '/employees/edit',
    meta: {
      requiresAuth: true
    },
    name: 'edit_employee',
    component: EmployeeForm,
    props: route => ({
      id: Number(route.query.id)
    })
  }, {
    path: '/employees/create',
    meta: {
      requiresAuth: true
    },
    name: 'create_employee',
    component: EmployeeForm
  }, {
    path: '/users',
    meta: {
      requiresAuth: true
    },
    name: 'users',
    component: Users
  }, {
    path: '/users/edit',
    meta: {
      requiresAuth: true
    },
    name: 'edit_user',
    component: UserForm,
    props: route => ({
      id: Number(route.query.id)
    })
  }, {
    path: '/users/create',
    meta: {
      requiresAuth: true
    },
    name: 'create_user',
    component: UserForm
  },
];

const router = new VueRouter({mode: 'history', routes});

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (store.getters.isLoggedIn) {
      next();
      return;
    }
    next('/login');
  } else {
    next();
  }
});

new Vue({
  router,
  render: h => h(App),
  store
}).$mount('#app');
