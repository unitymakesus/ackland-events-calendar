/**
 * Retrieve data attributes to pass as options to getPosts
 */
const getOptions = () => {
  let wrapper = $('#js-posts-wrapper');
  let data = wrapper.data();
  let options = {};

  Object.entries(data).forEach(([key, value]) => {
    options[key] = value;
  });

  // Remove properties we don't need for WP API request
  delete options.context;

  return options;
};

export default getOptions;
