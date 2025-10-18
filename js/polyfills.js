/**
 * @see https://web.archive.org/web/20201205011737/https://developer.mozilla.org/en-US/docs/Web/API/NodeList/forEach
 */
if (window.NodeList && !NodeList.prototype.forEach) {
    NodeList.prototype.forEach = function (callback, thisArg) {
        thisArg = thisArg || window;
        for (var i = 0; i < this.length; i++) {
            callback.call(thisArg, this[i], i, this);
        }
    };
}

/**
 * @see https://web.archive.org/web/20201205001311/https://developer.mozilla.org/en-US/docs/Web/API/Element/closest
 */
if (!Element.prototype.matches) {
    Element.prototype.matches =
        Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
}

if (!Element.prototype.closest) {
    Element.prototype.closest = function (s) {
        var el = this;
        do {
            if (Element.prototype.matches.call(el, s)) return el;
            el = el.parentElement || el.parentNode;
        } while (el !== null && el.nodeType === 1);
        return null;
    };
}
