<?php
include '../db/connect.php';

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $system_id = intval($data['system_id']);
    $duration = intval($data['duration']);
    $amount = floatval($data['amount']);
    $start_time = date('Y-m-d H:i:s', strtotime("-{$duration} seconds"));
    $end_time = date('Y-m-d H:i:s');

    $query = "INSERT INTO payments (system_id, start_time, end_time, amount, status) 
              VALUES ($system_id, '$start_time', '$end_time', $amount, 'paid')";

    mysqli_query($conn, $query);
}
