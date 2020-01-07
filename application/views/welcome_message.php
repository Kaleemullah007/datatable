<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

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

	#body {
		margin: 0 15px 0 15px;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
	

	<link href="http://localhost/template/template/back/css/bootstrap.min.css" rel="stylesheet">
<!--Nifty Stylesheet [ REQUIRED ]-->
<link href="http://localhost/template/template/back/css/nifty.min.css" rel="stylesheet">
<link href="http://localhost/template/template/back/css/theme-dark.css" rel="stylesheet">
<!--Nifty Premium Icon [ DEMONSTRATION ]-->
<link href="http://localhost/template/template/back/css/demo/nifty-demo-icons.min.css" rel="stylesheet">
<!--Demo [ DEMONSTRATION ]-->
<link href="http://localhost/template/template/back/css/demo/nifty-demo.min.css" rel="stylesheet">
<!--Premium Solid Icons [ OPTIONAL ]-->
<link href="http://localhost/template/template/back/premium/icon-sets/icons/solid-icons/premium-solid-icons.css" rel="stylesheet">
<!--Font Awesome [ OPTIONAL ]-->
<link href="http://localhost/template/template/back/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="http://localhost/template/template/back/plugins/datatables/media/css/dataTables.bootstrap.css" rel="stylesheet">
<!-- <link href="http://localhost/template/template/back/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet"> -->
        <link href="http://localhost/template/uploads/favicon/favicon_1515409281.png" rel="icon" type="image/png">

<!--JAVASCRIPT-->
<!--=================================================-->
<!--Pace - Page Load Progress Par [OPTIONAL]-->
<link href="http://localhost/template/template/back/plugins/pace/pace.min.css" rel="stylesheet">
<script src="http://localhost/template/template/back/plugins/pace/pace.min.js"></script>
<!--jQuery [ REQUIRED ]-->
<script src="http://localhost/template/template/back/js/jquery.min.js"></script>
<!--BootstrapJS [ RECOMMENDED ]-->
<script src="http://localhost/template/template/back/js/bootstrap.min.js"></script>
<!--NiftyJS [ RECOMMENDED ]-->
<script src="http://localhost/template/template/back/js/nifty.min.js"></script>
<!--=================================================-->
<!--Demo script [ DEMONSTRATION ]-->
<script src="http://localhost/template/template/back/js/demo/nifty-demo.min.js"></script>
<!--DataTables [ OPTIONAL ]-->
<script src="http://localhost/template/template/back/plugins/datatables/media/js/jquery.dataTables.js"></script>
<script src="http://localhost/template/template/back/plugins/datatables/media/js/dataTables.bootstrap.js"></script>
<script src="http://localhost/template/template/back/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
<!--DataTables Sample [ SAMPLE ]-->
<script src="http://localhost/template/template/back/js/demo/tables-datatables.js"></script>
</head>
<body>

	<!--CONTENT CONTAINER-->

	<div id="container">
	<h1>Welcome to Datatable!</h1>

	<div id="body">
		<p>The page you are looking at is being generated dynamically by CodeIgniter.</p>

		<p>If you would like to edit this page you'll find it located at:</p>
		<code>application/views/welcome_message.php</code>

		<p>The corresponding controller for this page is found at:</p>
		<code>application/controllers/Welcome.php</code>

		<p>If you are exploring CodeIgniter for the very first time, you should start by reading the <a href="user_guide/">User Guide</a>.</p>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>
