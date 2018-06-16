const numeral = require("numeral");

numeral.register('locale', 'it', {
    delimiters: {
        thousands: '.',
        decimal: ','
    },
    ordinal : function (number) {
        return '°';
    },
    currency: {
        symbol: '€'
    }
});

numeral.locale(window.config.locale.current);
require('vue').filter("formatNumber", function (value, format = '$0.00') {
    return value === null ? '' : numeral(value).format(format);
});