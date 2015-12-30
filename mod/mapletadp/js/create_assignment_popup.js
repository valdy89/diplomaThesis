/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require(['jquery'], function ($) {

    $(document).ready(function () {
        $('#startLink').click(function () {
            window.open("http://fryval.cz/mod/mapletadp/view.php?id="+$('input:hidden[name=id]').val()+"&run=true", "popupWindow", "width=1024,height=650,scrollbars=yes");
        });

    });
});