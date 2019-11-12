import Macy from 'macy/dist/macy.js';

export default {
  init() {
    if (document.querySelector('.grid') !== null) {
      var macyGrid = Macy({   // eslint-disable-line no-unused-vars
        container: '.grid',
        trueOrder: true,
        columns: 2,
        margin: {
          x: 20,
          y: 30,
        },
        breakAt: {
          767: 1,
        },
      });
    }
  },
  finalize() {
  },
};
