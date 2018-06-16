const moment = require("moment-timezone");

moment.locale(window.config.locale.current);
moment.tz.setDefault(window.config.locale.timezone);
require('vue').filter("formatDate", function (value, format='LLL') {
    return moment(value).format(format);
});