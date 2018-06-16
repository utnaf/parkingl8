const moment = require("moment");

moment.locale(window.config.locale.current);
require('vue').filter("formatDate", function (value, format='LLL') {
    return moment(value).format(format);
});