<!--===================================================-->
<div id="content-container">
	<div id="page-head">
		<!--Page Title-->
		<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
		<div id="page-title">
	
		</div>
		<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
		<!--End page title-->
		<!--Breadcrumb-->
		<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
		
		<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
		<!--End breadcrumb-->
	</div>
	<!--Page content-->
	<!--===================================================-->
	<div id="page-content">
		<!-- Basic Data Tables -->
		<!--===================================================-->
		<div class="panel">
		
			<div class="panel-heading">
				
			</div>
			<div class="panel-body">
			
				<div class="row">
					<table id="kaleem" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
					<tr>
						<th data-sortable="false">
							<?php echo translate('user_image')?>
						</th>



						<th>
							<?php echo translate('Member ID')?>
						</th>
						<th>
							<?php echo translate('name')?>
						</th>
						<?php $member_approval = $this->db->get_where('general_settings', array('type' => 'member_approval_by_admin'))->row()->value; 
						if($member_approval == 'yes') { ?>
							<th>
								<?php echo translate('approval_status')?>
							</th>
						<?php } ?>

						<th>
							<?php echo translate('followers')?>
						</th>
						<th>
							<?php echo translate('profile_reported')?>
						</th>
						<?php if ($parameter == "premium_members"): ?>
							<th data-sortable="false">
								<?php echo translate('package')?>
							</th>
						<?php endif ?>
						<th>
							<?php echo translate('member_since')?>
						</th>
						<th>
							<?php echo translate('member_status')?>
						</th>
						<th width= "15%" data-sortable="false">
							<?php echo translate('options')?>
						</th>

					</tr>
					</thead>				</table>
				</div>
			</div>
		</div>
		<!--===================================================-->
		<!-- End Striped Table -->
	</div>
	<!--===================================================-->
	<!--End page content-->
</div>
<style>
	#validation_info p {
		margin: 0px;
		color: #DE1B1B;
	}
</style>
<!--Default Bootstrap Modal-->
<!--===================================================-->
<div class="modal fade" id="admins_modal" admins="dialog" tabindex="-1" aria-labelledby="admins_modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modal_title"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="modal_body">
            	
            </div>
        	<div class="col-sm-12 text-center" id="validation_info" style="margin-top: -30px">

        	</div>            
            <!--Modal footer-->
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default btn-sm" type="button" id="modal_close"><?php echo translate('close')?></button>
                <button class="btn btn-primary btn-sm" id="save_admins" value="0"><?php echo translate('save')?></button>
            </div>
        </div>
    </div>
</div>
<!--===================================================-->
<!--End Default Bootstrap Modal-->
<!--Default Bootstrap Modal For DELETE-->
<!--===================================================-->
<div class="modal fade" id="delete_modal" admins="dialog" tabindex="-1" aria-labelledby="delete_modal" aria-hidden="true">
    <div class="modal-dialog" style="width: 400px;">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title"><?php echo translate('confirm_delete')?></h4>
            </div>
           	<!--Modal body-->
            <div class="modal-body">
            	<p><?php echo translate('are_you_sure_you_want_to_delete_this_data?')?></p>
            	<div class="text-right">
            		<button data-dismiss="modal" class="btn btn-default btn-sm" type="button" id="modal_close"><?php echo translate('close')?></button>
                	<button class="btn btn-danger btn-sm" id="delete_admins" value=""><?php echo translate('delete')?></button>
            	</div>
            </div>
           
        </div>
    </div>
</div>
<!--===================================================-->
<!--End Default Bootstrap Modal For DELETE-->
<script>
	setTimeout(function() {
	    $('#success_alert').fadeOut('fast');
	    $('#danger_alert').fadeOut('fast');
	}, 5000); // <-- time in milliseconds
