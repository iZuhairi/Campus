<?php
include "db.php";

$q = $_GET['q'] ?? '';
session_start();

$user_id = $_SESSION['id'] ?? 0;
$role = $_SESSION['role'] ?? 'user';

$stmt = $conn->prepare("
    SELECT services.*, users.name
    FROM services
    JOIN users ON services.user_id = users.id
    WHERE services.title LIKE CONCAT('%', ?, '%')
    ORDER BY services.id DESC
");
$stmt->bind_param("s", $q);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows > 0){

while($row = $res->fetch_assoc()){
?>

<!-- CARD DESIGN PREMIUM -->
<div class="card mb-3 border-0 shadow-sm"
     style="border-radius:18px; overflow:hidden;">

    <div class="row g-0 align-items-center">

        <!-- IMAGE -->
        <div class="col-md-3">
            <img src="uploads/<?= htmlspecialchars($row['image']) ?>" 
                 style="width:100%; height:140px; object-fit:cover;">
        </div>

        <!-- INFO -->
        <div class="col-md-6 p-3">

            <h5 class="fw-bold mb-1">
                <?= htmlspecialchars($row['title']) ?>
            </h5>

            <p class="text-muted small mb-2">
                <?= htmlspecialchars($row['description']) ?>
            </p>

            <span class="fw-bold text-success fs-5">
                RM <?= $row['price'] ?>
            </span>

            <div class="mt-2 text-secondary small">
                👤 <?= htmlspecialchars($row['name']) ?>
            </div>

        </div>

        <!-- BUTTON -->
        <div class="col-md-3 p-3">

            <div class="d-grid gap-2">

                <!-- VIEW -->
                <a href="view_service.php?id=<?= $row['id'] ?>" 
                   class="btn btn-info btn-sm text-white fw-bold"
                   style="border-radius:10px;">
                    View
                </a>

                <?php if($row['user_id'] == $user_id || $role == 'admin'){ ?>

                <!-- EDIT -->
                <a href="edit.php?id=<?= $row['id'] ?>" 
                   class="btn btn-warning btn-sm fw-bold"
                   style="border-radius:10px;">
                    Edit
                </a>

                <!-- DELETE -->
                <a href="delete.php?id=<?= $row['id'] ?>" 
                   class="btn btn-danger btn-sm fw-bold"
                   style="border-radius:10px;"
                   onclick="return confirm('Delete this service?')">
                    Delete
                </a>

                <?php } ?>

            </div>

        </div>

    </div>

</div>

<?php
}

}else{
?>

<!-- EMPTY STATE -->
<div class="text-center mt-5">
    <div class="card border-0 shadow-sm p-4"
         style="border-radius:15px;">

        <h1 class="text-muted"> No services found</h1>
        <p class="text-secondary small">Try different keywords</p>

    </div>
</div>

<?php
}
?>