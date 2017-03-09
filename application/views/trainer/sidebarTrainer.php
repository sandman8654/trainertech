<aside>
        <div class="left-nav">
            <ul>
                <!-- <li class="multi-item">
                    <a href="javascript:;" class="dcjq-parent">

                            Add Tra
                        inee
                    </a>
                    <ul class="sub">
                        <li class="multi-item">
                            <a href="javascript:;" class="dcjq-parent">Mustangs</a>
                            <ul class="sub">
                                <li><a href="#">- Quarterbacks</a></li>
                                <li><a href="#">- Runningbacks</a></li>
                                <li><a href="#">- O Linemen</a></li>
                                <li><a href="#">- Wide Recievers</a></li>
                                <li><a href="#">- Tight Ends</a></li>
                                <li><a href="#">- D Linemen</a></li>
                                <li><a href="#">- Cornerbacks</a></li>
                                <li><a href="#">- Safteys</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Private Client</a></li>
                        <li><a href="#">Add Group</a></li>
                    </ul>
                </li> -->
                <li><a <?php if($this->uri->segment(1).'/'.$this->uri->rsegment(2) == 'trainer/index'){ echo 'class="active"'; } ?> href="<?php echo base_url() ?>trainer">
                        <img src='<?php echo base_url() ?>assets/images/dashboard.png' width='20' style='margin-right:10px;' >
                        Dashboard
                    </a>
                </li>
                <li><a <?php if($this->uri->segment(1) == 'workout'){ echo 'class="active"'; } ?> href="<?php echo base_url() ?>workout/all">
                        <img src='<?php echo base_url() ?>assets/images/progress.png' width='20' style='margin-right:10px;' >
                        Manage Workout
                    </a>
                </li>
                <li><a <?php if($this->uri->segment(1) == 'circuit'){ echo 'class="active"'; } ?> href="<?php echo base_url() ?>circuit/all">
                        <img src='<?php echo base_url() ?>assets/images/circuit.png' width='20' style='margin-right:10px;' >
                        Manage Circuits
                    </a>
                </li>
                <li><a <?php if($this->uri->segment(1) == 'exercise'){ echo 'class="active"'; } ?> href="<?php echo base_url() ?>exercise/all">
                        <img src='<?php echo base_url() ?>assets/images/progress.png' width='20' style='margin-right:10px;' >
                        Manage Exercises
                    </a>
                </li>
                <li><a <?php if($this->uri->segment(1) == 'trainer_gallery'){ echo 'class="active"'; } ?> href="<?php echo base_url() ?>trainer_gallery/all">
                        <!-- <img src='<?php echo base_url() ?>assets/images/progress.png' width='20' style='margin-right:10px;' > -->
                        <i class="fa fa-image" style="font-size:16px;"></i>
                        Manage Images
                    </a>
                </li>
                <li><a <?php if($this->uri->segment(1).'/'.$this->uri->rsegment(2) == 'trainer/manage_trainee'){ echo 'class="active"'; } ?> href="<?php echo base_url() ?>trainer/manage_trainee">
                        <img src='<?php echo base_url() ?>assets/images/profile.png' width='20' style='margin-right:10px;' >
                        Manage Trainees
                    </a>
                </li>
                <li><a <?php if($this->uri->segment(1) == 'group'){ echo 'class="active"'; } ?> href="<?php echo base_url() ?>group/all">
                        <img src='<?php echo base_url() ?>assets/images/users.png' width='20' style='margin-right:10px;' >
                        Manage Groups
                    </a>
                </li>
                <li>
                    <a <?php if($this->uri->segment(1).'/'.$this->uri->rsegment(2) == 'support/trainee_queries'){ echo 'class="active"'; } ?> href="<?php echo base_url() ?>support/trainee_queries">
                        <img src='<?php echo base_url() ?>assets/images/chat.png' width='20' style='margin-right:10px;' >
                        Trainee Queries <?php if(count_unread_support()) echo '( '.count_unread_support().' )'; ?>
                    </a>
                </li>
                <li><a <?php if($this->uri->segment(1).'/'.$this->uri->rsegment(2) == 'support/all'){ echo 'class="active"'; } ?> href="<?php echo base_url() ?>support/all">
                        <img src='<?php echo base_url() ?>assets/images/globe.png' width='20' style='margin-right:10px;' >
                        Support
                    </a>
                </li>
            </ul>
        </div>
</aside>
<script type="text/javascript">
    // LEFT BAR ACCORDION
    $(function() {
        $('.left-nav > ul').dcAccordion({
            eventType: 'click',
            autoClose: true,
            saveState: true,
            disableLink: true,
            speed: 'slow',
            showCount: false,
            autoExpand: true,
            // cookie: 'dcjq-accordion-1',
            classExpand: 'dcjq-current-parent'
        });
    });
</script>