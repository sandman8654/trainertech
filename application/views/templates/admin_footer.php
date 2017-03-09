  <footer id="footer">
        <div class="row">
          <div class="col-sm-12 social">
            <a href="#"><i class="fa fa-facebook-square"></i>Like Us on Facebook</a>
            <a href="#"><i class="fa fa-twitter-square"></i>Follow Us on Twitter</a>
          </div>
        </div>
        <span class="copy">&copy; <?php echo date('Y'); ?> <?php echo PROJECT_NAME ?></span>
        <ul>
          <li><a href="#">Contact</a></li>|
          <li><a href="#">Privacy Policy</a></li>|
          <li><a href="#">Terms of Use</a></li>
        </ul>
      </footer>

      <nav id="menu" style="display:none">
        <ul>
          <li class="Selected"><a href="index.html">Introduction</a></li>
          <li><a href="horizontal-submenus.html">Horizontal submenus example</a></li>
          <li><a href="vertical-submenus.html">Vertical submenus example</a></li>
          <li><a href="photos.html">Photos in sliding panels</a></li>
          <li><a href="positions.html">Positioning the menu</a></li>
          <li><a href="colors.html">Coloring the menu</a></li>
          <li><a href="advanced.html">Advanced example</a></li>
          <li><a href="onepage.html">One page scrolling example</a></li>
          <li><a href="jqmobile/index.html">jQuery Mobile example</a></li>
        </ul>
      </nav>

    </div>   

    <script type="text/javascript">
        $(window).load(function(){            
            footer_position();
        });

            $(window).resize(function(){
              footer_position()
            })
            function footer_position(){
              var window_height = $(window).height();
              footer_height = $('#footer').height();
              content_height = window_height - footer_height - 20;
              $('.contant').css('min-height',content_height+'px')  
              $('#footer').css('margin-top','0px');
              $('form').css('padding-bottom','10px');

              // console.log(window_height);
              // console.log('------');
              // console.log(footer_height);
              // console.log('------');
              // console.log(content_height);              
            }

      </script> 

  </body>
</html>