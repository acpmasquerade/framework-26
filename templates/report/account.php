<?php Template::load("users/account", array("user"=>Template::getvar("user"))); ?>

<?php $formdata = Template::getvar("formdata"); ?>
<?php
  if(!isset($formdata['date-from'])){
    $formdata["date-from"] = "";
  }
  if(!isset($formdata['date-to'])){
    $formdata["date-to"] = "";
  }

  $account = Template::getvar("account");
?>


<style type="text/css">
  tr.rcode200 td{
    background: lightgreen !important;
  }
</style>

          
            <div class="row-fluid">
                    
              <?php /**
              <div class="btn-toolbar">
                <?php if(Config::get("role") === 'admin'): ?>
                <a href="<?php echo Config::url("user/add");?>" class="btn btn-primary">
                  <i class="icon-plus"></i>
                  New User
                </a>
                <div class="btn-group">
                </div>
                <?php endif; ?>
              </div>
              **/ ?>  


              <div class="btn-toolbar well">
                  <legend>Filters</legend>
                  <form method="GET" class="form" id='filters-form' action="<?php echo Config::url("report/account/{$account}");?>">
                    <div class="span12">
                      <div class="row-fluid">
                          <div class="span3">
                            <label for="filter-date-from">From Date</label>
                            <input type='text' id='filter-date-from' class="span12 datepicker" name="date-from" value="<?php echo $formdata["date-from"];?>">
                          </div>
                          <div class='span3'>
                            <label for="filter-date-to">To Date</label>
                            <input type='text' id='filter-date-to' class="span12 datepicker" name="date-to" value="<?php echo $formdata["date-to"];?>">
                          </div>
                          <div class="span3">
                            <label>&nbsp;</label>
                            <input type="submit" class="btn btn-primary" value="Submit" />
                            <input type="button" class="btn btn-danger" id='button-clear-filters' value="Clear Filters" />
                          </div>
                        </div>
                      </div>
                  </form>
                  <div class='clearfix'></div>
              </div>

              <div class="alert alert-info">
                <label>Total Credits Consumed by the account <strong><?php echo $account;?></strong> : <?php echo Session::getvar("credit-cache/{$account}"); ?>
              </div>

              <div class="well">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>phone</th>
                        <th>message</th>
                        <th>created</th>
                        <th>credits</th>
                        <th>total_unread</th>
                        <th>total_mofald</th>
                        <th>status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $some_count = 0 ; ?>
                      
                      <?php 
                        $response_code_class = array();
                        $response_code_class[200] = "success";
                        $response_code_class[403] = "warning";
                        $response_code_class[500] = "fatal";
                      ?>

                      <?php foreach(Template::getvar("logs") as $some_log): ?>

                      <tr class="alert alert-<?php echo $response_code_class[$some_log->response_code];?>">
                        <td><?php echo ++$some_count;?>
                            <small>(<?php echo $some_log->id;?>)</small>
                        </td>
                        <td><?php echo $some_log->phone;?></td>
                        <td><?php echo $some_log->message;?></td>
                        <td><?php echo $some_log->created;?></td>
                        <td><?php echo $some_log->credits;?></td>
                        <td><?php echo $some_log->total_unread;?></td>
                        <td><?php echo $some_log->total_mofald;?></td>
                        <td><?php echo $some_log->response_status;?></td>
                      </tr>
                      <?php endforeach; ?>

                      <?php if(!isset($some_log)): ?>
                      <tr>
                        <td colspan="8">Record not available.</td>
                      </tr>
                      <?php endif; ?>

                    </tbody>
                  </table>
              </div>

              <div class="pagination">
                <?php echo Helper_Pagination::generate_html(Template::getvar("page_prefix"), Template::getvar("max_results")); ?>
              </div>
              
              <!-- <div class="pagination">
                  <ul>
                      <li><a href="#">Prev</a></li>
                      <li><a href="#">1</a></li>
                      <li><a href="#">2</a></li>
                      <li><a href="#">3</a></li>
                      <li><a href="#">4</a></li>
                      <li><a href="#">Next</a></li>
                  </ul>
              </div> -->





              <SCRIPT TYPE="text/javascript">
              $(function(){
                $('#button-clear-filters').click(function(){
                  $("#filters-form input[type=text]").val("");
                  return false;
                })
              })
              </SCRIPT>