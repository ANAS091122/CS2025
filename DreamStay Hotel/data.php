<?php

$host = 'localhost';
$dbname = 'hotel_db';
$username = 'root';
$password = '';

// Create a database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch available rooms
function getAvailableRooms($pdo, $checkin, $checkout) {
    $sql = "
        SELECT r.id, r.room_number, r.room_type, r.price_per_night
        FROM rooms r
        WHERE r.id NOT IN (
            SELECT b.room_id FROM bookings b
            WHERE NOT (b.checkout_date <= :checkin OR b.checkin_date >= :checkout)
        )
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['checkin' => $checkin, 'checkout' => $checkout]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch all room types
function getRoomTypes($pdo) {
    $stmt = $pdo->query("SELECT DISTINCT room_type FROM rooms");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Add a new booking
function addBooking($pdo, $customerName, $roomId, $checkin, $checkout) {
    $sql = "INSERT INTO bookings (customer_name, room_id, checkin_date, checkout_date)
            VALUES (:customer_name, :room_id, :checkin, :checkout)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'customer_name' => $customerName,
        'room_id' => $roomId,
        'checkin' => $checkin,
        'checkout' => $checkout
    ]);
    return $pdo->lastInsertId();
}
?>