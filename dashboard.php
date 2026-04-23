<?php
include "db.php";
include "header.php";

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

// ================= STATISTICS =================
$total_services = $conn->query("SELECT COUNT(*) as count FROM services")->fetch_assoc()['count'];
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$my_services = $conn->query("SELECT COUNT(*) as count FROM services WHERE user_id=$user_id")->fetch_assoc()['count'];

// ================= ADD SERVICE =================
if(isset($_POST['add'])){

    $title = $_POST['title'];
    $desc  = $_POST['description'];
    $price = $_POST['price'];

    $fileName = $_FILES['image']['name'];
    $tmp      = $_FILES['image']['tmp_name'];
    $size     = $_FILES['image']['size'];

    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png'];

    if(!in_array($ext,$allowed)){
        echo "<script>alert('Only JPG/PNG allowed');</script>";
    }
    elseif($size > 2000000){
        echo "<script>alert('Max file 2MB');</script>";
    }
    else{

        if(!file_exists("uploads")){
            mkdir("uploads",0777,true);
        }

        $newName = time()."_".rand(1000,9999).".".$ext;

        if(move_uploaded_file($tmp,"uploads/".$newName)){

            $stmt = $conn->prepare("
                INSERT INTO services(user_id,title,description,price,image)
                VALUES (?,?,?,?,?)
            ");
            $stmt->bind_param("issds",$user_id,$title,$desc,$price,$newName);
            $stmt->execute();

            echo "<script>
                alert('Service Added!');
                window.location='dashboard.php';
            </script>";

        }
    }
}

// ================= HISTORY =================
$history = $conn->prepare("SELECT * FROM services WHERE user_id=? ORDER BY id DESC");
$history->bind_param("i",$user_id);
$history->execute();
$resHist = $history->get_result();
?>

<!-- ================= DASHBOARD ================= -->

<div class="mb-4">
    <h2 class="fw-bold">Dashboard</h2>
    <p class="text-muted">Manage your campus services</p>
</div>

<!-- STATISTICS -->
<div class="row mb-4">

    <div class="col-md-4">
        <div class="card p-3 text-white shadow-sm"
             style="background:linear-gradient(135deg,#6c5ce7,#a29bfe); border-radius:15px;">
            <small>Total Services</small>
            <h3><?= $total_services ?></h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3 text-white shadow-sm"
             style="background:linear-gradient(135deg,#00b894,#55efc4); border-radius:15px;">
            <small>My Services</small>
            <h3><?= $my_services ?></h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3 text-white shadow-sm"
             style="background:linear-gradient(135deg,#fd79a8,#e84393); border-radius:15px;">
            <small>Total Users</small>
            <h3><?= $total_users ?></h3>
        </div>
    </div>

</div>

<!-- ADD SERVICE -->
<div class="card p-4 shadow-sm mb-4" style="border-radius:15px;">
<h4>Add Service</h4>

<form method="POST" enctype="multipart/form-data">

    <input class="form-control mb-2" name="title" placeholder="Title" required>

    <input class="form-control mb-2" name="price" type="number" placeholder="Price" required>

    <textarea class="form-control mb-2" name="description" placeholder="Description"></textarea>

    <input class="form-control mb-2" type="file" name="image" required>

    <button class="btn btn-primary w-100" name="add">
        Post Service
    </button>

</form>
</div>

<!-- SEARCH -->
<div class="mb-3">
    <input class="form-control" id="search" placeholder="Search service...">
</div>

<div id="result"></div>

<script>
document.getElementById("search").addEventListener("keyup", function(){
    let q = this.value;

    fetch("search.php?q=" + q)
    .then(res => res.text())
    .then(data => {
        document.getElementById("result").innerHTML = data;
    });
});
</script>

<!-- ================= HISTORY ================= -->

<div class="mt-5">
<h4 class="fw-bold">History</h4>

<?php if($resHist->num_rows > 0){ ?>

<?php while($row = $resHist->fetch_assoc()){ ?>

<div class="card mb-3 border-0 shadow-sm"
     style="border-radius:15px; overflow:hidden;">

    <div class="row g-0 align-items-center">

        <!-- IMAGE -->
        <div class="col-md-2">
            <img src="uploads/<?= $row['image'] ?>"
                 style="width:100%; height:100px; object-fit:cover;">
        </div>

        <!-- INFO -->
        <div class="col-md-7 p-3">

            <h5><?= htmlspecialchars($row['title']) ?></h5>
            <p class="text-muted small"><?= htmlspecialchars($row['description']) ?></p>
            <b class="text-success">RM <?= $row['price'] ?></b>

        </div>

        <!-- BUTTON -->
        <div class="col-md-3 p-3">

            <div class="d-grid gap-2">

                <a href="view_service.php?id=<?= $row['id'] ?>"
                   class="btn btn-info btn-sm text-white">
                    View
                </a>

                <a href="edit.php?id=<?= $row['id'] ?>"
                   class="btn btn-warning btn-sm">
                   ✏ Edit
                </a>

                <a href="delete.php?id=<?= $row['id'] ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Delete?')">
                    Delete
                </a>

            </div>

        </div>

    </div>

</div>

<?php } ?>

<?php } else { ?>

<p class="text-muted">No history yet</p>

<?php } ?>

</div>

<?php include "footer.php"; ?>