
<?php 
include('load.php');
?>

<!DOCTYPE html>
<html>
<head>
<title>Demo</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/bootstrap-theme.min.css">
<style>
  table { margin:0 auto;}
  table tr th { padding:10px; }
  table tr td { padding:10px; }
  input[type="text"], input[type="email"] { width:100%; padding:5px; }
  #contacts_list { display:none; }
</style>
</head>
<body>

<button id="add_new" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#NewContactModal" style="margin:30px auto; display: block;">Add New</button>

<div id="contacts_list"></div>

<script type="text/template" id="listContactsTemplate">
    <table border=1>
      <thead>
      <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Address</th>
      <th>Phone No.</th>
      <th></th>
      <th></th>
      </tr>
      </thead>
      <tbody>
      <% $.each(contacts, function () {  %>
        <tr>
          <td><%= this.NAME %></td>
          <td><%= this.EMAIL %></td>
          <td><%= this.ADDRESS %></td>
          <td><%= this.PHONE %></td>
          <td class="edit_contact" ><a href='' contact_id='<%= this.id %>'>Edit</a></td>
          <td class="delete_contact" ><a href='' contact_id='<%= this.id %>'>Remove</a></td>
        </tr>
      <% }); %>  
      </tbody>
    </table>
</script>

<script type="text/template" id="pb_add_contact_template">
  <div class="modal fade" id="NewContactModal" tabindex="-1" role="dialog" aria-labelledby="NewContactModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add New Contact</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning alert-dismissable validate_error" style="display:none;">
          <strong>( * )</strong> Please Fill Required Fields.
        </div>

        <p><label>Name* : </label>
    		<input type="text" id="pb_name" name="pb_name" value="" /></p>
        <p><label>Email ID : </label>
        <input type="email" id="pb_email" id="pb_email" value="" /></p>
    		<p><label>Phone No* : </label>
        <input type="text" id="pb_phone" name="pb_phone" value="" /></p>
        <p><label>Address : </label>
    		<input type="text" id="pb_address" id="pb_address" value="" /></p>
    	</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="save_contact" class="btn btn-primary">SAVE</button>
      </div>
    </div>
  </div>
</div>
</script>

<script type="text/template" id="pb_edit_contact_template">
  <div class="modal fade" id="EditContactModal" tabindex="-1" role="dialog" aria-labelledby="EditContactModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Edit Contact</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning alert-dismissable validate_error" style="display:none;">
          <strong>( * )</strong> Please Don''t Leave Required Fields Empty.
        </div>
        
        <p><label>Name* : </label>
        <input type="text" id="pb_name" name="pb_name" value="<%= contactDetails.NAME %>" /></p>
        <p><label>Email ID : </label>
        <input type="email" id="pb_email" id="pb_email" value="<%= contactDetails.EMAIL %>" /></p>
        <p><label>Phone No* : </label>
        <input type="text" id="pb_phone" name="pb_phone" value="<%= contactDetails.PHONE %>" /></p>
        <p><label>Address : </label>
        <input type="text" id="pb_address" id="pb_address" value="<%= contactDetails.ADDRESS %>" /></p>
        <input type="hidden" id="contact_id" id="contact_id" value="<%= contactDetails.id %>" /></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="close_update_modal">Close</button>
        <button type="button" id="update_contact" class="btn btn-primary">UPDATE</button>
      </div>
    </div>
  </div>
</div>
</script>

<div id="add_new_contact_model"></div>
<div id="edit_contact_model"></div>

<!-- Scripts ---->
<script src="js/plugins.js" ></script>
<script src="js/app.js" ></script>
</body>
</html>
