<?php
include '../classes/class.receive.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'create':
        create_new_transaction();
        break;
    case 'additem':
        new_receive_item();
        break;
    case 'saveitem':
        save_receive_items();
        break;
}

function create_new_transaction()
{
    $receive = new Receive();
    $name = ucwords($_POST['sname']);
    $desc = ucwords($_POST['desc']);
    $stats = ucwords($_POST['stats']);
    $rid = $receive->new_receive($name, $desc, $stats);
    if (is_numeric($rid)) {
        header('Location: ../index.php?page=receive&action=receive&id=' . $rid);
        exit(); // Add exit() to prevent further execution
    } else {
        echo "Failed to create a new transaction.";
    }
}


function new_receive_item()
{
    $receive = new Receive();
    $recid = $_POST['recid'];
    $prodid = $_POST['prodid'];
    $qty = $_POST['qty'];
    $rid = $receive->new_receive_item($recid, $prodid, $qty);
    if ($rid) {
        header('location: ../index.php?page=receive&action=receive&id=' . $recid);
    }
}

function save_receive_items()
{
    $receive = new Receive();
    $id = $_POST['recid'];
    $receive->save_to_inventory($id);
    $rid = $receive->save_receive_items($id);
    if ($rid) {
        header('location: ../index.php?page=receive&action=receive&id=' . $id);
    }
}
