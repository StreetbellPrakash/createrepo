<?php
include_once '../../include/session.php';
include("../../include/globals.php");
include("header.php");
include("../../include/connect.php");
include 'mainmenu.php';
include '../logincheck.php';
include 'rightbar.php';
include 'leftmenu.php';
include_once '../../logincheckfunction.php';
include("../include/Get_Products_Admin.php");
include_once '../include/Get_Settings.php';
$getsettings = new GetSettings();
$pagepermission = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
$ispermit = $getsettings->checkPermission($pagepermission);

if($_SESSION['AdminType'] != "SUPERADMIN"){
if (!$ispermit) {
    
    redirect("logout.php");
}
}
$getproducts = new Get_Products_Admin();
    $storeuniqueId = $_SESSION['storeuniqueId'];
?>

<link rel="stylesheet" type="text/css" href="files/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="files/assets/pages/data-table/css/buttons.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="files/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="files/assets/css/pages.css">
<style>
    th,td{
        white-space: normal;
        }
        .jconfirm .container {
  margin-left:  25%;
}
.page-pagination {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  margin-top: 30px;
  border: 1px solid #EBEBEB;
  padding: 10px 30px;

}
@media (min-width: 768px) {
  .page-pagination {
    margin-top: 80px;
    flex-direction: row;
    align-items: center;
  }
}
@media (min-width: 992px) {
  .page-pagination {
    margin-top: 100px;
  }
}
.page-pagination__list {
  margin-top: 20px;
}
@media (min-width: 768px) {
  .page-pagination__list {
    margin-top: 0;
  }
}
.page-pagination__item {
  display: inline-block;
  margin-right: 5px;
}
.page-pagination__item:last-child {
  margin-right: 0;
}
.page-pagination__link {
  display: block;
  text-align: center;
  font-size: 16px;
  padding: 5px 12px;
  background: #EBEBEB;
  color: #666;
  border-radius: 3px;
  margin-bottom: 5px;
}
.page-pagination__link:hover, .page-pagination__link.active {
  background: #79A206;
  color: #fff;
}

</style>
<div class="pcoded-content">
 <div class="page-header card">
<div class="row align-items-end">
<div class="col-lg-8">
<div class="page-header-title">
<i class="feather icon-box bg-c-blue"></i>
<div class="d-inline">
<h5>Products</h5>
<span>Add, View, Edit Your Products here.</span>
</div>
</div>
</div>
<div class="col-lg-4">
<div class="page-header-breadcrumb">
<ul class=" breadcrumb breadcrumb-title">
<li class="breadcrumb-item">
<a href="product_list.php"><i class="feather icon-home"></i></a>
</li>
<li class="breadcrumb-item"><a href="product_list.php">Products</a> </li>
</ul>
</div>
</div>
</div>
</div> 

<div class="pcoded-inner-content">
<div class="main-body">
<div class="page-wrapper">
<div class="page-body">
<!-- start page content-->

<div class="card">
<div class="card-header">
<div class="card-header-left">
<h5>All Products</h5>
</div>
<div class="card-header-right">
    <a href="addproduct.php" >
	<button class="btn btn-primary waves-effect waves-light">Add Product</button>
    </a>
</div>
</div>

<div class="card-block">
<!--
<div class="row">
<div class="col-sm-12">
<div class="input-group input-group-dropdown">
<div class="input-group-prepend">
<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Filter</button>
<div class="dropdown-menu">
<a class="dropdown-item" href="#">list1</a>
<a class="dropdown-item" href="#">list2</a>
<a class="dropdown-item" href="#">list3</a>
<a class="dropdown-item" href="#">list4</a>
</div>
</div>
<input type="text" class="form-control" aria-label="Text input with dropdown button" placeholder="Search Products">
</div>
</div>
</div> -->

<div class="table-responsive">
<div id="example-1">
<!--<table class="table table-striped table-bordered" id="example-2">-->
    <table id="order-table" class="table table-hover m-b-0">
<thead>
<tr>
<th>Image</th>
<th><?php echo ucfirst($_SESSION['webname']." P/N"); ?></th>
<th>Product Name</th>
<th>Selling Price</th>
<th>Stock Availability</th>
<th>Online Status</th>
<th>Action</th>
</tr>
</thead>
<tbody id="listproductsajax">
<?php
if (isset($_GET['pageno'])) {
                $pageno = $_GET['pageno'];
            } else {
                $pageno = 1;
            }
            $no_of_records_per_page = 50;
           $offset = ($pageno - 1) * $no_of_records_per_page;
       $total = $getproducts->GetProductsTotal();
              // $method = $_SERVER['REQUEST_METHOD'];
        $total_pages = ceil($total / $no_of_records_per_page);  
  $productdetails = $getproducts->GetProducts($offset);
  

