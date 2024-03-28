$(document).ready(function () {
    $('#studentAdminDataTable').DataTable({
        "pageLength": 10,
        "lengthMenu": [[5, 10, 15, 25, -1], [5, 10, 15, 25, "All"]],
        "aoColumnDefs": [
            {"iDataSort": 6, "aTargets": [5]}
        ],
        "processing": true,
        "language": {
            loadingRecords: '&nbsp;',
            processing: '<img src="/local/assign/images/loading.gif">'
        },
        "serverSide": true,
        "ajax":{
            url :"assign_data.php",
            type: "POST",
            error: function(){
                $("#post_list_processing").css("display","none");
            }
        },
        "initComplete": function () {
            dropDownSelect();
        },
        "drawCallback": function() {
            dropDownSelect();
        },
        "order": [[5, 'desc']],
        "search": {
            "smart": true,
        },
        "bSortClasses": false,
        "orderClasses": false,
        "bAutoWidth": false,
        "bProcessing": true,
        "bDeferRender": true,
    });
    function dropDownSelect(){
        $('#studentAdminDataTable select.grader_pulldown').change(function () {
            var graderVal = $(this).val();

            $.ajax({
                url: 'process.php?action=graderdropdown',
                type: 'post',
                data: {grader_val: graderVal},
                success: function (response) {
                    console.log(response);
                    // if(response == 4){
                    //     $("#loading-img").remove();
                    //     alert("Please re-assign a grader on this exam");
                    // }
                    // else{
                    //      $(".overlay").hide();
                    // }
                }
            });


            var ddClass = $(this).parent().attr('class');
            var rowArray = ddClass.split('td_dropdown_');
            if ($.trim($(this).find("option:selected").text()) == 'Administrator') {
                $("#button_" + rowArray[1]).attr('class', 'enable_link');
            } else {
                $("#button_" + rowArray[1]).attr('class', 'disable_link');
            }
        });
    }
});

$(document).on("click", ".disable_link", function (e) {
    e.preventDefault();
    alert('Please change Grader assignment to ‘Administrator’ first, to perform grade.');
});