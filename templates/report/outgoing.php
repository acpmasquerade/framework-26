<?php $formdata = Template::getvar("formdata"); ?>
<?php
  if(!isset($formdata['date-from'])){
    $formdata["date-from"] = "";
  }
  if(!isset($formdata['date-to'])){
    $formdata["date-to"] = "";
  }

  if(!isset($formdata["username"])){
    $formdata["username"] = "";
  }
?>

            <div class="row-fluid">
                    
              <div class="btn-toolbar well">
                  <legend>Filters</legend>
                  <form method="GET" class="form" id='filters-form' action="<?php echo Config::url("report/outgoing");?>">
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
                          <div class='span3'>
                            <label for="filter-username">Username</label>
                            <input type='text' id='filter-username' class="span12" name="username" value="<?php echo $formdata["username"];?>">
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
              <div class="well">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Credits</th>
                        <th width=1></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $some_count = 0 ; ?>
                      <?php $total_credits = 0 ; ?>
                      <?php foreach(Template::getvar("logs") as $some_log): ?>
                      <tr>
                        <td><?php echo ++$some_count;?></td>
                        <td><?php echo $some_log->username;?></td>
                        <td><?php echo $some_log->billable_credits;?></td>
                        <td>
                          <a href="<?php echo Config::url("report/account/{$some_log->username}");?>"><i class='icon icon-signal'></i></a>
                        </td>
                        <?php $total_credits += intval($some_log->billable_credits); ?>
                      </tr>
                      <?php endforeach; ?>

                      <?php if(!isset($some_log)): ?>
                      <tr>
                        <td colspan="4">Record not available.</td>
                      </tr>
                      <?php endif; ?>
                      <tr>
                        <td colspan="2">Total</td>
                        <td colspan="2"><?php echo $total_credits;?></td>
                      </tr>
                    </tbody>
                  </table>
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