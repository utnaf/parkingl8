const moment = require("moment");

moment.locale('it');
require('vue').filter("formatDate", function (value, format='LLL') {
    return moment(value).format(format);
});