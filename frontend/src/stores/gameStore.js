import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useGameStore = defineStore('game', () => {
  //Parties
  const gameState = ref('playing')
  const currentLat = ref(null)
  const currentLon = ref(null)
  const actualLat = ref('48.6899712')
  const actualLon = ref('6.1820983')
  const distance = ref(0)
  const score = ref(0)
  const timeLeft = ref(30) //Voir en fonction des différentes series
  const attemptsLeft = ref(10) //Voir en fonction des différentes series

  //Series
  const seriePlayed = ref('Nancy et ses environs') //Voir en fonction des différentes series
  const serieLat = ref('48.6899712')
  const serieLon = ref('6.1820983')

  const imageLat = ref('48.6899712')
  const imageLon = ref('6.1820983')

  return { gameState, currentLat, currentLon, actualLat, actualLon, distance, score, serieLat, serieLon, timeLeft, attemptsLeft, seriePlayed, imageLat, imageLon }
})
