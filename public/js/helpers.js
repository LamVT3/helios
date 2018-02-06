/**
 * Created by phongvh on 4/19/2017.
 */

function formatMoney(number) {
    if (!number) return number;
    number = number.toString().replace(/[^\d\.]/g, '');
    number = number.replace(/(\.)(\d*)(\.)/, '$1$2');
    if (number.indexOf('.') !== -1) {
        var part = number.split('.');
        number = part[0].replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.') + ',' + part[1].replace(/(\d)\d(\d)/, '$1$2');
    } else
        number = number.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');

    return number + ' đ';
}

function selectText(containerid) {
    if (document.selection) {
        var range = document.body.createTextRange();
        range.moveToElementText(document.getElementById(containerid));
        range.select();
    } else if (window.getSelection) {
        var range = document.createRange();
        range.selectNode(document.getElementById(containerid));
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
    }
}

function limitCharacters(selector, maxLength) {
    var charLeft = maxLength - $(selector).val().length;

    var inform = '<div class="note">Tối đa ' + maxLength + ' kí tự. Còn lại <span class="char-left">' + (charLeft > 0 ? charLeft : 0) + '</span></div>';

    var callback = function (event) {
        charLeft = maxLength - $(this).val().length;
        /*var keycode = event.which;
        var valid =
            (keycode > 47 && keycode < 58) || // number keys
            keycode == 32 || keycode == 13 || // spacebar & return key(s) (if you want to allow carriage returns)
            (keycode > 64 && keycode < 91) || // letter keys
            (keycode > 95 && keycode < 112) || // numpad keys
            (keycode > 185 && keycode < 193) || // ;=,-./` (in order)
            (keycode > 218 && keycode < 223);   // [\]' (in order)*/

        // console.log(charLeft);
        if (charLeft >= 0)
            $(this).parent().find('.char-left').html(charLeft);
        else $(this).parent().find('.char-left').html(0);
    }

    $(selector).after(inform);

    // $(selector).keydown(callback).keyup(callback);
    $(selector).on('input', callback);
}
