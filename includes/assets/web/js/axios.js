import axios from "axios";

export const makeRequest = async (
  endpoint,
  params = {},
  method = "GET",
  token = "FEhI30q7ySHtMfzvSDo6RkxZUDVaQ1BBU3lBcGhYS3BrQStIUT09"
) => {
  const baseURL = "/wp-json";
  const api = axios.create({
    baseURL: baseURL,
  });
  const headers = token ? { Authorization: `Bearer ${token}` } : {};

  const config = {
    url: "zippy-pay/v1" + endpoint,
    params: params,
    method: method,
    headers: headers,
  };
  try {
    let res = null;

    res = await api.request(config);
    const data = res.data;
    return { data };
  } catch {
    (error) => {
      if (!error?.response) {
        console.error("❗Error", error.message);
        return { ...error, catchedError: error };
      }

      console.error(error.response.statusText);
      return error;
    };
  }
};
