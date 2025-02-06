import axios from "axios";

export default {

  install: (app, {baseUrl}) => {
    app.config.globalProperties.$api = () => {
      return axios.create({
        baseURL: baseUrl,
        headers: {
          "Content-type": "application/json",
        },
      });
    };
  }
};
