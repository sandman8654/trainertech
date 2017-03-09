<aside>
        <div class="left-nav">
            <ul>
                <li>
                    <a <?php if($this->uri->segment(1) == 'trainee_dashboard'){ echo 'class="active"'; } ?>  href="<?php echo base_url() ?>trainee">
                        <img src='<?php echo base_url() ?>assets/images/dashboard.png' width='20' style='margin-right:10px;' >
                        Dashboard 
                    </a>
                </li>   
                <li>
                    <a <?php if($this->uri->segment(1) == 'profile'){ echo 'class="active"'; } ?>  href="<?php echo base_url(); ?>profile/trainee">
                        <img src='<?php echo base_url() ?>assets/images/profile.png' width='20' style='margin-right:10px;' >
                        Profile
                    </a>
                </li>   
                <li>
                    <a <?php if($this->uri->segment(1) == 'progress'){ echo 'class="active"'; } ?>  href="<?php echo base_url(); ?>progress">
                        <img src='<?php echo base_url() ?>assets/images/progress.png' width='20' style='margin-right:10px;' >
                        Progress
                    </a>
                </li>   
                <li>
                    <a <?php if($this->uri->segment(1).'/'.$this->uri->segment(2) == 'trainee/support'){ echo 'class="active"'; } ?>  href="<?php echo base_url() ?>trainee/support">
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

