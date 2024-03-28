$(document).ready(function () {
    $('#countSpeedText').on('change',function () {
        var countSpeedText = $(this). children("option:selected"). val();
        var HtmlInput = '';
        if(countSpeedText != '')
        {
            for (var i = 1; i <= countSpeedText; i++) {
                HtmlInput+= "<div class='form-item clearfix'>" +
                    "<div class='labelleft'>" +
                    "<div class='form-label'>" +
                    "<label>Label <span style='color:red;'>*</span></label></label></div>" +
                    "<div class='form-setting'>" +
                    "<div class='form-text defaultsnext'> " +
                    "<input type='text' size='35' class='slabel' name='label_name[]'' id='label_name_"+i+"'  autocomplete='off' required>" +
                    "</div>" +
                    "<span id='errMessage_"+i+"' class='ferror'></span>" +
                    "</div></div>"
                    +"<div class='textright'>" +
                    "<div class='form-label'>" +
                    "<label>Comment Text <span style='color:red;'>*</span></label></div>" +
                    "<div class='form-setting'>" +
                    "<div class='form-text defaultsnext'>" +
                    "<textarea class='scomment'  name='comment_text[]' id= 'comment_text_"+i+"' cols='50' required></textarea></div><span id='cerrMessage_"+i+"' class='ferror'></span>" +
                    "</div>" +
                    "</div>" +
                    "</div>";
            }
            HtmlInput += '<div class="form-buttons">'+
						'<div class="buttonCenter">'+
						'<input class="form-submit" type="submit" name="submit" value="Submit">'+
						'</div>'+
					'</div>';
        }
        $("#Append_input").html(HtmlInput);
        $('.form-submit').on('submit', function(e) {
            // prevent default submit action
            var x = $("input[name='label_name[]']");

            $(x).each(function(key,val){
                console.log(key);
                skey= key+1;

                if(($.trim($(val).val()).length<=0 ) && ($.trim($("#comment_text_"+skey).val()).length<=0))
                {
                    $("#errMessage_"+skey).html("This field is required").css('color','red');
                    $("#cerrMessage_"+skey).html("This field is required.").css('color','red');
                    e.preventDefault();
                }else if($.trim($(val).val()).length<=0 )
                {
                    $("#errMessage_"+skey).html("This field is required").css('color','red');
                    e.preventDefault();
                }else if($.trim($("#comment_text_"+skey).val()).length<=0)
                {
                    $("#cerrMessage_"+skey).html("This field is required.").css('color','red');
                    e.preventDefault();
                }

            });
            $('#speedform').trigger('submit');
        });
    });


});