<footer class="footer">
			<div class="container">
				<div class="row f-blocks">
					<div class="col-sm-4 col-lg-3">
						<h4>VISIT US</h4>
						<p>301 S County Farm Rd, <br>Wheaton, IL 60187<br>
						<a href="http://maps.google.com/?q=301 S County Farm Rd, Wheaton, IL 60187" target="_blank">Find us on Google Maps</a></p>
						<h4>CONTACT US</h4>
						<p><a href="callto:(630) 947-0122">(630) 947-0122</a><br><a href="mailto:contact@turnkeyinvestments.com">contact@turnkeyinvestments.com</a></p>
					</div>
					<div class="col-sm-4 col-lg-3">
						<h4>ABOUT & LEGAL</h4>
						<div class="divider"></div>
						<ul>                            
							<li><a href="<?php echo base_url() ?>about"><span>&#10003</span> About Us</a></li>
							<?php $pages = getallpages(); if($pages): foreach($pages as $page): ?>
								<li><a href="<?php echo base_url().'page/'.$page->slug; ?>"><span>&#10003</span> <?php echo $page->title; ?></a></li>
							<?php endforeach; endif; ?>
						</ul>
					</div>
					<div class="col-sm-4 col-lg-3 col-lg-offset-3">
						<h4>LATEST TWEETS</h4>
						<div class="divider"></div>
						<ul class="twett">
							<?php $tweets = get_twitter_feed(); ?>                        
						<?php if ($tweets): foreach ($tweets as $key): ?>                                                    
						<!-- <span class="date"><?php //echo date('M d', strtotime($key->created_at)); ?></span> -->
							<li class="twetts"><?php echo $key->text ?></li>
						<?php endforeach; endif;  ?>                            
						</ul>
					</div>
				</div>
				<div class="row m-t">
					<div class="col-sm-12">
						<h4>FOLLOW US</h4>
						<?php $links = get_social_links(); ?>
						<div class="social">
							<a class="facebook" href="<?php echo $links->facebook ?>"></a>
							<a class="twetter" href="<?php echo $links->twitter ?>"></a>
							<a class="youtube" href="<?php echo $links->youtube ?>"></a>
						</div>
						<span class="copyright">&#169;2013, All Rights Reserved Turnkey Investments</span>
					</div>
				</div>
			</div>
		</footer>
		 
	   
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="<?php echo base_url() ?>assets/theme/assets/bootstrap/js/bootstrap.min.js"></script>     
		<script src="<?php echo base_url() ?>assets/theme/js/script.js"></script>

		<!--responsive menu-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="<?php echo base_url() ?>assets/theme/js/jquery.dlmenu.js"></script>
		<script>
			$(function() {
				$( '#dl-menu' ).dlmenu();

			});

			// $('#myModal').modal();
		</script>

		<script type="text/javascript" src="<?php echo base_url() ?>assets/theme/js/modernizr.custom.79639.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/theme/js/jquery.ba-cond.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/theme/js/jquery.slitslider.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/theme/js/jquery.bxslider.min.js"></script>
		<script type="text/javascript">	
			$(function() {
				 $('.bxslider').bxSlider({
                  infiniteLoop:false,
                  // pager:false,
                  controls:false,
                });
				 
				var Page = (function() {					
					var $navArrows = $( '#nav-arrows' ),
					slitslider = $( '#big-slider' ).slitslider( {
						
					}),
					init = function() {
						initEvents();
					},
					initEvents = function() {
						// add navigation events
						$navArrows.children( ':last' ).on( 'click', function() {
							slitslider.next();
							return false;
						} );
						$navArrows.children( ':first' ).on( 'click', function() {				
							slitslider.previous();
							return false;
						} );  
					};
					return { init : init };
				})();
				Page.init();
			});
		</script>

		<script type="text/javascript">
			$('#ngbr_more').click(function(){
				// alert('here');
				$('#desc').slideDown();
				$(this).hide();
			});
		</script>
		 <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
		<script>
			  // $(document).ready(function() {
			  //   $( "#slider" ).slider({ 
			  //       max: 5000,
			  //       min: 100,
			  //       step: 100,
			  //       value: 1000,
			  //         slide: function( event, ui ) {
			  //           $( ".value" ).text('$' + ui.value  );
			  //           $(".value").css({'left':$('a.ui-state-default').css('left')});
			  //         }
			  //   });
			  //   $( ".value" ).text('$' +  $("#slider" ).slider( "value" )   );
			  //   /*$(".value").text("$" + $("#slider" ).slider("value"));
			  //   var $value = $(".value");*/
			  // });

			$(function() {
	$( "#slider" ).slider({
	  range: true,
	  min: 0,
	  max: 5000,
	  // values: [ 0, '<?php //echo(getMaxprice()->application_fee); ?>' ],
	  values: [ 0, 5000 ],
	  change: function( event, ui ) {
		$( ".value" ).html( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
		$('#pricerange').val(ui.values[ 0 ] + "-" + ui.values[ 1 ]);
		// alert($('#pricerange').val());

		$.ajax({
			type: "POST",
			data: $('#srch').serialize(),
			url: '<?php echo base_url() ?>listings/ajaxfilter_listings/',            
			success: function (data) {                                     
				var res = $.parseJSON(data);                
				  if(res.status !="not found"){                  
					$('#contentbox').html(res.listing);
					$('#location_div').html(res.locations);
					$('#countresult').html(res.count);
				  }else{  
					$('#contentbox').html('Nothing found');                                    
					$('#countresult').html(0);
				  }              
				 
			   }
		  });  
		
	  }
	});
	$( ".value" ).html( "$" + $( "#slider" ).slider( "values", 0 ) +
	  " - $" + $( "#slider" ).slider( "values", 1 ) );
  });
		</script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/theme/css/colorbox.css"/>
<script type="text/javascript" src="<?php echo base_url() ?>assets/theme/js/jquery.colorbox.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".group2").colorbox({
			rel:'group2',
			transition:"fade"
		});
		$(".ajax").colorbox({
			width: '70%',
			height: '60%',
			fixed: true
		});
	});
</script>

	</body>
</html>