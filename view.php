<?php
include "db.php";
include "header.php";

if(isset($_GET['id'])){
    $id = $_GET['id'];
    
    // Ambil data servis dan nama penjual menggunakan JOIN
    $stmt = $conn->prepare("SELECT services.*, users.username FROM services JOIN users ON services.user_id = users.id WHERE services.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();

    if(!$service){
        echo "<div class='alert alert-danger'>Service not found!</div>";
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>

<div class="container mt-4">
    <a href="dashboard.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Back</a>
    
    <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
        <div class="row g-0">
            <div class="col-md-6">
                <img src="uploads/<?= $service['image'] ?>" class="img-fluid h-100" style="object-fit: cover;" alt="Service Image">
            </div>
            <div class="col-md-6 p-5">
                <h1 class="fw-bold"><?= $service['title'] ?></h1>
                <p class="text-muted">Posted by: <strong><?= $service['username'] ?></strong></p>
                <hr>
                <h3 class="text-success fw-bold">RM <?= number_format($service['price'], 2) ?></h3>
                <div class="mt-4">
                    <h5>Description:</h5>
                    <p class="text-secondary"><?= nl2br($service['description']) ?></p>
                </div>
                
                <div class="mt-5">
                    <button class="btn btn-primary btn-lg w-100" style="border-radius: 10px;">Contact Seller</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>