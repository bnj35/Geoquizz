import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import GameView from '../views/GameView.vue'
import GameRecapView from "../views/GameRecapView.vue";
import SignupView from '../views/SignupView.vue'
import SigninView from '../views/SigninView.vue'
import ProfileView from '../views/ProfileView.vue'
import CreatePartieView from "../views/createPartieView.vue";
import UserStatsView from "../views/UserStatsView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/userstats',
      name: 'userstats',
      component: UserStatsView,
    },
    {
      path: '/createpartie',
      name: 'createpartie',
      component: CreatePartieView,
    },
    {
      path: '/game',
      name: 'game',
      component: GameView,
    },
    {
      path: '/gamerecap',
      name: 'gamerecap',
      component: GameRecapView,
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
      path: '/profile',
      name: 'profile',
      component: ProfileView,
    },
  ],
})

export default router
