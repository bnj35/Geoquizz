import { defineStore } from 'pinia'

export const useGameStore = defineStore('game', {
  state: () => ({
    gameId: null,
    gameName: null,
    gameState: null,
    currentLat: null,
    currentLon: null,
    actualLat: '48.6899712',
    actualLon: '6.1820983',

    distance: null,
    distanceKm: null,

    maxDistance: null, //GAME INIT

    score: 0,
    scores: [], // NE PAS PERSISTE
    totalScore: 0, // DOIT PERSISTE

    timeLeft: 0, //GAME INIT
    timerStarted: false, //GAME INIT

    images: [],
    imagesLeft: 0, //GAME INIT

    themePlayed: 'Nancy et ses environs',

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
