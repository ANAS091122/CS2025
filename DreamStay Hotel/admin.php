<?php
require 'data.php';

// Handle room addition form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_room'])) {
    $roomNumber = $_POST['room_number'];
    $roomType = $_POST['room_type'];
    $price = $_POST['price_per_night'];

    $stmt = $pdo->prepare("INSERT INTO rooms (room_number, room_type, price_per_night) VALUES (?, ?, ?)");
    $stmt->execute([$roomNumber, $roomType, $price]);
    $message = "Room added successfully!";
}

// Handle room deletion
if (isset($_GET['delete_room'])) {
    $roomId = $_GET['delete_room'];
    $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = ?");
    $stmt->execute([$roomId]);
    header("Location: admin.php");
    exit;
}

// Fetch all rooms
$rooms = $pdo->query("SELECT * FROM rooms")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all bookings
$bookings = $pdo->query("
    SELECT b.*, r.room_number, g.name AS guest_name
    FROM bookings b
    JOIN rooms r ON b.room_id = r.id
    JOIN guests g ON b.guest_id = g.id
    ORDER BY b.checkin_date DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hotel Admin Panel</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        h2 { margin-top: 40px; }
        form input[type="text"], form input[type="number"] {
            padding: 5px; width: 200px; margin-right: 10px;
        }
        form input[type="submit"] {
            padding: 5px 15px;
        }
        .message {
            padding: 10px;
            background-color: #dff0d8;
            color: #3c763d;
            margin-bottom: 20px;
            border: 1px solid #d6e9c6;
        }
    </style>
</head>
<body>

<h1>Hotel Management - Admin Panel</h1>

<?php if (!empty($message)): ?>
    <div class="message"><?php echo $message; ?></div>
<?php endif; ?>

<h2>Add New Room</h2>
<form method="POST" action="admin.php">
    <input type="text" name="room_number" placeholder="Room Number" required>
    <input type="text" name="room_type" placeholder="Room Type" required>
    <input type="number" name="price_per_night" placeholder="Price per Night" required>
    <input type="submit" name="add_room" value="Add Room">
</form>

<h2>All Rooms</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Room Number</th>
        <th>Room Type</th>
        <th>Price per Night</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($rooms as $room): ?>
    <tr>
        <td><?php echo $room['id']; ?></td>
        <td><?php echo htmlspecialchars($room['room_number']); ?></td>
        <td><?php echo htmlspecialchars($room['room_type']); ?></td>
        <td>$<?php echo number_format($room['price_per_night'], 2); ?></td>
        <td>
            <a href="edit_room.php?id=<?php echo $room['id']; ?>">Edit</a> |
            <a href="admin.php?delete_room=<?php echo $room['id']; ?>" onclick="return confirm('Are you sure you want to delete this room?');">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<h2>All Bookings</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Guest Name</th>
        <th>Room Number</th>
        <th>Check-in Date</th>
        <th>Check-out Date</th>
        <th>Actions</th>