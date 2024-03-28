
<!--Start-added by lakhan-->  
<script type="text/javascript">
$(document).ready(function(){

//1
  var bookTitle = $('#red').find('.main-text').text();
  
//2       
  var descriptionData = $('.descriptionTab1').find('.no-overflow').text();

//3
    var youtubeUrlArray = $("#player").attr("src").split('?')[0];
    var youtubeUrlIdArray = youtubeUrlArray.split('embed/');
    var youtubeUrlImgBook = "http://img.youtube.com/vi/"+youtubeUrlIdArray[1]+"/hqdefault.jpg";

    $('meta[property="og:title"]').attr('content', bookTitle);
    $('meta[property="og:description"]').attr('content', descriptionData);
    $('meta[property="og:image"]').attr('content',youtubeUrlImgBook );

  });
</script>

<!--End-added by lakhan--> 


<script>

  window.fbAsyncInit = function() {
      FB.init({
        //appId: '958169587597310',
        appId: '1878226425726155', 
        status: true, 
        cookie: true,
        xfbml: true
      });
  };
  (function() {
      var e = document.createElement('script'); 
          e.async = true;
          e.src = document.location.protocol +'//connect.facebook.net/en_US/all.js';
          document.getElementById('fb-root').appendChild(e);
  }());

</script>



<script type="text/javascript">
$(document).ready(function(){

  $('#icon_facebook_large').click(function(e){
    //1
    var titleData = $('#red').find('.main-text').text();
  
    //2       
    var descriptionData = $('.descriptionTab1').find('.no-overflow').text();

    //3
    var youtubeVideoUrl = $("#player").attr("src");
    var youtubeUrlArray = $("#player").attr("src").split('?')[0];
    var youtubeUrlIdArray = youtubeUrlArray.split('embed/');
    var youtubeUrlImgBook = "http://img.youtube.com/vi/"+youtubeUrlIdArray[1]+"/0.jpg";

    FB.ui({
      
      title: titleData,
      description: descriptionData,
      method: 'share',
      href:"<?=$CFG->wwwroot?>/?redirect=0",
      picture: youtubeUrlImgBook,

    });
  });


});
</script>
