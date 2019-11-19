// import isBreakpoint from '../util/isBreakpoint';
import getPosts from '../util/getPosts';
import getOptions from '../util/getOptions';
import clearResults from '../util/clearResults';
import debounce from '../util/debounce';

/**
 * Retrieve events from WP API endpoint.
 * @param {*} options
 */
let wrapper = $('#js-posts-wrapper');
let row = $('#js-posts-row');
let totalReturned = 0;
function loadPosts(options) {
  let current = parseInt(wrapper.attr('data-paged')) || '';
  let postType = wrapper.attr('data-context') || '';

  // Pass along next pagination to our request.
  options.paged = current + 1;

  getPosts(postType, options)
    .then(({ headers, data }) => {
      console.log(data);

      // let totalPages = headers['x-wp-totalpages'];
      let totalPosts = headers['x-wp-total'];

      data.forEach(post => {
        let featured_image = post.featured_image ? `
          <div class="card__image">
            <img
              class="lazyload"
              data-sizes="auto"
              data-src="${post.featured_image.src}"
              data-srcset="${post.featured_image.src} 1x, ${post.featured_image.src_2x} 2x"
              data-expand="-10"
              alt="${post.featured_image.alt}"
            />
          </div>
          ` : '';

        let card = `
        <div class="card">
          ${featured_image}
          <div class="card__text">
            <h2 class="h5 heading-reset">
              <a href="${post.url}">${post.title}</a>
            </h2>
            <div class="tribe-event-schedule">${post.schedule_details}</div>
            <div class="tribe-event-excerpt">${post.excerpt}</div>
          </div>
        </div>
        `;

        row.append(card);
        totalReturned++;
      });

      $('#ajaxLoading').hide();
      $('#js-posts-load-more')
        .removeClass('d-none')
        .attr('data-finished', totalReturned >= totalPosts);

      if (totalReturned === 0) {
        row.append(`
          <div class='alert alert-info'>
            No results were found. Try refining search.
          </div>`);
      }
    })
    .catch(function(error) {
      console.log(error);
      row.append(`
        <div class='alert alert-danger'>
          Something went wrong.
        </div>`);
    });

  wrapper.attr('data-paged', current + 1);
}

function initPosts() {
  // Initial load / request.
  loadPosts(getOptions());

  /*
   * Combine all the filters for sending to loadPosts().
   */
  function combineFilters() {
    let filters = document.querySelectorAll('fieldset[data-fieldset]');
    let searchQuery = encodeURIComponent(document.getElementById('js-filter-search').value);
    let options = getOptions();

    filters.forEach(filter => {
      let selected = $(filter).find(':checked');
      let key = filter.getAttribute('data-fieldset');
      let values = selected.map(function() {
          return $(this).attr('value');
        })
        .toArray()
        .join();

      if (values.length > 0) {
        options[key] = values;
        // wrapper.data(key, values);
      } else {
        options[key] = values;
        // wrapper.removeData(key, null);
      }

      if (searchQuery) {
        options.s = searchQuery;
      }
    });

    return options;
  }

  /*
   * Wrapper function for clear and load posts.
   */
  function doFiltering(options) {
    clearResults(wrapper, row);
    totalReturned = 0;
    loadPosts(options);
  }

  $('#js-reset-filters').on('click', e => {
    e.preventDefault();
    $('[data-filter]').removeAttr('checked');
    let options = getOptions();
    doFiltering(options);
  });

  /*
   * Button to apply filters (mainly for mobile)
   */
  // $('#jsApplyFilters').on('click', () => {
  //   doFiltering(combineFilters());
  // });

  /*
   * Add a change event for all filters.
   */
  $('[data-filter]').on('change', () => {
    // if (isBreakpoint('xs')) {
    //   return;
    // }

    doFiltering(combineFilters());
  });

  // Load more.
  $('#js-posts-load-more').on('click', e => {
    let options = getOptions();
    e.preventDefault();
    $(e.target).text('loading...');
    loadPosts(options);
  });

  // Search.
  $('#js-filter-search').on('keyup paste', debounce(e => {
    // if (isBreakpoint('xs')) {
    //   return;
    // }

    let searchQuery = encodeURIComponent(document.getElementById('js-filter-search').value);
    let options = getOptions();

    // don't do search if user pressed tab, any arrow, ctrl, apple, alt/opt, shift, caps
    const keysToIgnore = [9, 27, 37, 38, 39, 40, 17, 18, 93, 16, 20];
    if (keysToIgnore.includes(e.which)) {
      return;
    }

    if (searchQuery) {
      options.s = searchQuery;
    }

    doFiltering(options);
  }, 800));
}

export default {
  init() {

  },
  finalize() {
    if ($('#js-posts-wrapper').length > 0) {
      initPosts();
    }
  },
};
