<?php
session_start();
include("../db.php");

if(isset($_SESSION["username"])){
    $username = $_SESSION["username"];
}

if(isset($_POST["symbol"]) && strcmp($_POST["symbol"], '') != 0){
    
    $symbol = $_POST["symbol"];
    $recommend = $_POST["recommend"];
    
    $sql = "SELECT * from stock where `Symbol` like '".$symbol."' and `Recommend` like '".$recommend."'";
    $result = $con1->query($sql);
    
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $symbol = $row["Symbol"];
            $recommend = $row["Recommend"];
            $price = $row["Price"];
            $op_price = $row["OptionPrice"];
            $lotSize = $row["LotSize"];
            $qty = "";
            $op_qty = '';
            
            $sql1 = "SELECT * from funds where Username like '".$username."'";
            $result1 = $con1->query($sql1);
            
            $funds = "";
            
            if($result1->num_rows > 0){
                while($row = $result1->fetch_assoc()){
                    $funds = $row["Funds"];
                    $funds = number_format($funds, 2, '.', ',');
                }
            }else{
                $funds = "0.0";   
            }
            
            $sql2 = "SELECT * from orders where `Username` like '".$username."' and `Symbol` like '".$symbol."' and `SellStatus` = 'True'";
            
            $result2 = $con1->query($sql2);
            
            if($result2->num_rows > 0){
                while($row = $result2->fetch_assoc()){
                    $qty = $row["BuyQty"];
                }
            }else{
                // Do nothing
                $qty = 0;
            }
            
            $sql3 = "SELECT * from orders where `Username` like '".$username."' and `Recommend` like '".$recommend."' and `SellOpStatus` = 'True'";
            
            $result3 = $con1->query($sql3);
            
            if($result3->num_rows > 0){
                while($row = $result3->fetch_assoc()){
                    $op_qty = $row["BuyOpQty"];
                }
            }else{
                // Do nothing
                $op_qty = 0;
            }
            
            echo '<div class="form-row">
            
                        <div class="form-group col-md-6">
                            <label for="stockSymbol">Stock Symbol</label>
                            <input type="text" class="form-control" id="stockSymbol" name="stockSymbol" value="'.$symbol.'" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="target">Price</label>
                            <input type="number" class="form-control" id="price" name="price" value="'.$price.'" required>
                        </div>
                        
                        <div class="form-group col-md-3">
                            <label for="target">Quantity</label>
                            <input type="number" class="form-control qty" id="qty" name="qty" value="'.$qty.'" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                    <div class="form-group col-md-6">
                            <label for="stockSymbol"><b>Margin: </b> Rs</label>
                            <label id="margin">0.0</label>
                    </div>
                    
                    <div class="form-group col-md-6">
                            <label for="stockSymbol"><b>Funds: </b></label>
                            <label id="funds">Rs '.$funds.'</label>
                    </div>
                    
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="stockSymbol">Stock Symbol</label>
                            <input type="text" class="form-control" id="recommend" name="recommend" value="'.$recommend.'" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="target">Price</label>
                            <input type="number" class="form-control" id="op_price" name="op_price" value="'.$op_price.'" required>
                        </div>
                        
                        <div class="form-group col-md-3">
                            <label for="target">LotSize</label>
                            <input type="number" class="form-control" id="lotSize" name="lotSize" value="'.$lotSize.'" required>
                        </div>
                        
                         <div class="form-group col-md-3">
                            <label for="target">Quantity</label>
                            <input type="number" class="form-control op-qty" id="op_qty" name="op_qty" value="'.$op_qty.'" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                    <div class="form-group col-md-6">
                            <label for="stockSymbol"><b>Margin: </b> Rs</label>
                            <label id="op_margin">0.0</label>
                    </div>
                    
                    <div class="form-group col-md-6">
                            <label for="stockSymbol"><b>Funds: </b></label>
                            <label id="funds">Rs '.$funds.'</label>
                    </div>
                    
                    </div>    
                    </div>';
            
        }
    }else{
        echo "No Symbol Found";
    }
    
    $con1->close();
    
}else{
    echo "No Data";
}


?>