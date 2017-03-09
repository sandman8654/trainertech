
        <section id="big-slider" class="big-slider sl-slider-wrapper">
            <div class="sl-slider">
                <?php if ($slides): foreach($slides as $slide){ ?>
                    <div class="sl-slide" data-orientation="horizontal" data-slice1-rotation="-25" data-slice2-rotation="-25" data-slice1-scale="2" data-slice2-scale="2">
                    <div class="sl-slide-inner">
                        <div class="bg-img" style="background-image:url('<?php echo base_url() ?>assets/uploads/slider/<?php echo $slide->image; ?>')"></div>
                        <div class="container">
                        <div class="slide-header">
                                <h1><?php echo $slide->headline; ?></h1>
                                <h6><?php echo $slide->sub_headline; ?></h6>
                            </div>
                        
                        <a class="learn-more blue-slider" href="<?php echo $slide->btn_link ?>"><span class="icon-chevron-right"></span>LEARN MORE</a>
                    </div>
                    </div>
                </div>    
                <?php } endif; ?>
                
                <!-- <div class="sl-slide" data-orientation="vertical" data-slice1-rotation="-25" data-slice2-rotation="-25" data-slice1-scale="2" data-slice2-scale="2">
                    <div class="sl-slide-inner">                      
                        <div class="bg-img bg-img-2"></div>
                        <div class="container">
                        <div class="slide-header">
                                <h1>Invest in Real Estate <br>the Easy Way</h1>
                                <h6>Lorem ipsum dolor sit amet, consectetur adipisicing <br> elit, sed do eiusmod tempor incididunt ut labore et<br> dolore magna aliqua. </h6>
                            </div>
                        
                        <a class="learn-more blue-slider" href="#"><span class="icon-chevron-right"></span>LEARN MORE</a>
                        </div>
                    </div>
                </div>  -->
            </div>
            <nav id="nav-arrows" class="slider-nav">
                <div class="container">
                    <span class="slide-link"><i class="icon-angle-left"></i>SERVICES</span>
                    <span class="slide-link">investing the easy way<i class="icon-angle-right"></i></span>
                </div>
            </nav>
        </section>

        <section class="four-item">
            <div class="container">
                <div class="row services">
                    <div class="col-md-3 col-sm-6">
                        <img src="<?php echo base_url() ?>assets/theme/img/houses.png">
                        <h4>TURN KEE REAL<br> ESTATE INVESTMENT</h4>
                        <div class="border-horizontal"></div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.  </p>
                        <button class="btn lern-more">LEARN MORE</button>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <img src="<?php echo base_url() ?>assets/theme/img/antena.png">
                        <h4>WHU INVEST IN<br> CHICAGO</h4>
                        <div class="border-horizontal"></div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quisea.   </p>
                        <button class="btn lern-more">LEARN MORE</button>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <img src="<?php echo base_url() ?>assets/theme/img/home.png">
                        <h4>INVESTMENT<br> PROPERTY<br> MANAGMENT</h4>
                        <div class="border-horizontal"></div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.  </p>
                        <button class="btn lern-more">LEARN MORE</button>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <img src="<?php echo base_url() ?>assets/theme/img/list.png">
                        <h4>PENTAL<br> INVENTORY</h4>
                        <div class="border-horizontal"></div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. nisi ut aliquip ex ea commodo.  </p>
                        <button class="btn lern-more">LEARN MORE</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="neibor sm-inf" style="background-image:url('<?php echo base_url() ?>assets/uploads/home/<?php echo $neighbor->image ?>')">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <h3><?php echo $neighbor->title ?></h3>
                        <div class="small-bord"></div>
                       <p><?php echo $neighbor->excerpt; ?></p>
                       <span id="desc" style="display:none"><?php echo $neighbor->description; ?></span>
                        <button class="btn lern-more" id="ngbr_more">LEARN MORE</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="f-listing">
            <h1 class="text-center">FEAUTURED LISTINGS</h1>
        </section>

        <section class="listing">
            <div class="container">
                <div class="row">
                    <?php if ($listing): foreach($listing as $property){ ?>
                    <div class="col-sm-4">
                        <a href="<?php echo base_url() ?>listings/detail/<?php echo $property->slug ?>"><img src="<?php echo base_url() ?>assets/uploads/properties/<?php echo $property->featured_image; ?>" width='100%'></a>
                        <h4> <?php echo $property->title ?></h4>
                        <p><?php echo $property->excerpt ?></p>
                    </div>
                    <?php } endif; ?>
                    <!-- <div class="col-sm-4">
                        <img src="<?php echo base_url() ?>assets/theme/img/listing1.png">
                        <h4>2295 S. MAJOR AVE</h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    </div>
                    <div class="col-sm-4">
                        <img src="<?php echo base_url() ?>assets/theme/img/listing2.png">
                        <h4>3288 N. GREEN</h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                    </div>
                    <div class="col-sm-4">
                        <img src="<?php echo base_url() ?>assets/theme/img/listing3.png">
                        <h4>4236 N. CHICAGO</h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt.</p>
                    </div> -->
                </div>
                <div class="text-center m-t-lg"><a href="<?php echo base_url() ?>listings" class="btn lern-more">VIEW ALL LISTINGS</a></div>
            </div>
        </section>

        <style type="text/css">
            .sl-slide .slide-header{
                margin-top: 18%;
            }
        </style>
        