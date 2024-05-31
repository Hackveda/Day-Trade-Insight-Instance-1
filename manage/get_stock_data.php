<?php
include("../db.php");

if(isset($_POST["symbol"]) && strcmp($_POST["symbol"], '') != 0){
    
    $symbol = $_POST["symbol"];
    $recommend = $_POST["recommend"];
    
    $sql = "SELECT * from stock where `Symbol` like '".$symbol."' and Recommend like '".$recommend."'";
    $result = $con1->query($sql);
    
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            
            $symbol = $row["Symbol"];
            $target = $row["Target"];
            $signal = $row["Signal"];
            $strike = $row["Strike"];
            $recommend = $row["Recommend"];
            $entry_price = $row["EntryPrice"];
            $option_target = $row["OptionTarget"];
            $fundNeeded = $row["FundNeed"];
            $view = $row["View"];
            
            echo '<div class="form-row"><div class="form-group col-md-6"><label for="stockSymbol">Stock Symbol</label>
            <input type="text" class="form-control" id="edit_stockSymbol" name="stockSymbol" required value="'.$symbol.'">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="target">Target</label>
                            <input type="number" value="'.$target.'" class="form-control" id="edit_target" name="target" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="signal">Signal</label>
                            <select class="form-control" id="edit_signal" name="signal" required>';
                            
                            if(strcmp($signal , "Buy") == 0){
                                echo '<option value="Buy" selected>Buy</option>
                                <option value="Sell">Sell</option>
                                <option value="Hold">Hold</option>';
                                
                            }else if(strcmp($signal, "Sell") == 0){
                                echo '<option value="Buy">Buy</option>
                                <option value="Sell" selected>Sell</option>
                                <option value="Hold">Hold</option>';
                            }
                            
                            echo '</select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="target">Strike</label>
                            <input type="number" value="'.$strike.'" class="form-control" id="edit_strike" name="strike" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label for="recommendation">Recommendation</label>
                            <input type="text" class="form-control" value="'.$recommend.'" id="edit_recommendation" name="recommendation" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="optionTarget">Entry Price</label>
                            <input type="number" class="form-control" value="'.$entry_price.'" id="edit_entryPrice" name="entryPrice" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="optionTarget">Option Target</label>
                            <input type="number" class="form-control" value="'.$option_target.'" id="edit_optionTarget" name="optionTarget" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="fundNeeded">Fund Needed</label>
                            <input type="number" class="form-control" value="'.$fundNeeded.'" id="edit_fundNeeded" name="fundNeeded" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="optionTarget">Target View</label>
                            <select class="form-control" id="edit_targetView" name="targetView" required>
                                <option value="1-2 Days">1-2 Days</option>
                                <option value="2-3 Days">2-3 Days</option>
                                <option value="3-5 Days">3-5 Days</option>
                                <option value="Expiry Day">Expiry Day</option>
                                <option value="1-2 Months">1-2 Months</option>
                                <option value="6 Months">6 Months</option>
                                <option value="1 Year">1 Year</option>
                                <option value="5 Year">5 Year</option>
                            </select>
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