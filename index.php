<?php

// echo "Welcome to index page";
/*
* This file should be a secure protected file, which cannot be accessed unless the user
* has successfully logged in with his/her credentials from users.txt.
* 
* This file should display a dashboard with data from the northwind database
*/

include("db-connect.php");

session_start();


// Get total order on May 1995
$queryOrder = 'SELECT orders.OrderID, order_details.ProductID, orders.CustomerID, orders.CustomerID, orders.OrderDate, order_details.Quantity FROM orders INNER JOIN order_details ON order_details.OrderID=orders.OrderID Where Month(orders.OrderDate)="05" && YEAR(orders.OrderDate)="1995" GROUP BY order_details.OrderID';

$result = $conn->query($queryOrder);
$totalSales = 0;
$totalQuantity = 0;
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $totalOrder = $result->num_rows;
    }
} else {
    echo '0';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>

    <!-- Bootsrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

    <script>
    </script>

    <style>
        body {
            margin-top: 20px;
            /* padding-top: 50px; */
        }

        .second-header {
            padding-top: 20px;
            padding-left: 30px;
        }

        .sales {
            padding: 20px;
            background-color: whitesmoke;
        }

        table {
            width: 50%;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <div class="float-start">
                <h4>Welcome <?php echo $_SESSION['$username']; ?>,</h4>
            </div>
            <div class='float-end'>
                <button type="button" class="btn btn-link">
                    <a href='logout.php' role="button">Logout</a></button>
            </div>
        </div>
        </br>
        <div>
            <div class="header">
                <div class="second-header">
                    <h2>Sales Dashboard</h2>
                    <h3>May 1995</h3>
                </div>
            </div>

            <div class="sales-group">
                <br>
                <div id="total-order" class="sales">
                    <h5>Total order: <?php echo $totalOrder; ?></h5>
                </div>
                <br>

                <!-- DAILY SALES -->
                <div class="sales">
                    <h5>Daily sales:
                    </h5>
                    <br>

                    <table id="dailySalesTbl">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Total Sales</th>
                            </tr>
                        </thead>
                        <?php
                        $counter = 1;
                        $grandTotalSales = 0;
                        for ($i = 1; $i <= 31; $i++) {
                            $dateInitial = "1995-05-" . $i;
                            $queryDailySales = "SELECT * FROM orders INNER JOIN order_details ON order_details.OrderID=orders.OrderID WHERE orders.OrderDate='$dateInitial'";

                            $result = $conn->query($queryDailySales);
                            $totalSalesDay = 0;
                            $totalQuantity = 0;
                            if ($result->num_rows > 0) {
                                $data = array();

                                while ($row = mysqli_fetch_array($result)) {
                                    $count = $result->num_rows;
                                    $orderid = $row['OrderID'];
                                    $productid = $row['ProductID'];
                                    $customerid = $row['CustomerID'];
                                    $unitprice = $row['UnitPrice'];
                                    $quantity = $row['Quantity'];
                                    $discount = $row['Discount'];

                                    $price = $unitprice * $quantity;
                                    $afterdiscount = $price * (1 - $discount);
                                    $totalSalesDay += $afterdiscount;

                                    $priceround = round($price, 2);
                                    $totalSalesDay = round($totalSalesDay, 2);
                                    $count = $result->num_rows;
                                    $grandTotalSales += $totalSalesDay;
                                }
                                $data[] = array("totalsales" => $totalSalesDay, "count" => $count, "dateinitial" => $dateInitial);
                            } else {
                                // echo '0';
                            }

                        ?>
                            <tbody id="dailySalesBody">
                                <tr>
                                    <td><?php echo $counter++ ?></td>
                                    <td><?php echo $dateInitial; ?></td>
                                    <td>RM <?php echo $totalSalesDay; ?></td>
                                </tr>

                            </tbody>

                        <?php
                        }
                        ?>
                    </table>
                    <!-- <h4>Grand total daily sales: <?php echo $grandTotalSales; ?></h4> -->
                </div>

                </br>

                <div id="total-sales" class="sales">
                    <h5>Total sales: <?php echo $grandTotalSales; ?></h5>
                </div>
                </br>

                <div id="product-sales" class="sales">
                    <h5>Percentage of sales by product categories (sales percentage breakdown for the month):
                    </h5>
                </div>
                <br>

                <div id="customer-sales" class="sales">
                    <h5>Sales numbers by customers:
                    </h5>

                    <table id="tableSalesCust">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer ID</th>
                                <th>Total Sales</th>
                            </tr>
                        </thead>


                        <!-- to find total customer based on id -->
                        <?php
                        // Get total sales by customer on May 1995
                        $queryCustSales = "SELECT DISTINCT orders.CustomerID FROM orders WHERE Month(orders.OrderDate)='05' && YEAR(orders.OrderDate)='1995'";

                        $result = $conn->query($queryCustSales);
                        $counter = 1;
                        $totalSalesCust = 0;
                        $totalQuantity = 0;
                        if ($result->num_rows > 0) {
                            $data = array();
                            while ($row = mysqli_fetch_array($result)) {
                                $count = $result->num_rows;
                                $custID = $row['CustomerID'];

                                $queryCust2 = "SELECT * FROM orders INNER JOIN order_details ON order_details.OrderID=orders.OrderID WHERE orders.CustomerID='$custID' && Month(orders.OrderDate)='05' && YEAR(orders.OrderDate)='1995'";
                                $result2 = $conn->query($queryCust2);
                                while ($row2 = mysqli_fetch_array($result2)) {
                                    $rowCount =
                                        $result2->num_rows;
                                    $orderid = $row2['OrderID'];
                                    $productid = $row2['ProductID'];
                                    $customerid = $row2['CustomerID'];
                                    $unitprice = $row2['UnitPrice'];
                                    $quantity = $row2['Quantity'];
                                    $discount = $row2['Discount'];

                                    $price = $unitprice * $quantity;
                                    $afterdiscount = $price * (1 - $discount);
                                    $totalSalesCust += $afterdiscount;

                                    $priceround = round($price, 2);
                                    $totalSalesCust = round($totalSalesCust, 2);
                                    $count = $result->num_rows;
                                }


                                $data[] = array("totalsales" => $totalSalesCust, "count" => $rowCount, "customerid" => $customerid, "orderId" => $orderid);
                        ?>

                                <tbody>
                                    <tr>
                                        <td><?php echo $counter++; ?></td>
                                        <td><?php echo $customerid; ?></td>
                                        <td><?php echo $totalSalesCust; ?></td>
                                    </tr>
                                </tbody>
                        <?php
                            }
                        } else {
                            echo '0';
                        }
                        ?>
                    </table>

                </div>

                <br>

                <div id="employee-sales" class="sales">
                    <h5>Sales numbers by employees
                    </h5>
                    <table>
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Total Sales</th>
                            </tr>
                        </thead>
                        <?php

                        $queryEmpId = "SELECT * FROM employees";
                        $resultQuery = $conn->query($queryEmpId);
                        $totalEmp = $resultQuery->num_rows;
                        ?>
                        <h5>Total Employee: <?php echo $totalEmp; ?></h5>
                        </br>
                        <?php
                        for (
                            $i = 1;
                            $i <= $totalEmp;
                            $i++
                        ) {
                            $employeeid = $i;

                            $queryEmpSales = "SELECT * FROM orders INNER JOIN order_details ON order_details.OrderID=orders.OrderID WHERE Month(orders.OrderDate)='05' && YEAR(orders.OrderDate)='1995' && orders.EmployeeID='$employeeid'";

                            $result = $conn->query($queryEmpSales);
                            $totalSalesEmp = 0;
                            $totalQuantity = 0;
                            if ($result->num_rows > 0) {
                                $data = array();
                                while ($row = mysqli_fetch_array($result)) {
                                    $count = $result->num_rows;
                                    $orderid = $row['OrderID'];
                                    $productid = $row['ProductID'];
                                    $customerid = $row['CustomerID'];
                                    $unitprice = $row['UnitPrice'];
                                    $quantity = $row['Quantity'];
                                    $discount = $row['Discount'];

                                    $price = $unitprice * $quantity;
                                    $afterdiscount = $price * (1 - $discount);
                                    $totalSalesEmp += $afterdiscount;

                                    $priceround = round($price, 2); //to get 2 dp
                                    $totalSalesEmp = round($totalSalesEmp, 2);
                                    $count = $result->num_rows;
                                }

                                $data[] = array("totalsales" => $totalSalesEmp, "count" => $count, "employeeid" => $employeeid);
                            } else {
                                // echo '0';
                            }
                        ?>
                            <tbody>
                                <tr>
                                    <td><?php echo $employeeid; ?></td>
                                    <td><?php echo $totalSalesEmp ?></td>
                                </tr>
                            </tbody>

                        <?php
                        }
                        ?>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- Boostrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <script src="js/chart.js"></script>

</body>

</html>