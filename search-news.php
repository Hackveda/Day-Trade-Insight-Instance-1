<?php
session_start();
include("db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search News | Day Trade Insight</title>
    
    <!-- Add DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>
<body>
    <h1>Search Stock News</h1>

    <!-- Create a table with an ID for DataTables to target -->
    <table id="data-table" class="display">
        <thead>
            <tr>
                <th>Date</th>
                <th>Symbol</th>
                <th>News</th>
                <th>Tweet</th>
                <th>Source</th>
            </tr>
        </thead>
        <tbody>
            
            <?php 
            //$sql9 = "SELECT * FROM `tweets` WHERE 1 ORDER BY `created_at` DESC";
            $sql9 = "SELECT * FROM `tweets` WHERE 1 ORDER BY `url` DESC";
            $result9 = $con1->query($sql9);
            
            if($result9->num_rows > 0){
                while($row = $result9->fetch_assoc()){
                    $symbol = $row["symbol"];
                    $news = $row["news"];
                    $timestamp = $row["created_at"];
                    $tweet = $row["text"];
                    $url = $row["url"];
                    $username = $row["screen_name"];
                    $followers = $row["followers_count"];
                    
                    $fromTimezone = new DateTimeZone('UTC'); // Assuming the input timestamp is in UTC
                    $toTimezone = new DateTimeZone('Asia/Kolkata'); // IST (Indian Standard Time) Timezone
                    
                    // Create a DateTime object from the input timestamp and set the input timezone
                    $datetime = new DateTime($timestamp, $fromTimezone);
                    
                    // Convert the DateTime to the IST timezone
                    $datetime->setTimezone($toTimezone);
                    
                    // Format the DateTime object as per your desired format
                    $formattedDate = $datetime->format('j M Y \a\t g:i A');
                    
                    //echo $formattedDate; // Output: 10 Sep 2023 at 4:08 AM
                    
                    $news_data .= "<tr><td>".$symbol."</td><td>".$formattedDate."</td><td>".$news."</td>";
                    $news_data .= "<td>".$tweet."</td><td><a href='".$url."'>".$url."</a></td></tr>";
                }
                
                echo $news_data;
                
                
            }else{
                    echo "Searching News";
            }
            
            ?>
            
        </tbody>
    </table>

    <!-- Add DataTables JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <!-- Initialize DataTables and enable search -->
    <script>
        $(document).ready(function () {
            $('#data-table').DataTable();
        });
    </script>
</body>
</html>