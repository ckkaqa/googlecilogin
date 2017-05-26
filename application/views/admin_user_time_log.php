<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugin/datetimepicker') ?>/jquery.datetimepicker.css"/ >
    <script src="<?php echo base_url('assets/plugin/datetimepicker') ?>/jquery.js"></script>
    <script src="<?php echo base_url('assets/plugin/datetimepicker') ?>/build/jquery.datetimepicker.full.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>

</head>
<body>
    <a href = "<?php echo site_url('admin/setup') ?>" class = "btn btn-default">View All user logins</a>
    <div class="container">
      <h2><?php echo $user->fullname ?></h2>
      <p>Time log</p>            
      
      <form method = "post" action = "<?php echo site_url('admin/viewUserLog/'.encode_url($user->id)) ?>">
        <input type="submit" class = "btn btn-default" name="Submit" value = "Save Changes">
        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
              <thead>
                <tr class="text-center">
                      <td colspan="2">Morning</td>
                      <td colspan="2">Afternoon</td>
                      <td colspan="4">Other info</td>
                  </tr>
                  <tr>
                      <th>Time In</th>
                      <th>Time out</th>
                      <th>Time In</th>
                      <th>Time out</th>
                      <th>Daily Salary</th>
                      <th>Late(min)</th>
                      <th>OT(min)</th>
                      <th>Night Diff(min)</th>
                  </tr>
              </thead>
              <tfoot>
                  <tr>
                      <th>Time In</th>
                      <th>Time out</th>
                      <th>Time In</th>
                      <th>Time out</th>
                      <th>Daily Salary</th>
                      <th>Late(min)</th>
                      <th>OT(min)</th>
                      <th>Night Diff(min)</th>
                  </tr>
              </tfoot>
              <tbody>
                <?php foreach ($time_logs as $key => $v):?>
                  <tr>
                    <td>
                      <?php
                        $originalMornDate = $v->morning_in_log;
                        $new_morn_log = date("Y-M-d H:i", strtotime($originalMornDate));

                        $originalMornOutLog = $v->morning_out_log;
                        $new_morn_out_log = date("Y-M-d H:i", strtotime($originalMornOutLog));

                        $originalNoonInLog = $v->noon_in_log;
                        $new_noon_in_log = date("Y-M-d H:i", strtotime($originalNoonInLog));

                        $originalNoonOutLog = $v->noon_out_log;
                        $new_noon_out_log = date("Y-M-d H:i", strtotime($originalNoonOutLog));

                      ?>
                      <input type="hidden" name="id[]" value = "<?php echo $v->id?>">
                      <input type="text" name = "morning_in_log[]" class="input_time form-control" id="morning_in_log" value = "<?php echo $new_morn_log ?>">

                    </td>
                    <td>
                      <input type="text" name = "morning_out_log[]" class="input_time form-control" id="morning_out_log" value = "<?php echo $v->morning_out_log == '0000-00-00 00:00:00' ? '' : $new_morn_out_log ?>">

                    </td>
                    <td>
                      <input type="text" name = "noon_in_log[]" class="input_time form-control" id="noon_in_log" value = "<?php echo $v->noon_in_log == '0000-00-00 00:00:00' ? '' : $new_noon_in_log ?>">
                    </td>
                    <td>
                      <input type="text" name = "noon_out_log[]" class="input_time form-control" id="noon_out_log" value = "<?php echo $v->noon_out_log == '0000-00-00 00:00:00' ? '' : $new_noon_out_log ?>">
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
          </table>

        </form>

    </div>

</body>
</html>
<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable( {
          "order": [[ 0, "desc" ]]
      } );
    } );

    jQuery('.input_time').datetimepicker({
        format:'Y-M-d H:i',
        defaultTime:'08:00'
    });

</script>