<?php
session_start();
include 'db.php';

// ADD RECORD
if (isset($_POST['add'])) {
    $name = $_POST['company_name'];
    $departure_date = date('F j, Y', strtotime($_POST['departure_date'])); // Format: March 14, 2025
    $description = $_POST['description'];
    $date_posted = date('Y-m-d H:i:s');
    $link = !empty($_POST['link']) ? $_POST['link'] : "404";
    // Process Image Upload
    if ($_FILES['company_picture']['error'] == 0) {
        $imageData = file_get_contents($_FILES['company_picture']['tmp_name']);
        $base64Image = base64_encode($imageData);

        $stmt = $conn->prepare("INSERT INTO recordsofmigrants (company_name, departure_date, description, date_posted, company_picture,link) VALUES (?, ?, ?, ?, ?,?)");
        $stmt->bind_param("ssssss", $name, $departure_date, $description, $date_posted, $base64Image,$link);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Record added successfully!";
        } else {
            $_SESSION['error'] = "Failed to add record.";
        }
        $stmt->close();
    }
    header("Location: dashboard2.php");
    exit();
}

// EDIT RECORD
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $name = $_POST['company_name'];
    $departure_date = date('F j, Y', strtotime($_POST['departure_date'])); // Tamang format
    $description = $_POST['description'];
    $link = $_POST['link'];

    if ($_FILES['company_picture']['error'] == 0) {
        $imageData = file_get_contents($_FILES['company_picture']['tmp_name']);
        $base64Image = base64_encode($imageData);

        $stmt = $conn->prepare("UPDATE recordsofmigrants SET company_name=?, departure_date=?, description=?, link=?, company_picture=? WHERE id=?");
        $stmt->bind_param("sssssi", $name, $departure_date, $description,$link, $base64Image, $id);
    } else {
        $stmt = $conn->prepare("UPDATE recordsofmigrants SET company_name=?, departure_date=?, link=?, description=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $departure_date, $link, $description, $id);
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
    $stmt = $conn->prepare("DELETE FROM recordsofmigrants WHERE id=$id");
    // $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Record deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete record.";
    }
    $stmt->close();
    header("Location: dashboard2.php");
    exit();
}
?>
