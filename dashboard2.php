<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
include 'db.php';

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Pagbilang ng total recordsOfMigrants (kasama ang search)
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM recordsofmigrants WHERE name LIKE ?");
    $search_param = "%{$search}%";
    $stmt->bind_param("s", $search_param);
} else {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM recordsofmigrants");
}
$stmt->execute();
$stmt->bind_result($total_recordsofmigrants);
$stmt->fetch();
$stmt->close();

$total_pages = ceil($total_recordsofmigrants / $limit);

// Kunin ang recordsOfMigrants gamit ang pagination at search filter
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM recordsofmigrants WHERE company_name LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("sii", $search_param, $limit, $offset);
} else {
    $stmt = $conn->prepare("SELECT * FROM recordsofmigrants ORDER BY id DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this record?");
        }
    </script>
</head>
<body>
<?php include 'nav.php'; ?>
    <div class="container mt-4">
        <!-- Search Form --> <h2><b>Top Employers</b></h2>
        <form method="GET" action="" class="mt-3" style="display:flex;">
            <input type="text" name="search" class="form-control" placeholder="Search by Name..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-success " style="margin-left:5px;">Search</button>
        </form>

        <div class="mt-2" style="display:flex; align-items: center;  width 100%;  justify-content: space-between;">
        <button type="button" style="display:flex; align-items: center;" class="btn btn-primary " onclick="openAddModal()">



<svg xmlns="http://www.w3.org/2000/svg" style="margin-right:10px;" width="20" height="20" fill="currentColor" class="bi bi-person-fill-add" viewBox="0 0 16 16">
  <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
  <path d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4"/>
</svg>
Add New Record
</button>
 <!-- Pagination Limit -->
 <div>
         <label for="limit">Show:</label>
        <select id="limit" onchange="changeLimit()" class="form-select w-auto d-inline-block mt-3">
            <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
            <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20</option>
            <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
            <option value="250" <?= $limit == 250 ? 'selected' : '' ?>>250</option>
            <option value="1000" <?= $limit == 1000 ? 'selected' : '' ?>>1000</option>
        </select>
        </div>
</div>
        <!-- Add Record Form -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add New Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="process2.php" method="POST" enctype="multipart/form-data">
                    <input type="text" name="company_name" class="form-control mb-2" placeholder="Name" required>
                    <input type="date" name="departure_date" class="form-control mb-2" required>
                    <textarea name="description" class="form-control mb-2" placeholder="Description" required></textarea>
                    <textarea name="link" class="form-control mb-2" placeholder="Link Of Company(Optional)" ></textarea>
                    <input type="file" name="company_picture" class="form-control mb-2" accept="image/*" required>
                    <button type="submit" name="add" class="btn btn-success">Add Record</button>
                </form>
            </div>
        </div>
    </div>
</div>
       

        <!-- RecordsOfMigrants Table -->
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Date of partnership</th>
                    <th>Description</th>
                    <th>Date Posted</th>
                    <th>Company Link</th>
                    <th>Profile Picture</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']); ?></td>
                        <td><?= htmlspecialchars($row['company_name']); ?></td>
                        <td><?= date('F j, Y', strtotime($row['departure_date'])); ?></td>
                        <td><?= htmlspecialchars($row['description']); ?></td>
                        <td><?= date('F j, Y', strtotime($row['date_posted'])); ?></td>
                        <td><?= htmlspecialchars($row['link']); ?></td>
                                    <td><img src="data:image/png;base64,<?= htmlspecialchars($row['company_picture']); ?>" width="150"></td>
                        <td>  
                            <button class="btn btn-primary" onclick="openEditModal(<?= htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                            <form action="process2.php" method="POST" class="d-inline" onsubmit="return confirmDelete()">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']); ?>">
                                <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>


    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="process2.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="edit-id">
                        <input type="text" name="company_name" id="edit-name" class="form-control mb-2" required>
                        <input type="date" name="departure_date" id="edit-departure" class="form-control mb-2" required>
                        <textarea name="description" id="edit-description" class="form-control mb-2" required></textarea>
                        <input type="text" name="link" id="edit-link" class="form-control mb-2" required>
                        <input type="file" name="company_picture" class="form-control mb-2" accept="image/*">
                        <button type="submit" name="edit" class="btn btn-success">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


        <!-- Pagination -->
         <div style="display:flex; width 100%;  justify-content: space-between;">
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>

         <!-- Pagination Limit -->
          <div>
         <label for="limit">Show:</label>
        <select id="limit" onchange="changeLimit()" class="form-select w-auto d-inline-block mb-3">
            <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
            <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20</option>
            <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
            <option value="250" <?= $limit == 250 ? 'selected' : '' ?>>250</option>
            <option value="1000" <?= $limit == 1000 ? 'selected' : '' ?>>1000</option>
        </select>
        </div>
    </div>
    </div>

    <script>
    function openAddModal() {
        var addModal = new bootstrap.Modal(document.getElementById("addModal"));
        addModal.show();
    }

    function openEditModal(record) {
        document.getElementById("edit-id").value = record.id;
        document.getElementById("edit-name").value = record.company_name;
        document.getElementById("edit-link").value = record.link;
        document.getElementById("edit-departure").value = record.departure_date;
        document.getElementById("edit-description").value = record.description;

        var editModal = new bootstrap.Modal(document.getElementById("editModal"));
        editModal.show();
    }

    function changeLimit() {
        let limit = document.getElementById("limit").value;
        let search = document.querySelector("input[name='search']") ? document.querySelector("input[name='search']").value : '';
        window.location.href = "?limit=" + limit + "&page=1&search=" + encodeURIComponent(search);
    }
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
