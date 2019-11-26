import axios from 'axios';

/**
 * Request data from a WP API endpoint
 */
const API_URL = '/wp-json/ackland/v1/';

const getPosts = (postType, params) => {
  let endpoint = API_URL + postType;

  console.log(params);

  return axios.get(endpoint, { params });
};

export default getPosts;