</script>
<script>
	$("#add_admins").click(function(){
	    $("#modal_title").html("Add admins");
	    $("#modal_body").html("<div class='text-center'><i class='fa fa-refresh fa-5x fa-spin'></i></div>");
	    $("#save_admins").val(1);
	    $('#validation_info').html("");
	    $.ajax({
		    url: "<?=base_url()?>admin/admins/add_admins",
		    success: function(response) {
				$("#modal_body").html(response);
		    },
			fail: function (error) {
			    alert(error);
			}
		});
	});

	function edit_admins(id){
		$("#modal_title").html("Edit admins");
	    $("#modal_body").html("<div class='text-center'><i class='fa fa-refresh fa-5x fa-spin'></i></div>");
	    $("#save_admins").val(2);
	    $('#validation_info').html("");
	    $.ajax({
		    url: "<?=base_url()?>admin/admins/edit_admins/"+id,
		    success: function(response) {
				$("#modal_body").html(response);
		    },
			fail: function (error) {
			    alert(error);
			}
		});
	}

	$("#save_admins").click(function(){
		var check = $("#save_admins").val();
		var form = $("#admins_form");
		if (check == 1) {
			var page_url = "<?=base_url()?>admin/admins/do_add"
		} 
		if (check == 2) {
			var page_url = "<?=base_url()?>admin/admins/update"
		}
	    $.ajax({
		    type: "POST",
		    url: page_url,
		    cache: false,
		    data: form.serialize(),
		    success: function(response) {
		    	if (IsJsonString(response)) {
		    		// Displaying Validation Error for ajax submit
		    		var JSONArray = $.parseJSON(response);
		    		var html = "";
		    		$.each(JSONArray, function(row, fields) {
		    			html = fields['ajax_error'];
		    		});
		    		$('#validation_info').html(html);
		    	}
		    	else {
		    		window.location.href = "<?=base_url()?>admin/admins";
		    	}
		    },
			fail: function (error) {
			    alert(error);
			}
		});
	});

	function delete_admins(id){
	    $("#delete_admins").val(id);
	}

	$("#delete_admins").click(function(){
    	$.ajax({
		    url: "<?=base_url()?>admin/admins/delete/"+$("#delete_admins").val(),
		    success: function(response) {
				window.location.href = "<?=base_url()?>admin/admins";
		    },
			fail: function (error) {
			    alert(error);
			}
		});
    })

    function IsJsonString(str) {
	    try {
	        JSON.parse(str);
	    } catch (e) {
	        return false;
	    }
	    return true;
	}
</script>



</body>
</html>
<script type="text/javascript">
	
    $(document).ready(function () {
    	var type = "users";
    	var url = "";
    	if (type == "users") {
    		url = "http://localhost/template/index.php/welcome/members/users/list_data";
    		$('#kaleem').DataTable({
	            "processing": true,
	            "serverSide": true,
	            "lengthMenu": [[10, 15, 25, 35, 50, 100, 1000,-1], [10, 15, 25, 35, 50, 100, 1000,"All"]],
	            "ajax":{
					"url": url,
					"dataType": "json",
					"type": "POST",
					"data":{  'ci_csrf_token' : '' }
				},
		    	"columns": [
					{ "data": "image" },
					{ "data": "member_id" },
					{ "data": "name" },
					
					{ "data": "follower" },
					{ "data": "profile_reported" },
					{ "data": "member_since" },
					{ "data": "member_status" },
					{ "data": "options" },
				],
				"drawCallback": function( settings ) {
			        $('.add-tooltip').tooltip();
			    }
		    });
    	}
    	if (type == "premium_members") {
    		url = "http://localhost/template/index.php/welcome/members/premium_members/list_data";
    		$('#members_table').DataTable({
	            "processing": true,
	            "serverSide": true,
	            "ajax":{
					"url": url,
					"dataType": "json",
					"type": "POST",
					"data":{  'ci_csrf_token' : '' }
				},
		    	"columns": [
					{ "data": "image" },
					{ "data": "member_id" },
					{ "data": "name" },
										{ "data": "follower" },
					{ "data": "profile_reported" },
					{ "data": "package" },
					{ "data": "member_since" },
					{ "data": "member_status" },
					{ "data": "options" },
				],
				"drawCallback": function( settings ) {
			        $('.add-tooltip').tooltip();
			    }
		    });
    	}
    	if (type == "deleted_members") {
    		url = "http://localhost/template/admin/deleted_members/list_data";
    		$('#members_table').DataTable({
	            "processing": true,
	            "serverSide": true,
	            "ajax":{
					"url": url,
					"dataType": "json",
					"type": "POST",
					"data":{  'ci_csrf_token' : '' }
				},
		    	"columns": [
					{ "data": "image" },
					{ "data": "member_id" },
					{ "data": "name" },
					{ "data": "follower" },
					{ "data": "package" },
					{ "data": "member_since" },
					{ "data": "member_status" },
					{ "data": "options" },
				],
				"drawCallback": function( settings ) {
			        $('.add-tooltip').tooltip();
			    }
		    });
    	}
    });
   function view_package(id){
		$.ajax({
		    url: "http://localhost/template/admin/member_package_modal/"+id,
		    success: function(response) {
				$("#package_modal_body").html(response);
		    },
			fail: function (error) {
			    alert(error);
			}
		});
	}
</script>
</script>