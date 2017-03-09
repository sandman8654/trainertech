


<!-- <section class="contant"> -->
  <?php echo alert(); ?>   
  <h4>Edit System Exercise</h4>
  <?php echo form_open_multipart(current_url(), array('class'=>'tasi-form')); ?>
     <div class="form-group">
      <label for="exampleInputEmail1">Name *</label> 
      <input type="text" class="form-control" name="name" id="" value="<?php echo $exercise->name; ?>" placeholder="Enter name">
      <span class="error"><?php echo form_error('name'); ?></span>
    </div>

    <div class="form-group">
      <label for="exampleInputEmail1">Image *</label> 
      <input type="file" name="exercise_image">   
      

      <?php if ($galleryimages): ?>
        <a href="javascript:void(0)" class="showgallery_w">
          or Select Image From Library
          <input type="hidden" value="<?php echo $exercise->image ?>" name="ex_img" id="ex_img">
        </a>              
        <?php if (!empty($exercise->image)): ?>
        <div class="showimage">
          <img src="<?php echo BUCKET_PATH.$exercise->image ?>" style="width:100px;">              
          <a href="javascript:void(0);" id="rem_img">Remove</a>
        </div>
      <?php else: ?>
        <div class="showimage" style="display:none"></div>
      <?php endif; ?>
      <?php endif; ?>

    </div>

    <div class="form-group">
      <label for="">Description *</label>
      <textarea class="form-control" name="description" rows="10"><?php echo $exercise->description; ?></textarea>
      <span class="error"><?php echo form_error('description'); ?></span>
    </div>
    <br>
    <input type="submit" class="btn btn-primary" value="Update">
  </form> 
<br>  
<!-- </section> -->
<div class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="remove_hasclass()" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Select Image</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php if ($galleryimages): $i =1; foreach($galleryimages as $row): ?>  
                    <div class="col-sm-4" style="text-align:center; margin-bottom:10px">
                        <div class="checkbox">
                            <!-- <label > -->
                            <img src="<?php echo BUCKET_PATH.$row->image ?>" alt="" style="max-width:100%">
                            <input name="galleryimage"  value="<?php echo $row->image ?>" type="radio" value="" style="display:inline">
                            <!-- </label> -->
                        </div>
                    </div>
                    <?php if($i%3 == 0){ ?>              
                </div>
                <div class="row">
                    <?php } ?>
                    <?php $i++; endforeach; endif; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="closemmodal" onclick="remove_hasclass()" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submit" onclick="return selectgallery()">Select</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script type="text/javascript">
  $(document).on('click','.showgallery_w',function(){
    
    if($("#ex_img").val() == ''){
      $('input[name=galleryimage]:checked').prop('checked', false);
    }

    $('.modal').modal();                
    $(this).find('#ex_img').addClass('hasimage');
  });
    
  function selectgallery(){
    var val = $('input[name=galleryimage]:checked').val();
    $(document).find('.hasimage').val(val);
    var alink = $(document).find('.hasimage').parent();
    alink.parent().find('.showimage').show();

    var el = '<img src="<?php echo BUCKET_PATH ?>'+val+'" style="width:100px">';

    el += '<a href="javascript:void(0);" id="rem_img">Remove</a>';

    alink.parent().find('.showimage').html(el);
    $('#closemmodal').trigger('click');
  }
    
  function remove_hasclass(){
    $(document).find('.hasimage').removeClass('hasimage');
  }

  $(document).on("click", "#rem_img", function(){
    $(".showimage").hide();
    $(".showimage").html('');
    $("#ex_img").val('');
  });
</script>