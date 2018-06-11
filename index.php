<?php
/* Page create new Store */
include_once( 'config.php' );

if( isset($_POST['submit']) && isset($_POST['store_name']) && $_POST['store_name'] != '' ){
    //create new store
    $store_name = $_POST['store_name'];
    $newDB = 'demo_'.$store_name;

    $sql = "CREATE DATABASE $newDB;";
    $db->executeQuery( $sql );
    
    $db->setDatabase ( $newDB );
    $db->connect();

    $sql = "CREATE TABLE $newDB.`order_item` (
            `id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
            `item_name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
            `quantity` int(11) NOT NULL,
            `price` int(11) NOT NULL,
            `order_list_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
    $db->executeQuery( $sql );
    
    $sql = "CREATE TABLE $newDB.`order_list` (
            `id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
            `created_at` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
    $db->executeQuery( $sql );
    
    $order_list->set('id', $store_name.'001');
    $order_list->add();

    $order_item->set('id', $store_name.'001');
    $order_item->set('item_name', 'Shoes');
    $order_item->set('quantity', 1);
    $order_item->set('price', 250);
    $order_item->set('order_list_id', $store_name.'001');
    $order_item->add();

    $order_item->set('id', $store_name.'002');
    $order_item->set('item_name', 'Shirt');
    $order_item->set('quantity', 2);
    $order_item->set('price', 45);
    $order_item->set('order_list_id', $store_name.'001');
    $order_item->add();
    
    $kq['order_list'] = $order_list->list_all();
    $kq['order_item'] = $order_item->list_all();
    
    $firebase->set('/'.$newDB, $kq );

    unset( $kq );
    
    $msg = 'Success!';
}

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
            .left{
                background: #ccc;
                margin: 10px;
                padding: 20px;
                border-radius: 5px;
            }
            .right{
                background: #888888;
                padding: 10px;
                border-radius: 5px;
                margin: 10px;
                min-height: 108px;
                color: #fff;
            }
            button{
                margin-top:5px;
            }
        </style>
    </head>
    <body>
        <div class="row">
            <div class="col-sm-12 text-center header">
                <h4>Demo Project: Create new DB</h4>
            </div>
            <div class="col-sm-3">
                <a href="/order.php">
                    <button class="btn btn-warning">Create new Order</button>
                </a>
            </div>
            <div class="col-sm-3 left">
                <div class="col-sm-12">
                    <strong><?php if( isset($msg) ) echo  $msg; else echo 'Create new store' ?></strong>
                </div>
                <div class="col-sm-12">
                    <form name="newStore" method="POST">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3">demo_</span>
                            </div>
                            <input type="text" class="form-control" name="store_name" placeholder="Store Name" value="">
                        </div>
                        <button name="submit" class="btn btn-success">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-sm-3 right">
                <div class="col-sm-12">
                    <strong>The Stores</strong>
                </div>
                <div class="col-sm-12">
                    <ul>
                        <?php
                            foreach ($lDB as $key => $item) {
                                echo '<li>'.$item['store_name'].'</li>';
                            }
                        ?>
                    </ul>
                </div>
            </div>
            </div>
        </div>
    </body>
</html>