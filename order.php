<?php
/* Page create new Order push to Firebase */

include_once( 'config.php' );
$sql = "SELECT schema_name as store_name
FROM information_schema.schemata
WHERE schema_name LIKE 'demo_%'";
$lDB = $db->executeQuery_list( $sql );
?>

<html>
    <head>
       <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <style>
            .header{
                color: white;
                background: #46799F;
                padding: 10px;
                text-align: center;
            }
            .content{
                background: #ccc;
                margin: 10px;
                padding: 20px;
                border-radius: 5px;
            }
            button, input, select{
                margin-top:10px;
            }
            .red{
                color: red;
            }
        </style>
    </head>
    <body>
        <div class="row">
            <div class="col-sm-12 text-center header">
                <h4>Demo Project: New Order to Firebase</h4>
            </div>
            
            <div class="col-sm-4">
                <a href="/">
                    <button class="btn btn-warning">Create new DB</button>
                </a>
            </div>
            
            <div class="col-sm-4 content">
                <div class="col-sm-12">
                    <strong>Create new order (One order One Item)</strong>
                </div>
                <div class="col-sm-12">
                    <div class="col-sm-12">
                        <div class="col-sm-12">
                            <select id="store_name" name="store_name" class="form-control">
                                    <?php
                                        foreach ($lDB as $key => $item) {
                                            echo '<option>'.$item['store_name'].'</option>';
                                        }
                                    ?>
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <input id="item_name" class="form-control" type="text" name="item_name" placeholder="Item Name" value="">
                        </div>
                        <div class="col-sm-12">
                            <input id="quantity" class="form-control" type="number" name="quantity" placeholder="Quantity" value="">
                        </div>
                        <div class="col-sm-12">
                            <input id="price" class="form-control" type="number" name="price" placeholder="Price" value="">
                        </div>
                        <div class="col-sm-12">
                            <button onclick="create_order();" id="btn_create" name="submit" class="btn btn-success">
                                Create order
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <strong class="red">
                        - Note: After the order from a pariticular store is pushed to Firebase, it's expected to be listened by NodeJS and will be updated to that store on MySQL accordingly. Three basic events need to be listened on Firebase: child_added, child_changed & child_removed.
                    </strong>
                </div>
            </div>
        </div>
    </body>
    <script src="https://www.gstatic.com/firebasejs/5.0.4/firebase.js"></script>
    <script >
        // Initialize Firebase
        var config = {
            apiKey: "AIzaSyBcfnbM9H-gFKEWImY3LIQEe291x4Zuts0",
            authDomain: "demoproject-d1e73.firebaseapp.com",
            databaseURL: "https://demoproject-d1e73.firebaseio.com",
            projectId: "demoproject-d1e73",
            storageBucket: "demoproject-d1e73.appspot.com",
            messagingSenderId: "635727947228"
        };
        firebase.initializeApp(config);
        
        function create_order(){

            var store_name      = document.getElementById("store_name").value;
            var item_name       = document.getElementById("item_name").value;
            var quantity        = document.getElementById("quantity").value;
            var price           = document.getElementById("price").value;

            if( store_name != null && item_name != '' && parseInt(quantity) > 0 && parseInt(price) > 0  ){

                const order_list = {
                        id: 'storeID'+parseInt(Date.now()/1000),
                        created_at: parseInt(Date.now()/1000)+''
                }
                
                const order_item = {
                        id: 'storeID'+parseInt(Date.now()/1000),
                        order_list_id: order_list.id,
                        item_name,
                        quantity,
                        price
                }
                
                firebase.database().ref( store_name+'/order_list/'+order_list.id ).set( order_list );
                firebase.database().ref( store_name+'/order_item/'+order_item.id ).set( order_item );
                
                document.getElementById("item_name").value = '';
                document.getElementById("quantity").value = '';
                document.getElementById("price").value = '';
                
                alert("Order created!");
            }else{
                alert('INVALID_INPUT');
            }
        }
    </script>
</html>