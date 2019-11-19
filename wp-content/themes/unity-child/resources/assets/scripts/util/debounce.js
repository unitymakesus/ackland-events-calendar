const debounce = (fn, duration) => {
  let timer;

  return function() {
    let context = this;
    let args = arguments;

    clearTimeout(timer);
    timer = setTimeout(() => {
      timer = null;
      fn.apply(context, args);
    }, duration);
  };
}
export default debounce;
