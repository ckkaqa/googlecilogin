<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>CodeIgniter Simple Chat App</title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
	<style type="text/css">
	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }
	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}
	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}
	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}
	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}
	#body{
		margin: 0 15px 0 15px;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 10px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
</head>
<body>
	<h1>CodeIgniter Simple Chat App</h1>

	<div class = "container">
		<div class="col-md-4">
			<p>My Profile</p>
			<table>
				<tr>
					<td>ID</td>
					<td>:</td>
					<td><?php echo @$user_profile['id'];?></td>
				</tr>
				<tr>
					<td>Name</td>
					<td>:</td>
					<td><?php echo @$user_profile['name'];?></td>
				</tr>
				<tr>
					<td>First Name</td>
					<td>:</td>
					<td><?php echo @$user_profile['given_name'];?></td>
				</tr>
				<tr>
					<td>Last Name</td>
					<td>:</td>
					<td><?php echo @$user_profile['family_name'];?></td>
				</tr>
				<tr>
					<td>Email</td>
					<td>:</td>
					<td><?php echo @$user_profile['email'];?></td>
				</tr>
				<tr>
					<td>Gender</td>
					<td>:</td>
					<td><?php echo @$user_profile['gender'];?></td>
				</tr>
				<tr>
					<td>Photo</td>
					<td>:</td>
					<td><img src="<?php echo $user_profile['picture'];?>" width="200"></td>
				</tr>
			</table>
			
			<p><a href="<?php echo site_url('welcome');?>">Back to Home</a></p>
		</div>
		<input type="hidden" name="" id = "is-checkedin" value="<?php echo $check?>">
		<div class="col-md-8 text-right">
				<a href="<?php echo site_url('login/addTimeLog/in')?>" class = "btn btn-default btn-lg" id = "checkedin">Check in</a>
				<a href="<?php echo site_url('login/addTimeLog/out')?>" class = "btn btn-default btn-lg" id = "checkedout">Check Out</a>
			<ul id = "log">
			</ul>

			<table id="example" class="table table-striped table-bordered " cellspacing="0" width="100%">
			<?php if ($salaryRate): ?>
				<?php 
					$dsalary = $salaryRate->salary_rate/20;
					$hsalary = $dsalary/8;
				?>
			<?php else: ?>
				<?php 
					$dsalary = 0;
					$hsalary = 0;
				?>
			<?php endif ?>
                <thead>
                	<tr class="text-center">
                		<td ></td>
                        <td colspan="2">1st Half (<?php echo date('h:i a',strtotime($salaryRate?$salaryRate->work_start:''))?>)</td>
                        <td colspan="2">2nd Half (<?php echo date('h:i a',strtotime($salaryRate?$salaryRate->work_end:''))?>)</td>
                        <td >Hours</td>
                        <td >Salary</td>
                    </tr>
                    <tr >
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Time out</th>
                        <th>Time In</th>
                        <th>Time out</th>
                        <th>( 8 hours )</th>
                        <th>( P <?php echo $dsalary ?>.00 )</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr class="text-center">
                        <th></th>
                        <th>Time In</th>
                        <th>Time out</th>
                        <th>Time In</th>
                        <th>Time out</th>
                        <th>Hours</th>
                        <th>( P <?php echo $hsalary?>.00 )</th>
                    </tr>
                </tfoot>
                <tbody>
                	<?php foreach ($time_logs as $key => $v):?>
                		<?php 
                			$hours = $getHours($v->id);

                		?>
                		<tr class = "text-left">
                			<td><?php echo date('F d, Y',strtotime($v->morning_in_log)); ?></td>
                			<td><?php echo date('h:i a',strtotime($v->morning_in_log)); ?></td>
                			<td><?php echo $v->morning_out_log == '0000-00-00 00:00:00' ? '' : date('h:i a',strtotime($v->morning_out_log)); ?></td>
                			<td><?php echo $v->noon_in_log == '0000-00-00 00:00:00' ? '' : date('h:i a',strtotime($v->noon_in_log)) ?></td>
                			<td><?php echo $v->noon_out_log == '0000-00-00 00:00:00' ? '' : date('h:i a',strtotime($v->noon_out_log)); ?></td>
                			<td>
	                			<?php 
	                			if ($v->noon_out_log != '0000-00-00 00:00:00') {
	                				echo $hours->hours;
	                				$numHours = $hours->hours;
	                				// echo(round($hours->hours,2));
	                			}else{
	                				echo '0';
	                				$numHours = 0;
	                			}

	                			?>
                			</td>
                			<td>P
	                			<?php 
	                				echo $hsalary*$numHours;
	                			?>
                			</td>
	                    </tr>
                	<?php endforeach; ?>
                </tbody>
            </table>

		</div>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>

<script type="text/javascript">
	$(document).ready(function() {
        $('#example').DataTable( {
        	"order": [[ 0, "desc" ]]
    	} );
    } );


	$(document).ready(function(){
		var check = $('#is-checkedin').val();
		// if (check == 'in') {
		// 	$('#checkedout').hide();
		// }else{
		// 	$('#checkedin').hide();
		// }
		$('#checkedin').on('click', function(){
			$('#checkedin').hide();
			$('#checkedout').show();
		});
		$('#checkedout').on('click', function(){
			$('#checkedin').show();
			$('#checkedout').hide();
		});
	});

</script>

</body>
</html>
