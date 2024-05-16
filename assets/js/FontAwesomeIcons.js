const FONTAWESOME_ICONS = {
  _getFontAwesomeIcon(icon_class) {
    return `<i class="fa fa-${icon_class}"></i>`;
  },

  // Icons
  get BOOKMARK() {
    return this._getFontAwesomeIcon('bookmark');
  },

  get COPY() {
    return this._getFontAwesomeIcon('copy');
  },

  get CHART_SIMPLE() {
    return this._getFontAwesomeIcon('chart-simple');
  },

  get CLOCK() {
    return this._getFontAwesomeIcon('clock');
  },

  get DELETE() {
    return this._getFontAwesomeIcon('trash-can');
  },

  get GO_TO_LINK() {
    return this._getFontAwesomeIcon('up-right-from-square');
  },

  get MARK_AS_COMPLETED() {
    return this._getFontAwesomeIcon('check-double');
  },

  get NO_TASK_FOUND() {
    return this._getFontAwesomeIcon('ban');
  },

  get SEE_MORE_ELLIPSIS() {
    return this._getFontAwesomeIcon('ellipsis');
  },

  get NOT_STARTED() {
    return this._getFontAwesomeIcon('square');
  }






}

