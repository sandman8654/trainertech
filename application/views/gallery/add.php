<section class="contant">
  <?php echo alert(); ?>   
  <h3>Add Image</h3>
  <?php echo form_open_multipart(current_url(), array('class'=>'tasi-form')); ?>            

            <div class="form-group">
              <label for="exampleInputEmail1">Image *</label> 
              <input type="file" name="gallery_image">              
            </div>
            
            <input name="submit" type="submit" class="btn btn-primary" value="Add">
          </form>    
</section>



   