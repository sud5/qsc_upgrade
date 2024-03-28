<?php ?>
<script>
 // video play
          function playYoutubeBytime(exists,prev_data_caption_line_time){


            var palyTimeInSecArray = prev_data_caption_line_time.split(":");
            var palyTimeInSec = parseInt(palyTimeInSecArray[0]) * 60 + parseInt(palyTimeInSecArray[1]);


            if(exists){
              var src = $('#player').attr('src');
              var videoURLArr = src.split("?");
              //var startVideoID = videoURLArr[0]+"?start="+palyTimeInSec+"&autoplay=1&"+videoURLArr[1];
              var startVideoID = videoURLArr[0]+"?start="+palyTimeInSec+"&autoplay=1&rel=0&enablejsapi=1";
              $('#player').attr('src',startVideoID);
$("#lvideo").hide();
              $("#player").show();
              $(".video-overflow").show();

            }


          } // end function
$(window).on("load", function() {

          var prevSearchedKey = localStorage.getItem("searchedKey");
          var pagenum = "<?=$pagenum?>";

          console.log(prevSearchedKey+" Window load");
          console.log(pagenum+" Window pagenum");
          console.log(localStorage.getItem("data-desc"));
          if(localStorage.getItem("data-desc") == ''){

          var prev_data_time = localStorage.getItem("data-time");
          var prev_data_caption_line_time = localStorage.getItem("data-caption-line-time");
          var prev_data_pagenum = localStorage.getItem("data-pagenum");
 
          if(prevSearchedKey != "" && pagenum > 0 ){

            //$('table#resultTable tbody').html(prevData);
            $("#searchText").val(prevSearchedKey);
            $("#searchDo").click();

            var exists = "<?=$flagYoutube;?>";
            playYoutubeBytime(exists, prev_data_caption_line_time);
          }

         
  }else{
   if(prevSearchedKey != "" && pagenum > 0 ){

            //$('table#resultTable tbody').html(prevData);
$("#lvideo").hide();
            $("#player").show();
              $(".video-overflow").show();
            $("#searchText").val(prevSearchedKey);
            $("#searchDo").click();
     }
  }
  


});



$(document).ready(function(){

 var pagenum = "<?=$pagenum?>";

 if(pagenum)  $('table#resultTable tbody').html("");
  //if(prevData != "") $('table#resultTable tbody').html(prevData);


      $(document).on('click', '#searchDo', function(event) {

        event.preventDefault();

        var searchText = $("#searchText").val();
        searchText = searchText.trim();
        
        if(searchText != null && searchText != ""){
          $("#searchContentTwo").show();
          $('#searchContentTwo').html("<span class='loadingImage' id='lcontentinnersearch'><img src='pix/loading_img.gif'></span>");

          $.ajax({
                url: "<?=$CFG->wwwroot.'/mod/book/searchTranscriptData.php'?>",
                type: 'post',
                data: {searchTextData: searchText, courseid: <?=$course->id?>},
                success: function(response) {
                     $("#searchContent").hide();
                     //$("#searchContentTwo").show();
                     
                     $("#closeSearch").show();

                    //var transUrl = "<?=$CFG->dirroot.'/mod/book/searchTranscriptData.php'?>";
                    if(response != ""){
$("#searchContentTwo").html(response);
                          $('table#resultTable tbody').html(response);
                          localStorage.removeItem("searchedKey");
                          localStorage.setItem("searchedKey", searchText);
                          
                    }else{
$("#searchContentTwo").html("Error Occured");
			}
                }
          });

        }
        else{
          $(".new-search span.success").remove();
          $(".new-search span.error").html("Please enter text.");
        }

    });    
$(document).on('click', '#closeSearch', function(event) {
$("#closeSearch").hide();
$("#searchContentTwo").hide();
$("#searchContent").show();
$("#searchText").val('');


});

$(document).on('click', '.searchedResultLink', function(event) {

      //event.preventDefault();
      //event.stopPropagation();
      //localStorage.clear();
      localStorage.removeItem("data-time");
      localStorage.setItem("data-time", $(this).attr('data-time'));
      localStorage.removeItem("data-caption-line-time");
      localStorage.setItem("data-caption-line-time", $(this).attr('data-caption-line-time'));
      localStorage.removeItem("data-pagenum");
      localStorage.setItem("data-pagenum", $(this).attr('data-pagenum'));
      localStorage.setItem("data-desc",'');

});

$(document).on('click', '.searchedResultLinktwo', function(event) {

      //event.preventDefault();
      //event.stopPropagation();
      //localStorage.clear();
      localStorage.removeItem("data-desc");
      localStorage.setItem("data-desc", $(this).attr('data-desc'));
      localStorage.removeItem("data-pagenum");
      localStorage.setItem("data-pagenum", $(this).attr('data-pagenum'));

});





$(document).on('click', '.caption-line', function(event) {
    var exists = "<?=$flagYoutube;?>";
    var videoTime = $(this).find('.caption-line-time').text();
    videoTime = videoTime.trim();
    playYoutubeBytimeOnclick(exists,videoTime);
        

});


 // video play
          function playYoutubeBytimeOnclick(exists,videoTime){


            var palyTimeInSecArray = videoTime.split(":");
            var palyTimeInSec = parseInt(palyTimeInSecArray[0]) * 60 + parseInt(palyTimeInSecArray[1]);


            if(exists){
              var src = $('#player').attr('src');
              var videoURLArr = src.split("?");
              //var startVideoID = videoURLArr[0]+"?start="+palyTimeInSec+"&autoplay=1&"+videoURLArr[1];
              var startVideoID = videoURLArr[0]+"?start="+palyTimeInSec+"&autoplay=1&rel=0&enablejsapi=1";
              $('#player').attr('src',startVideoID);
            }

          } // end function





});
</script>



