<aside>
        <div class="left-nav">
            <ul>
                <li><a href="<?php echo base_url() ?>manager"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li><a href="<?php echo base_url() ?>manager/manage_trainer"><i class="fa fa-users"></i>Manage Trainers</a></li>
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