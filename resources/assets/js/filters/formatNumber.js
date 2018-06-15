const numeral = require("numeral");

require('vue').filter("formatNumber", function (value, format = '$0.00') {
    return numeral(value).format(format);
});