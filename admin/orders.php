<?php
session_start();
include("../db.php");

error_reporting(0);
if(isset($_GET['action']) && $_GET['action']!="" && $_GET['action']=='delete')
{
    $order_id=$_GET['order_id'];

    /*this is delete query*/
    mysqli_query($con,"DELETE FROM orders WHERE order_id='$order_id'") or die("Delete query is incorrect...");
} 

///pagination
$page = $_GET['page'];

if($page == "" || $page == "1")
{
    $page1 = 0;	
}
else
{
    $page1 = ($page * 10) - 10;	
}

include "sidenav.php";
include "topheader.php";

// Handle checkout logic here
if(isset($_POST['checkout'])){
    // Retrieve order details from the form
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Perform checkout operation, e.g., update product stock, store order details in the database, etc.
    // Implement your checkout logic here

    // Redirect to the success page or display a success message
    header("Location: success.php");
    exit;
}
?>
<!-- End Navbar -->
<div class="content">
    <div class="container-fluid">
        <!-- your content here -->
        <div class="col-md-14">
            <div class="card ">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Orders / Page <?php echo $page; ?></h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive ps">
                        <table class="table table-hover tablesorter" id="">
                            <thead class="text-primary">
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Products</th>
                                    <th>Contact | Email</th>
                                    <th>Address</th>
                                    <th>Details</th>
                                    <th>Shipping</th>
                                    <th>Time</th>
                                    <th>Action</th> <!-- Added column for checkout action -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $result = mysqli_query($con, "SELECT order_id, product_title, first_name, mobile, email, address1, address2, product_price, address2, qty FROM orders, products, user_info WHERE orders.product_id = products.product_id AND user_info.user_id = orders.user_id LIMIT $page1, 10") or die("Query 1 incorrect.....");

                                while(list($order_id, $p_names, $cus_name, $contact_no, $email, $address, $country, $details, $zip_code, $time, $quantity) = mysqli_fetch_array($result))
                                {	
                                    echo "<tr>
                                            <td>$cus_name</td>
                                            <td>$p_names</td>
                                            <td>$email<br>$contact_no</td>
                                            <td>$address<br>ZIP: $zip_code<br>$country</td>
                                            <td>$details</td>
                                            <td>$quantity</td>
                                            <td>$time</td>
                                            <td>
                                                <!-- Added checkout form with hidden inputs for order details -->
                                                <form method='POST' action='checkout.php'>
                                                    <input type='hidden' name='product_id' value='$product_id'>
                                                    <input type='hidden' name='quantity' value='$quantity'>
                                                    <button type='submit' name='checkout' class='btn btn-primary'>Checkout</button>
                                                </form>
                                            </td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                        
                        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                        </div>
                        <div class="ps__rail-y" style="top: 0px; right: 0px;">
                            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "footer.php";
?>
