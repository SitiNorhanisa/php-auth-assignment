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
        $count = $result->num_rows;
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
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous"> -->


    <!-- jQuery -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js"></script> -->

    <!-- <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js" integrity="sha512-2rNj2KJ+D8s1ceNasTIex6z4HWyOnEYLVC3FigGOmyQCZc2eBXKgOxQmo3oKLHyfcj53uz4QMsRCWNbLd32Q1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js" integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous"></script> -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>



    <script>
    </script>
</head>

<body>
    <div>
        <h4>Welcome <?php echo $_SESSION['$username']; ?></h4>
        <div>
            <a href="logout.php">Logout</a>
        </div>
        <div>
            <h4>Sales Dashboard</h4>
            <br>
            <h5>May 1995</h5>
            <br>
            <h5>1) Total sales (text number): <span id="sales"></span></h5>
            <br>
            <h5>2) Total order (text number): <?php echo $count; ?></h5>
            <br>

            <!-- DAILY SALES -->
            <h5>3) Daily sales (bar chart):
            </h5>
            <br>

            <canvas id="myChart" style="width:100%;max-width:600px"></canvas>

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
                for ($i = 1; $i <= 31; $i++) {
                    $dateInitial = "1995-05-" . $i;
                    $queryDailySales = "SELECT * FROM orders INNER JOIN order_details ON order_details.OrderID=orders.OrderID WHERE orders.OrderDate='$dateInitial'";

                    $result = $conn->query($queryDailySales);
                    $totalSales = 0;
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
                            $totalSales += $afterdiscount;

                            $priceround = round($price, 2);
                            $totalSales = round($totalSales, 2);
                            $count = $result->num_rows;
                        }
                        $data[] = array("totalsales" => $totalSales, "count" => $count, "dateinitial" => $dateInitial);
                        // echo json_encode($data);
                        // echo ("<br>");
                    } else {
                        // echo '0';
                    }


                ?>

                    <tbody id="dailySalesBody">
                        <tr>
                            <td><?php echo $counter++ ?></td>
                            <td><?php echo $dateInitial; ?></td>
                            <td>RM <?php echo $totalSales; ?></td>
                        </tr>

                    </tbody>

                <?php
                }
                ?>
            </table>
            <h5>4) Percentage of sales by product categories (sales percentage breakdown for the month):
            </h5>
            <br>
            <h5>5) Sales numbers by customers
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
                $totalSales = 0;
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
                            $totalSales += $afterdiscount;

                            $priceround = round($price, 2);
                            $totalsalesround = round($totalSales, 2);
                            $count = $result->num_rows;
                        }


                        $data[] = array("totalsales" => $totalsalesround, "count" => $rowCount, "customerid" => $customerid, "orderId" => $orderid);
                ?>

                        <tbody>
                            <tr>
                                <td><?php echo $counter++; ?></td>
                                <td><?php echo $customerid; ?></td>
                                <td><?php echo $totalsalesround; ?></td>
                            </tr>
                        </tbody>
                <?php
                    }
                } else {
                    echo '0';
                }
                ?>
            </table>

            <h5>Total customer: </h5>
            <br>
            <h5>6) Sales numbers by employees
            </h5>
            <br>
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
                        // echo json_encode($data);
                        // echo "<br>";
                        // echo "<br>";
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

    <!-- Boostrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script> -->

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <script src="js/chart.js"></script>

</body>

</html>