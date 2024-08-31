<?php
global $wpdb;
global $woocommerce;
$get_path = plugins_url();
  if ( isset($_POST['btnexport']) ) {
      $delimiter = ",";
      $fb = fopen('php://output', 'w');
      $wpdb->hide_errors();
      @set_time_limit(0);
      if (function_exists('apache_setenv'))
      @apache_setenv('no-gzip', 1);
      @ini_set('zlib.output_compression', 0);
      @ob_clean();

      header('Content-Type: text/csv; charset=UTF-8');
      header('Content-Disposition: attachment; filename=woocommerce-product-reviews-export-' . date('Y_m_d_H_i_s', current_time('timestamp')) . '.csv');
      header('Pragma: no-cache');
      header('Expires: 0');
      $fields = array('comment_ID', 'comment_post_ID', 'product_SKU', 'comment_author', 'comment_author_email', 'comment_date', 'comment_date_gmt', 'comment_content', 'comment_approved', 'comment_parent', 'user_id', 'comment_alter_id', 'rating'); 
      fputcsv($fb, $fields, $delimiter); 
      $args = array ('post_type' => 'product');
      $comments = get_comments( $args );
       foreach($comments as $commentres){
          $args_product = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'id' =>  array($commentres->comment_post_ID),
          );
             $comment_ID = $commentres->comment_ID;
             $comment_post_ID = $commentres->comment_post_ID;
             $product_sku = get_post_meta($commentres->comment_post_ID, '_sku', true);
             $comment_author = $commentres->comment_author;
             $comment_author_email = $commentres->comment_author_email;
             $comment_date = $commentres->comment_date;
             $comment_date_gmt = $commentres->comment_date_gmt;
             $comment_content = $commentres->comment_content;
             $comment_approved = $commentres->comment_approved;
             $comment_parent = $commentres->comment_parent;
             $user_id = $commentres->user_id;
             $comment_alter_id = $commentres->comment_ID;
             $rating = get_comment_meta($commentres->comment_ID, 'rating', true);

             $lineData = array($comment_ID, $comment_post_ID, $product_sku, $comment_author, $comment_author_email, $comment_date, $comment_date_gmt, $comment_content, $comment_approved, $comment_parent, $user_id, $comment_alter_id, $rating); 
              fputcsv($fb, $lineData, $delimiter);
         }
        fclose($fb);
        exit;
}


 if ( isset($_POST['btnimport']) ) {
       // Allowed mime types
     $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

     if(!empty($_FILES['files']['name']) && in_array($_FILES['files']['type'], $csvMimes)){

        if(is_uploaded_file($_FILES['files']['tmp_name'])){
          $csvFile = fopen($_FILES['files']['tmp_name'], 'r');
          fgetcsv($csvFile);
           while(($line = fgetcsv($csvFile)) !== FALSE){
             $comment_post_ID = $line[0];
             $product_sku = $line[1];
             $comment_author = $line[2];
             $comment_author_email = $line[3];
             $comment_content = $line[4];
             $comment_approved = $line[5];
             $comment_parent = $line[6];
             $user_id = $line[7];
             $rating = $line[8];
             $verified = $line[9];

             $postdata = array(
                  'comment_post_ID'      => $comment_post_ID,
                  'comment_date'         => date('Y-m-d H:i:s'),
                  'comment_date_gmt'     => date('Y-m-d H:i:s'),
                  'comment_author'       => $comment_author,
                  'comment_author_url'   => '',
                  'comment_author_email' => $comment_author_email,
                  'comment_content'      => $comment_content,
                  'comment_approved'     => $comment_approved,
                  'comment_type'         => 'review',
                  'comment_parent'       => $comment_parent,
                  'user_id'              => $user_id ,
            );
            $comment_id = wp_insert_comment($postdata);
            update_post_meta($comment_post_ID, '_sku', $product_sku);
            update_comment_meta( $comment_post_ID, 'rating',  $rating  );

            echo "<div class='notice notice-success is-dismissible'><p><strong>Product review import was successfully.</strong></p></div>";
           }
        }else{
           echo "<div class='notice notice-error is-dismissible'><p><strong>There has been an error.</strong></p></div>";
        }
     }else{
       echo "<div class='notice notice-error is-dismissible'><p><strong>Please check the file extension. Only CSV files are allowed.</strong></p></div>";
     }
  }
   

?>  
<script src='<?php echo $get_path;?>/product-review-porter/assets/js/porter.js'></script>
<link rel='stylesheet' href='<?php echo $get_path;?>/product-review-porter/assets/css/porter.css'>
<div id="sndpc-col-right" class="">

  <div class="wp-box">
    <div class="inner">
      <img src="https://www.softnoesis.com/images/logo.png">
    </div>
    <div class="footer footer-blue">
      <ul class="left">
        <li>Created by <a href="http://www.softnoesis.com" target="_blank" title="Softnoesis">Softnoesis Pvt. Ltd.</a></li>
        <li></li>
      </ul>
    </div>
  </div>
</div>
<div class="wrapper">
  <div class="print-tab" data-tab-id="1">
    <ul class="print-tab-menu">
      <li data-tab-menu="tab1"><a>Product Review Export</a></li>
      <li data-tab-menu="tab2"><a>Product Review Import</a></li>
    </ul>
    <div class="print-tab-content">
      <div data-tab-content="tab1">
        <div class="clstollbox">
          <h3 class="clstitle">Export Product Reviews in CSV Format:</h3>
          <p>Export and download your product reviews in CSV format. This file can be used to import product reviews back into your Woocommerce shop.</p>
          <form action="" method="post">
            <p class="submit">
              <input type="submit" class="button button-primary" name="btnexport" value="Export Product Reviews">
            </p>
          </form>
        </div>
      </div>
      <div data-tab-content="tab2">
        <div class="clstollbox">
          <h3 class="clstitle">Import Product Reviews in CSV Format:</h3>
          <p>You can import all product reviews, in CSV format, into WooCommerce.</p>
          <form method="POST" action="" enctype="multipart/form-data">
            <table class="form-table clstable">
              <tbody class="clstbody">
                <tr>
                  <th>
                    <label for="upload">Select a CSV file</label>
                  </th>
                  <td>
                    <input type="file" id="upload" name="files" size="25">
                    <span>  Maximum size: 40 MB </span>
                  </td>
                </tr>

              </tbody>
            </table>
            <p class="submit">
              <input type="submit" class="button button-primary" name="btnimport" value="Import Product Reviews">
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>