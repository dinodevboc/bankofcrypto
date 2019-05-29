<?php /*<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Latest compiled and minified CSS -->
<!-- <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" > -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css">
<link rel="stylesheet" href="<?php echo WCP_PLUGIN_URL ?>/css/jquery-ui-timepicker-addon.css">

<!-- Latest compiled and minified JavaScript -->
<!-- <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script> -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.23/moment-timezone-with-data.js"></script>
<script src="<?php echo WCP_PLUGIN_URL  ?>/js/jquery-ui-timepicker-addon.js"></script> */ ?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>




<div class="wrap" style="padding-top:20px;">
  <h3>Crypto Currencies</h3>
  <input type="button" value="Add New" name="btn_add_new" id="btn_add_new" class="btn btn-info btn-sm" onclick="add_new_btn()"  />
  <hr style="background-color:#000000; height:2px; width: 100%;">
    <div class="col-sm-12">
        

        
        <div style="padding-bottom:10px;">
            <div style="clear:both;"></div>
        </div>
        <div class="table-responsive">
            <table id='service-table' class="table table-bordered">
                <thead>
                    <tr>
                        <!-- <th class="all">Client Name</th> -->
                        <th class="all">Name</th>
                        <th class="all">Price</th>
                        <th class="all">Symbol</th>
                        <th class="all">Price Updated Last</th>
                        <th class="all">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!--  Model Popup -->

<div class="modal fade" id="AddNewModal" role="dialog">
    <div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
	    <div class="modal-header">
		
		<h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
	    </div>
	   <div class="modal-body">
      <form action="<?php echo admin_url("admin-ajax.php") ?>" method="post" id="wcpForm" autocomplete="off"  >
        <div class="form-group">
          <div class="wcp-form-field">
            <label for="name"><?php echo __("Title", "wcp") ?><span class="is-required">*</span> </label><br/>
            <input class="form-control" name="name" id="name" type="text" value="" autocomplete="off" required>
          </div>
        </div>
         <div class="form-group">
          <div class="wcp-form-field">
            <label for="price"><?php echo __("Price", "wcp") ?><span class="is-required">*</span> </label><br/>
            <input class="form-control" name="price" id="price" type="text" value="" autocomplete="off" required>
          </div>
        </div>
        <div class="form-group">
          <label for="price_last_updated"><?php echo __("Price Last Updated", "wcp") ?><span class="is-required">*</span> </label><br/>
          <input class="form-control" name="price_last_updated" id="price_last_updated" type="text"  value=""  autocomplete="off" placeholder="" required>
        </div>
        <div class="form-group">
          <label for="uploaded_file"><?php echo __("Symbol", "wcp") ?><span class="is-required">*</span> </label><br/>
          <input class="form-control" name="symbol" id="symbol" type="text"  value=""  autocomplete="off" placeholder="" required>
          <!--div class='file-uploader'>
            <input id="upload_file_button" type="button" class="button" value="<?php _e( 'Upload symbol' ); ?>" />
            <input type='hidden' name='file_attachment_id' id='file_attachment_id' value=''>
            <div class='file-preview-wrapper'>
              <a  id='file-preview' href='' width='200' <?php echo 'style="display:none"'; ?>>View Attachment</a>
            </div>
          </div-->
        </div>
        
        <div class="form-group">
          <input type="hidden" name="action" value="wcp_add_crypCurr" id="formaction"/>
          <input type="hidden" name="edit_ID" value="0" id="edit_ID" />
          <button type="submit" class=" wcp-button btn btn-info" id="submitform" data-text="<?php echo __("Save", "wcp") ?>"><?php echo __("Submit", "wcp") ?></button>
        </div>
    </form>

                    
		</div>
		<div class="modal-footer">
                    <button type="cancel" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>
    </div>
