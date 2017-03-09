<?php
	if(!$results){
		$results['count'] = 0;
	}
?>
<header class="page-description">
			<div class="container text-center">
				<h1></h1>
				<p></p>

			</div>

		</header>


		  
		<section class="sm-inf">
		  <div class="container">
			<p style="margin-bottom:0px; margin-top:5%; color:black"><?php echo $results['count']; ?> RESULT<?php if($results['count'] > 1) echo 'S'; ?>  FOUND</p>
				<?php if($results['count'] != 0){ ?>
					<?php foreach($results['listings'] as $row){ ?>
					<div class="row">
						<a href="<?php echo base_url().'listings/detail/'.$row->slug ?>">
						<div class="col-sm-9 ">
							<h3> <?php echo $row->title ?> <!-- <span class="pull-right" style="font-size:14px"></span> --></h3>
							<div class="small-bord" ></div>                        
							<p><span><?php echo $row->excerpt ?></span></p>                    </div>
					</div>
					</a>
					<?php } ?>
				<?php }else{ ?>
					<div class="row">
						<div class="col-sm-9 ">
							<h3>NO RESULTS FOUND.</h3>
						</div>
				<?php } ?>
			</div>
		</section>
	
		<style type="text/css">
			.about .sm-inf h3{
				letter-spacing: 0px;
			}

			#search{
				background-color: #E0B705;
				float: right;
				padding: 8px 10px 9px;
				width: 50px;
				margin-left: 100px
			}

			#srctoggle{
				display: block
			}

			#search form{

			}
		</style>



		<!-- <section class="sm-inf street">

			<div class="container">

				<div class="row">

					<div class="col-sm-9 col-sm-offset-3">

						<h3>WHY CHOOSE TURNKEY</h3>

						<div class="small-bord"></div>

						<h5>Nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, </h5>

						<p>magni dolores eos, qui ratione voluptatem sequi nesciunt, neque dolor. Nemo enim ipsam voluptatem, polores eos. qui ratione voluptatem sequi nesciunt, neque dolor. Nemo enim ipsam voluptatem, polores eos. Nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt.</p>

					</div>

				</div>

			</div>

		</section>



		<section class="sm-inf lock">

			<div class="container">

				<div class="row">

					<div class="col-sm-9">

						<h3>OUR DEDICATION FOR YOUR INVESTMENTS</h3>

						<div class="small-bord"></div>

						<h5>Nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, </h5>

						<p>magni dolores eos, qui ratione voluptatem sequi nesciunt, neque dolor. Nemo enim ipsam voluptatem, polores eos. qui ratione voluptatem sequi nesciunt, neque dolor. Nemo enim ipsam voluptatem, polores eos. Nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt.</p>

					</div>

				</div>

			</div>

		</section>



		<section class="sm-inf tablet">

			<div class="container">

				<div class="row">

					<div class="col-sm-9 col-sm-offset-3">

						<h3>PIONEERING REAL ESTATE INVESTMENT</h3>

						<div class="small-bord"></div>

						<h5>Nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, </h5>

						<p>magni dolores eos, qui ratione voluptatem sequi nesciunt, neque dolor. Nemo enim ipsam voluptatem, polores eos. qui ratione voluptatem sequi nesciunt, neque dolor. Nemo enim ipsam voluptatem, polores eos. Nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt. </p>

					</div>

				</div>

			</div>

		</section> -->

<input type="hidden" id="total_results" value="<?php echo $results['count']; ?>">
<input type="hidden" id="flag" value="1">

<script type="text/javascript">
	$(document).ready(function(){
		$(window).scroll(function(){
			if ($("#flag").val() == '0')  {
	            return ;
	        }
	        var wintop = $(window).scrollTop(), docheight = $(document).height(), winheight = $(window).height();
	        var  scrolltrigger = 0.60;
	        if  ((wintop/(docheight-winheight)) > scrolltrigger) {
				$("#flag").val(0); 
				lastAddedLiveFunc();
	        }
	    });
	});

	function lastAddedLiveFunc(){
		var total_results = $('#total_results').val();
		var fetch = $('section.sm-inf div.container > .row').length;
		
		if(total_results <= fetch)
			return;
		
		$.ajax({
			url:'<?php echo base_url() ?>search/load_more',
			type:'post',
			data:{ 's' : '<?php echo $this->input->post("s") ?>' , 'offset' : fetch },
			success : function(resp){
				if(resp != '0'){
					var res = JSON.parse(resp);
					var listings = res.listings;
					var content = '';
					$.each(listings, function(index, value){
						content += '<div class="row">';
						content += '<a href="<?php echo base_url()."listings/detail/" ?>'+value['slug']+'">';
						content += '<div class="col-sm-9">';
						content += '<h3>'+value['title']+'<!-- <span class="pull-right" style="font-size:14px"><?php echo base_url()."listings/detail/" ?>'+value['slug']+'</span> --> </h3>';
						content += '<div class="small-bord" ></div>';                       
						content += '<p><span>'+value['excerpt']+'</span></p></a></div>';
						content += '</div>';
					});
					$("section.sm-inf div.container").append(content);
					fetch = $('section.sm-inf div.container > .row').length;

					if(total_results <= fetch)
						$("#flag").val(0); 
					else
						$("#flag").val(1); 

				}else{
					$("#flag").val(0); 
				}
			}
		});


	}
</script>
		