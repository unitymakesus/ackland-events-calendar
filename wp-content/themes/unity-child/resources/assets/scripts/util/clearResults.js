/**
 * Remove results for fresh request.
 */
const clearResults = (wrapper, row) => {
  wrapper.attr('data-paged', 0);
  row.empty();
  $('#js-posts-load-more').addClass('d-none');
  $('#ajaxLoading').show();
}

export default clearResults;
