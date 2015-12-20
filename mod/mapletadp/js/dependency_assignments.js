/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require(['jquery'], function ($) {

    $(document).ready(function () {
        $('#fitem_id_assignmentId').hide();
        $('#id_classId').change(function () {
            $('#fitem_id_assignmentId').show();
            getAssignments();

        });
    });

    function getAssignments() {
        $.ajax({
            method: "POST",
            url: "/mod/mapletadp/assignment_list.php?classID=" + $('#id_classId').val()
        }).success(function (data) {
            //console.log(data);    
            result_obj = $.parseJSON(data);
            //  console.log(result_obj);
            var list = "";
            $('#id_assignmentId').empty();
            $.each(result_obj, function (i, val) {
                list += '<option value="' + i + '">' + val + '</option>';
            });
            $('#id_assignmentId').empty().append(list);

        });
    }
});