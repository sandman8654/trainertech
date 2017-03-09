				</section>
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
				      	content_height = window_height - footer_height - 20
				        $('.contant').css('min-height',content_height+'px');
				        $('#footer').css('margin-top','0px');				        
				        $('#footer').css('bottom','0px');				        
				        console.log(window_height);            
				      }

				</script>
	  </body>
</html>