if ($productdetails) {
    foreach ($productdetails as $data) {
        
        $orderID= $data['orderID'];
        if($orderID == "1"){
        $PID = $data['int_id'];
        $int_stock = $data['int_stock'];
        $str_sname = $data['str_sname'];
        $userId = $data['userId'];
        $pid = $data['int_id'];
        $amount = $data['float_amount'];
        $details = $data['str_details'];
        $uniqueId = $data['uniqueId'];
        $deleted = $data['int_deleted'];
        $pcode = $data['productcode'];
        $tech_info_pdf = unserialize($data['techinical_info_pdf']);
        $add_info_pdf = unserialize($data['additional_info_pdf']);
        $internal_info_pdf = unserialize($data['internal_info_pdf']);
        
        $customerwisesellingprice = unserialize($data['customerwise_sellingprice']);
        $custpno="";
        if(count($customerwisesellingprice)>0 && $customerwisesellingprice !=""){
           for($t=0;$t<count($customerwisesellingprice); $t++){
             $custpno .= $customerwisesellingprice[$t]["pno"]."<br>";
               
           }

        }
        
     if($int_stock == "1"){
         $stock="IN Stock";
     }else{
      $stock="Out of Stock";
 
     }
  
     if($deleted == "1"){
         $isactive ="Inactive";
     }else{
     $isactive ="Active";
     }
    
     $imageo = $data['name'];
      $image = str_replace("o_", "s_", $imageo);
           $filename= $uniqueId ."/". $image;                               
 ?>   
<tr>
<th scope="row">
    <a href="product_img_edit.php?pid=<?php echo $PID ?>" style="cursor:pointer;">
    <img class="img-radius" onError="this.onerror=null;this.src='/images/1380718228.jpg';" src="<?php echo $bucketurl.$filename;?>" width="50px;" height="50px;">
    </a>
</th>
<td class="tabledit-view-mode"><span class="tabledit-span"><?php echo $pcode; ?></span>
<input class="tabledit-input form-control input-sm" type="text" name="pcode" disabled="">
</td>
<td class="tabledit-view-mode"><span class="tabledit-span"><?php echo $str_sname."<br><small>".$custpno."</small>"; ?></span><br>
    <?php if($tech_info_pdf !="" && count($tech_info_pdf) >0){ 
        for($t=0;$t<count($tech_info_pdf); $t++){  
            $pdfname=$tech_info_pdf[$t];
           $pdfurl= $bucketurl.$storeuniqueId."/".$pdfname;
           ?>
    <a href="<?php echo $pdfurl; ?>" target="_blank"    ><small><?php echo $t + 1 .". ".$pdfname; ?> </small></a><br> 
        
            
        <?php  } ?>
    <br>
        
       <?php } ?>
    
    
    <?php if($add_info_pdf !="" && count($add_info_pdf) >0){ 
        for($t=0;$t<count($add_info_pdf); $t++){  
            $pdfname=$add_info_pdf[$t];
           $pdfurl= $bucketurl.$storeuniqueId."/".$pdfname;
           ?>
    <a href="<?php echo $pdfurl; ?>" target="_blank"    ><small><?php echo $t + 1 .". ".$pdfname; ?> </small></a><br> 
        
            
        <?php  } ?>
    <hr>
        
       <?php } ?>
    
    <?php if($internal_info_pdf !="" && count($internal_info_pdf) >0){ 
        for($t=0;$t<count($internal_info_pdf); $t++){  
            $pdfname=$internal_info_pdf[$t];
           $pdfurl= $bucketurl.$storeuniqueId."/".$pdfname;
           ?>
    <a href="<?php echo $pdfurl; ?>" target="_blank"    ><small><?php echo $t + 1 .". ".$pdfname; ?> </small></a><br> 
        
            
        <?php  } ?>
    <hr>
        
       <?php } ?> 
<input class="tabledit-input form-control input-sm" type="text" name="pname" disabled="">
</td>
<td class="tabledit-view-mode"><span class="tabledit-span"><?php echo $amount; ?></span>
<input class="tabledit-input form-control input-sm" type="text" value="<?php echo $amount; ?>" name="price" data-a-sign="Rs. " disabled="">
</td>
<td class="tabledit-view-mode"><span class="tabledit-span"><?php echo $stock; ?></span>
    <!--
<select class="tabledit-input form-control input-sm" name="stack" disabled="" style="display:none;">
<option value="0" <?php if($int_stock == 0){  echo 'selected'; } ?> >Out of Stock</option>
<option value="1" <?php if($int_stock == 1){  echo 'selected'; } ?> >IN Stock</option>

</select>-->
</td>
<td class="tabledit-view-mode"><span class="tabledit-span"><?php echo $isactive;?></span>
    <!--
<select class="tabledit-input form-control input-sm" name="isdeleted" disabled="" style="display:none;">
<option value="0" <?php if($isDeleted == 0){  echo 'selected'; } ?> >Active</option>
<option value="1" <?php if($isDeleted == 1){  echo 'selected'; } ?> >Inactive</option>

</select>-->
</td>
<td style="width: 150px;">
    <div class="btn-group btn-group-sm" style="float: none;">
	<a href="product_edit_section.php?pid=<?php echo $PID ?>" class="tabledit-edit-button btn btn-primary waves-effect waves-light" style="float: none;">
		<span class="icofont icofont-ui-edit"></span>
	</a>
        <a href="product_speccreatepdf.php?pid=<?php echo $PID ?>" style="float: none;" class="tabledit-edit-button btn btn-secondary waves-effect waves-light">
    <span class="icofont icofont-file-pdf"></span>
  </a>
        <a href="javascript:void(0);" data-id="btn<?php echo $PID;?>" onclick="deleteproduct(this,'<?php echo $PID;?>')" class="tabledit-delete-button btn btn-danger waves-effect waves-light" style="float: none;">
		<span class="icofont icofont-ui-delete"></span>
	</a>
</div>

    
</td>
</tr>
<?php
    } }
}else{
?>
<tr><td colspan="6"> No records found</td></tr>
<?php } ?>
</tbody>
</table>
</div>
    
  
        <div class="page-pagination m-t-50">
            <ul class="page-pagination__list">
    <?php if ($pageno < $total_pages && $pageno > 1) { ?>
                    <li class="page-pagination__item"><a class="page-pagination__link"  href="<?php echo "/main/services/product_list.php?pageno=" . ($pageno - 1); ?>">Prev</a>
        <?php } else { ?>    
                    <li class="page-pagination__item"><a class="page-pagination__link"  href="<?php echo "/main/services/product_list.php?pageno=" . ($pageno); ?>">Prev</a>  
        <?php } ?>   
                </li>  
                <?php if ($total_pages > 0) { ?>   
                    <?php
                    for ($p = 1; $p <= $total_pages; $p++) {
                        if ($pageno == $p) {
                            ?> 
                            <li class="page-pagination__item"  id="<?php echo "page" . $p; ?>"><a class="page-pagination__link active"  href="<?php echo "/main/services/product_list.php?pageno=" . ($p); ?>"><?php echo $p; ?></a></li>
                        <?php } else { ?>
                            <li class="page-pagination__item"  id="<?php echo "page" . $p; ?>"><a class="page-pagination__link"  href="<?php echo "/main/services/product_list.php?pageno=" . ($p); ?>"><?php echo $p; ?></a></li>

                        <?php
                        }
                    }
                }
                ?>

                <?php if ($pageno < $total_pages) { ?>
                    <li class="page-pagination__item"><a class="page-pagination__link"  href="<?php echo "/main/services/product_list.php?pageno=" . ($pageno + 1); ?>">Next</a>
                <?php } else { ?>
                    <li class="page-pagination__item"><a class="page-pagination__link"  href="<?php echo "/main/services/product_list.php?pageno=" . ($pageno); ?>">Next</a> 
                <?php } ?>
                </li>
            </ul>
        </div>  
        
    
