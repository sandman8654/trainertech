
    <div class="container-fluid">
      <div class="row">
        

        <?php $this->load->view('admin/sidebar'); ?>


        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          
          <div class="panel panel-default">
            <div class="panel-heading">Form</div>

            <div class="row">
              
              <div class="col-sm-11 col-md-10 main">
              
                <form role="form">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                  </div>

                  <div class="form-group">
                    <label for="exampleInputPassword1">Textarea</label>
                    <textarea class="form-control" rows="3"></textarea>
                  </div>
                  

                  <div class="form-group">
                    <label for="exampleInputFile">File input</label>
                    <input type="file" id="exampleInputFile">
                    <p class="help-block">Example block-level help text here.</p>
                  </div>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox"> Check me out
                    </label>
                  </div>

                  <div class="radio">
                    <label>
                      <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
                      Option one is this and that&mdash;be sure to include why it's great
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                      Option two can be something else and selecting it will deselect option one
                    </label>
                  </div>

                  <div class="selectbox">
                    <select class="form-control">
                      <option>1</option>
                      <option>2</option>
                      <option>3</option>
                      <option>4</option>
                      <option>5</option>
                    </select>
                  </div>


                  <br>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </form>
              
              </div>
            
            </div>
            
          </div>

          
        </div>
      </div>
    </div>

    