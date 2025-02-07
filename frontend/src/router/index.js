import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import GameView from '../views/GameView.vue'
import GameRecapView from "../views/GameRecapView.vue";
import SignupView from '../views/SignupView.vue'
import SigninView from '../views/SigninView.vue'
import ProfileView from '../views/ProfileView.vue'
import CreatePartieView from "../views/createPartieView.vue";
import UserStatsView from "../views/UserStatsView.vue";
import GameoverView from "../views/GameoverView.vue";
import {useUserStore} from "@/stores/userStore.js";

const router = createRouter({

  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      beforeEnter: (to, from, next) => {
        if (useUserStore().user_token === null) {
          next({ name: 'signin' });
        } else {
          next();
        }
      },
      path: '/createpartie',
      name: 'createpartie',
      component: CreatePartieView,
    },
    {
      beforeEnter: (to, from, next) => {
        if (useUserStore().user_token === null) {
          next({ name: 'signin' });
        } else {
          next();
        }
      },
      path: '/game',
      name: 'game',
      component: GameView,
    },
    {
      beforeEnter: (to, from, next) => {
        if (useUserStore().user_token === null) {
          next({ name: 'signin' });
        } else {
          next();
        }
      },
      path: '/gamerecap',
      name: 'gamerecap',
      component: GameRecapView,
    },
    {
      beforeEnter: (to, from, next) => {
        if (useUserStore().user_token === null) {
          next({ name: 'signin' });
        } else {
          next();
        }
      },
      path: '/gameover',
      name: 'gameover',
      component: GameoverView,

    },
    {
      path: '/signup',
      name: 'signup',
      component: SignupView,
    },
    {
      path: '/signin',
      name: 'signin',
      component: SigninView,
    },
    {
      beforeEnter: (to, from, next) => {
        if (useUserStore().user_token === null) {
          next({ name: 'signin' });
        } else {
          next();
        }
      },
      path: '/profile',
      name: 'profile',
      component: UserStatsView,
    },
  ],
})

export default router
