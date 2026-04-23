<?php
include "db.php";
include "header.php";

// ==========================
// CHECK LOGIN (OPTIONAL)
// ==========================
if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit();
}

// ==========================
// CHECK ID
// ==========================
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    echo "<div class='alert alert-danger'>❌ Invalid request</div>";
    exit();
}

$id = $_GET['id'];

// ==========================
// GET DATA (FIXED: users.name)
// ==========================
$stmt = $conn->prepare("
    SELECT services.*, users.name 
    FROM services 
    JOIN users ON services.user_id = users.id 
    WHERE services.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$service = $result->fetch_assoc();

// ==========================
// CHECK DATA EXIST
// ==========================
if(!$service){
    echo "<div class='alert alert-danger'>❌ Service not found</div>";
    exit();
}
?>

<div class="container mt-4">

    <a href="dashboard.php" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Back
    </a>

    <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">

        <div class="row g-0">

            <!-- IMAGE -->
            <div class="col-md-6">
                <img src="uploads/<?= htmlspecialchars($service['image']) ?>" 
                     class="img-fluid w-100"
                     style="height:100%; object-fit:cover;">
            </div>

            <!-- DETAILS -->
            <div class="col-md-6 p-5">

                <h1 class="fw-bold"><?= htmlspecialchars($service['title']) ?></h1>

                <p class="text-muted">
                    Posted by: <strong><?= htmlspecialchars($service['name']) ?></strong>
                </p>

                <hr>

                <h3 class="text-success fw-bold">
                    RM <?= number_format($service['price'], 2) ?>
                </h3>

                <div class="mt-4">
                    <h5>Description:</h5>
                    <p class="text-secondary">
                        <?= nl2br(htmlspecialchars($service['description'])) ?>
                    </p>
                </div>

                <!-- BUTTON -->
                <div class="mt-5">

                    <?php if($service['user_id'] == $_SESSION['id'] || $_SESSION['role'] == 'admin'){ ?>

                    <a href="edit.php?id=<?= $service['id'] ?>" 
                       class="btn btn-warning btn-lg w-100"
                       style="border-radius:12px;">
                       <i class="bi bi-pencil-square"></i> Edit
</a>

<?php } else { ?>

<button class="btn btn-secondary btn-lg w-100" disabled>
   🔒 Not Allowed
</button>

<?php } ?>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include "footer.php"; ?>