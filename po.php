<html>
    <head>
      <title>My Facebook Login Page</title>
    </head>
    <body>
      <div id="fb-root"></div>
      <script>
        window.fbAsyncInit = function() {
          FB.init({
            appId      : '416070945072529',
            status     : true, 
            cookie     : true,
            xfbml      : true,
            oauth      : true,
          });
        };
        (function(d){
           var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
           js = d.createElement('script'); js.id = id; js.async = true;
           js.src = "//connect.facebook.net/en_US/all.js";
           d.getElementsByTagName('head')[0].appendChild(js);
         }(document));
      </script>
     <div 
        class="fb-registration" 
        data-fields="[{'name':'name'}, {'name':'email'},
          {'name':'favorite_car','description':'What is your favorite car?',
            'type':'text'}]" 
        data-redirect-uri="http://stpgroupon.com"
      </div>
    </body>
 </html>