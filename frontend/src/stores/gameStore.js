import { defineStore } from 'pinia'

export const useGameStore = defineStore('game', {
  state: () => ({
    gameState: 'playing',
    currentLat: null,
    currentLon: null,
    actualLat: '48.6899712',
    actualLon: '6.1820983',

    distance: 0,
    distanceKm: 0,

    maxDistance: 10000, //GAME INIT

    score: 0,
    scores: [], // NE PAS PERSISTE
    totalScore: 0, // DOIT PERSISTE

    timeLeft: 300000, //GAME INIT
    timerStarted: false, //GAME INIT

    images: ['../assets/images/1.jpg', '../assets/images/2.jpg', '../assets/images/3.jpg', '../assets/images/4.jpg'],
    imagesPlayed: [],
    imagesLeft: 10, //GAME INIT
    imageLat: '48.6899712',
    imageLon: '6.1820983',

    seriePlayed: 'Nancy et ses environs',
    serieLat: '48.6899712',
    serieLon: '6.1820983',

    defaultLat: '48.866667',
    defaultLon: '2.333333',

    hasPlayed: false,

  }),
  actions:{
    resetGame(){
      this.gameState = 'playing';
      this.currentLat = null;
      this.currentLon = null;
      this.distance = 0;
      this.distanceKm = 0;
      this.score = 0;
      this.scores = [];
      this.totalScore = 0;
      this.timeLeft = 0;
      this.timerStarted = false;
      this.imagesPlayed = [];
      this.imagesLeft = 0;
      this.hasPlayed = false;
    }
  },
});