</div>

</div>

</div>


<!-- end page content-->
</div>
</div>
</div>
</div>
</div>
<!--
<div id="styleSelector">
</div>-->

<?php include'footer.php';?>

<script src="/plugins/jquery-confirm/jquery-confirm.js" type="text/javascript"></script>   
<link href="/plugins/jquery-confirm/jquery-confirm.css" rel="stylesheet" type="text/css" media="screen" />  
<script src="/plugins/alert/jquery.ui.draggable.js" type="text/javascript"></script>   
<script src="/plugins/alert/jQuery.alert.js" type="text/javascript"></script>   
<link href="/plugins/alert/jQuery.alert.css" rel="stylesheet" type="text/css" media="screen" />  

<script src="product_list_delete.js" type="text/javascript"></script>
<script src="product_list_search.js.js" type="text/javascript"></script>


<!-- data table script-->
<script src="files/bower_components/datatables.net/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="files/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="files/assets/pages/data-table/js/jszip.min.js" type="text/javascript"></script>
<script src="files/assets/pages/data-table/js/pdfmake.min.js" type="text/javascript"></script>
<script src="files/assets/pages/data-table/js/vfs_fonts.js" type="text/javascript"></script>
<script src="files/bower_components/datatables.net-buttons/js/buttons.print.min.js" type="text/javascript"></script>
<script src="files/bower_components/datatables.net-buttons/js/buttons.html5.min.js" type="text/javascript"></script>
<script src="files/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
<script src="files/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="files/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js" type="text/javascript"></script>

<script src="files/assets/pages/data-table/js/data-table-custom.js" type="text/javascript"></script>

<!--
<script type="text/javascript" src="files/assets/pages/edit-table/jquery.tabledit.js"></script>
<script type="text/javascript" src="files/assets/pages/edit-table/editable.js"></script> -->
<!--<script src="files/assets/js/vertical/vertical-layout.min.js" type="text/javascript"></script>-->
