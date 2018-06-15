const numeral = require("numeral");

require('vue').filter("formatNumber", function (value, format = '$0.00') {
    return value === null ? '' : numeral(value).format(format);
});