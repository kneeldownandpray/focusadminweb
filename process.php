<?php
session_start();
include 'db.php';

// ADD RECORD
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $started_date = date('F j, Y', strtotime($_POST['started_date'])); // Format: March 14, 2025
    $description = $_POST['description'];
    $date_posted = date('Y-m-d H:i:s');

    // Process Image Upload
    if ($_FILES['profile_picture']['error'] == 0) {
        $imageData = file_get_contents($_FILES['profile_picture']['tmp_name']);
        $base64Image = base64_encode($imageData);

        $stmt = $conn->prepare("INSERT INTO records (name, started_date, description, date_posted, profile_picture) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $started_date, $description, $date_posted, $base64Image);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Record added successfully!";
        } else {
            $_SESSION['error'] = "Failed to add record.";
        }
        $stmt->close();
    }
    header("Location: dashboard.php");
    exit();
}

// EDIT RECORD
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $started_date = date('F j, Y', strtotime($_POST['started_date'])); // Tamang format
    $description = $_POST['description'];

    if ($_FILES['profile_picture']['error'] == 0) {
        $imageData = file_get_contents($_FILES['profile_picture']['tmp_name']);
        $base64Image = base64_encode($imageData);

        $stmt = $conn->prepare("UPDATE records SET name=?, started_date=?, description=?, profile_picture=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $started_date, $description, $base64Image, $id);
    } else {
        $stmt = $conn->prepare("UPDATE records SET name=?, started_date=?, description=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $started_date, $description, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['message'] = "Record updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update record.";
    }
    $stmt->close();
    header("Location: dashboard.php");
    exit();
}


// DELETE RECORD
if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM records WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Record deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete record.";
    }
    $stmt->close();
    header("Location: dashboard.php");
    exit();
}
?>
