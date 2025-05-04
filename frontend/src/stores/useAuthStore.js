import {ref, computed} from "vue"
import apiClient from "@/services/apiClient";
import {defineStore} from "pinia";

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null);
  const isLoggedIn = computed(() => !!user.value);
  const getUser = async function () {
    await apiClient.get('user/me').then((response) => {
      user.value = response.data;
    })
  }
  const login = async function (credentials) {
    const response = await apiClient.post('login', credentials);
    console.log(response)
    localStorage.setItem('authToken', response.data.token);

    await getUser();
  }
  const register = async function (credentials) {
    await apiClient.post('register', credentials);
  }
  const logout = async function () {
    await apiClient.post('user/logout');
    user.value = null;
    localStorage.removeItem('authToken');
  }
  return { user, isLoggedIn, getUser, login, register, logout }
}, {persist: true});
