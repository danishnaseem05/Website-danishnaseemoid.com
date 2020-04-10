<?php if ( ! defined( 'ABSPATH' ) ) exit;

$ssm_subscribers_list = get_post_meta($postId,'ssm_subscribers_list',true);
    //update_post_meta( $postId, 'ssm_subscribers_list', '', $unique = false);
    //var_dump($ssm_subscribers_list);

    if (empty($ssm_subscribers_list)) {
        $hidebtn = 'none';
      $ssm_subscribers_list = array();
      $ssm_subscribers_list[0] = array(
                'name' => 'Example', 
                'email' => 'example@example.com',
            );
    }
?>
<div style='padding:50px; margin:0 auto; margin-top:50px; font-family:sans-serif,arial;font-size:17px; width:60%;'>
<table class='w3-table w3-striped w3-bordered w3-card-4' style="max-width: 750px;">
  <tr  style="background:#2fa8f9; color: #fff;">
    <th>Name</th>
    <th>Email</th>
  </tr>
  <?php 
    if (is_array($ssm_subscribers_list)) {
      foreach ( $ssm_subscribers_list as $ssm_result ) { ?>
        <tr>
            <td><?php echo $ssm_result['name']; ?></td>
            <td><?php echo $ssm_result['email']; ?></td>
        </tr>
      <?php 

      } 
    }
  ?>

</table>
</div>
  <br>
  <br>
  <div></div>
  <form></form>
  <?php  $subsDataDownloadCheck = wp_create_nonce( 'POPB_data_subs_download_check' );  ?>

<div id="subsListDownloadbtn" class="btn-green"  style='color:#fff; text-decoration:none;padding:15px; float: left; margin-left:50px;' >DOWNLOAD LIST</div>
  <br>
  <br>
  <form method="post" class="empty_button_form" action="<?php echo admin_url('admin-ajax.php?action=ulpb_subscribe_list_empty'); ?>" style="width:350px; float: right;">
  <input type="hidden" name="ps_ID" value="<?php echo $postId; ?>">
  <input type="submit" style='background:#F27935; color:#fff; text-decoration:none;padding:15px;' value="Empty List">
 <p id="response">Note : Deleted email addresses can't be recovered. Backup subscribers data before deleting.</p>
  </form>
  <br>

  <script type="text/javascript">

            ( function( $ ) {

                var allSubscribeFormDataObject =[ <?php 
                        $ssm_results_to_write = get_post_meta( $postId, 'ssm_subscribers_list', true );
                        $all_data = '';
                        if (!empty($ssm_results_to_write)) {
                            $subsListSize = sizeof($ssm_results_to_write);
                            echo "[";
                            foreach ($ssm_results_to_write[$subsListSize-1] as $key => $res) {
                                echo "'$key',";
                            }
                            echo "],";

                            foreach ($ssm_results_to_write as $key => $res) {
                                echo "[";
                                foreach ($res as $value) {
                                  echo "'$value',";
                                }
                                echo "],";
                            }

                        }

                        //echo json_encode($ulpb_formBuilder_single_item['Form_Fields'] ) . ",";
                    ?>];

                function exportSubsListToCsv(filename, rows) {

                  var processRow = function (row) {
                    var finalVal = '';
                    for (var j = 0; j < row.length; j++) {
                        var innerValue = row[j] === null ? '' : row[j].toString();
                        if (row[j] instanceof Date) {
                            innerValue = row[j].toLocaleString();
                        };
                        var result = innerValue.replace(/"/g, '""');
                        if (result.search(/("|,|\n)/g) >= 0)
                            result = '"' + result + '"';
                        if (j > 0)
                            finalVal += ',';
                        finalVal += result;
                    }

                    return finalVal + '\n';
                  };

                  var csvFile = '';
                  for (var i = 0; i < rows.length; i++) {
                      csvFile += processRow(rows[i]);
                      console.log(csvFile);
                  }

                  var blob = new Blob([csvFile], { type: 'text/csv;charset=utf-8;' });
                  if (navigator.msSaveBlob) { // IE 10+
                      navigator.msSaveBlob(blob, filename);
                  } else {
                      var link = document.createElement("a");
                      if (link.download !== undefined) { // feature detection
                          // Browsers that support HTML5 download attribute
                          var url = URL.createObjectURL(blob);
                          link.setAttribute("href", url);
                          link.setAttribute("download", filename);
                          link.style.visibility = 'hidden';
                          document.body.appendChild(link);
                          link.click();
                          document.body.removeChild(link);
                      }
                  }
                }
        
                $('#subsListDownloadbtn').on('click',function(){

                    /*
                    $('.popb_confirm_action_popup').css('display','block');

                    $('.popb_confirm_message').text('Premium Users Only');
                    $('.popb_confirm_subMessage').text('This feature is only available in Premium Version. ');
                    $('.popb_confirm_subMessage').append('<a href="https://pluginops.com/page-builder" target="_blank">Learn More </a>');

                    $('.confirm_btn').click( function(){
                      $('.popb_confirm_action_popup').css('display','none');
                    });
                    */
                    if (allSubscribeFormDataObject == '') {
                      alert('No Subscribers Found!');
                    }
                    var utc = new Date().toJSON().slice(0,10).replace(/-/g,'_');
                    exportSubsListToCsv('PluginOps_subscribe_form_data_'+utc+'.csv',allSubscribeFormDataObject);
                });
            })(jQuery);
        </script>