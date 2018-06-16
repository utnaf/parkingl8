function replace(str, keys) {
    for(let i = 0; i < keys.length; i++) {
        str = str.replace('%', keys[i]);
    }

    return str;
}


require('vue').filter("translate", function (str, ...keys) {
    if (
        window.config.hasOwnProperty('translations')
        && window.config.translations.hasOwnProperty(str)
    ) {
        return replace(window.config.translations[str], keys);
    }

    return str;
});