</div>
<!-- Close Model Popup -->
<script>
    
    jQuery(document).ready(function ($) {
        $ = jQuery;
        reload_table();
        var error_count=0;

        var SPform =   $('#wcpForm');
        SPform.validate();

        SPform.submit(function(e){
          e.preventDefault();
          This = $(this);
          This.find("#file_attachment_id-error").remove();
          if(SPform.valid()){
          
         /* if(This.find("#file_attachment_id").val() == "" || This.find("#file_attachment_id").val() == 0){
            This.find("#file_attachment_id-error").remove();
            This.find(".file-uploader").append('<label id="file_attachment_id-error" class="error" for="file_attachment_id">This field is required.</label>');
             return false;
          }*/
          This.find("#submitform").attr("disabled", "disable");
          var input_data = This.serialize(); 
          var ajaxurl = This.attr("action");
          $('.load-spinner').addClass("show");

          $.post(ajaxurl, input_data, function(response) {
            var response = JSON.parse(response);
            if(response.status == 1 && response.success == 1){
              $('.load-spinner').removeClass("show");
              This.find("#submitform").removeAttr("disabled");
              jQuery("#AddNewModal").modal('hide');
              jQuery("#file-preview").hide();
              SPform[0].reset();
              reload_table();
            }
          }); 
          }
          
         });        

     
    });
     function reload_table() {
            jQuery('#service-table').dataTable({
                    "paging": true,
                    "pageLength": 10,
                    "bProcessing": true,
                    "serverSide": true,
                     "bDestroy": true,
                    "ajax": {
                        type: "post",
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {"action": "wcp_get_crypCurrs"}

                    },
                    "aoColumns": [
                      
                        {mData: 'name'},
                        {mData: 'price'},
                        {mData: 'symbol'},
                        {mData: 'price_last_updated'},
                        {mData: 'action'}
                    ],
                    "order": [[ 0, "desc" ]],        

                    "columnDefs": [{
                        "targets": [4],
                        "orderable": false
                    }]
            });
      
        }
      function wcp_delete_row(id) {
            if (confirm("Are you sure?")) {
          

                jQuery.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: {"action": "wcp_delete_crypCurr", id: id},
                    success: function (data) {
                        if (data == "success") {
                            reload_table();
                        }
                    }
                });
            }
             return false;
    }
    function wcp_edit_row(id) {
      jQuery('#price_last_updated').datetimepicker({ timeFormat: 'hh:mm tt'});
    jQuery.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {"action": "wcp_get_crypCurr_by_id", id: id},
            success: function (data) {
               
                var result = JSON.parse(data);
                if (result.status == 1) {
                  var SPform =   jQuery('#wcpForm');
                  SPform.find(jQuery("#name")).val(result.row.name);
                  SPform.find(jQuery("#formaction")).val("wcp_edit_crypCurr");
                  SPform.find(jQuery("#edit_ID")).val(result.row.ID);
                  SPform.find(jQuery("#price")).val(result.row.price);
                  SPform.find(jQuery("#symbol")).val(result.row.symbol);
                  SPform.find(jQuery("#price_last_updated")).val(result.row.price_last_updated);
                  jQuery(".modal-title").html("Edit");
                  jQuery('#AddNewModal').modal('show');
                  console.log(data);
                    
                }
            }
        });
       
    }
      function add_new_btn(){
      	jQuery(".modal-title").html("Add New");
        jQuery('#AddNewModal').modal('show');
        jQuery('#price_last_updated').datetimepicker({ timeFormat: 'hh:mm tt'});
      
    }
   
 
</script>

<?php $my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 ); ?>
<script type='text/javascript'>
    jQuery( document ).ready( function( $ ) {
      
      // Uploading files
      var file_frame;
      var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
      var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
      jQuery('#upload_file_button').on('click', function( event ){
        event.preventDefault();
        // If the media frame already exists, reopen it.
        if ( file_frame ) {
          // Set the post ID to what we want
          file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
          // Open frame
          file_frame.open();
          return;
        } else {
          // Set the wp.media post id so the uploader grabs the ID we want when initialised
          wp.media.model.settings.post.id = set_to_post_id;
        }
        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
          title: 'Select a file to upload',
          button: {
            text: 'Use this file',
          },
          multiple: false // Set to true to allow multiple files to be selected
        });
        // When an file is selected, run a callback.
        file_frame.on( 'select', function() {
          // We set multiple to false so only get one file from the uploader
          attachment = file_frame.state().get('selection').first().toJSON();
          // Do something with attachment.id and/or attachment.url here
          $( '#file-preview' ).attr( 'href', attachment.url ).show();
          $( '#file_attachment_id' ).val( attachment.id );
          $("#file_attachment_id-error").remove();
          // Restore the main post ID
          wp.media.model.settings.post.id = wp_media_post_id;
        });
          // Finally, open the modal
          file_frame.open();
      });
      // Restore the main ID when the add media button is pressed
      jQuery( 'a.add_media' ).on( 'click', function() {
        wp.media.model.settings.post.id = wp_media_post_id;
      });
    });
</script>