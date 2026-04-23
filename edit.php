<?php
// DEBUG (optional - boleh buang nanti)
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db.php";
include "header.php";

// ==========================
// CHECK LOGIN
// ==========================
if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['id'];
$role = $_SESSION['role'];

// ==========================
// GET DATA
// ==========================
$stmt = $conn->prepare("SELECT * FROM services WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

// ==========================
// PERMISSION CHECK
// ==========================
if(!$data || ($data['user_id'] != $user_id && $role != 'admin')){
    echo "<h3 style='color:red'>Access Denied</h3>";
    exit();
}

// ==========================
// UPDATE PROCESS
// ==========================
if(isset($_POST['update'])){

    $title = htmlspecialchars($_POST['title']);
    $desc  = htmlspecialchars($_POST['description']);
    $price = $_POST['price'];

    $newImage = $data['image']; // default gambar lama

    // ======================
    // CHECK FILE UPLOAD
    // ======================
    if(!empty($_FILES['image']['name'])){

        $fileName = $_FILES['image']['name'];
        $fileTmp  = $_FILES['image']['tmp_name'];
        $fileSize = $_FILES['image']['size'];

        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png'];

        // VALIDATION
        if(!in_array($ext,$allowed)){
            echo "<script>alert('❌ Only JPG, JPEG, PNG allowed');</script>";
        }
        elseif($fileSize > 2000000){
            echo "<script>alert('❌ File too large (Max 2MB)');</script>";
        }
        else{

            $newImage = time()."_".rand(1000,9999).".".$ext;

            if(move_uploaded_file($fileTmp, "uploads/".$newImage)){

                // DELETE OLD IMAGE
                if(!empty($data['image']) && file_exists("uploads/".$data['image'])){
                    unlink("uploads/".$data['image']);
                }

            }else{
                echo "<script>alert('❌ Upload failed');</script>";
            }
        }
    }

    // ======================
    // UPDATE DATABASE
    // ======================
    $stmt = $conn->prepare("
        UPDATE services 
        SET title=?, description=?, price=?, image=? 
        WHERE id=?
    ");
    $stmt->bind_param("ssdsi",$title,$desc,$price,$newImage,$id);

    if($stmt->execute()){
        echo "<script>
            alert('✅ Service updated successfully');
            window.location='dashboard.php';
        </script>";
    }else{
        echo "Error: ".$stmt->error;
    }
}
?>

<!-- ================= UI ================= -->

<div class="card p-4 shadow-sm" style="border-radius:20px; max-width:600px; margin:auto;">

    <h4 class="mb-3">✏ Edit Service</h4>

    <!-- CURRENT IMAGE -->
    <div class="text-center mb-3">
        <img src="uploads/<?= htmlspecialchars($data['image']) ?>" 
             style="width:200px;height:150px;object-fit:cover;border-radius:10px;">
    </div>

    <form method="POST" enctype="multipart/form-data">

        <label class="fw-bold">Service Title</label>
        <input class="form-control mb-3" name="title"
            value="<?= htmlspecialchars($data['title']) ?>" required>

        <label class="fw-bold">Description</label>
        <textarea class="form-control mb-3" name="description"><?= htmlspecialchars($data['description']) ?></textarea>

        <label class="fw-bold">Price (RM)</label>
        <input class="form-control mb-3" name="price"
            type="number" step="0.01"
            value="<?= $data['price'] ?>" required>

        <label class="fw-bold">Change Image (optional)</label>
        <input class="form-control mb-3" type="file" name="image">

        <button type="submit" name="update"
            class="btn btn-primary w-100 fw-bold">
            Update Service
        </button>

    </form>

</div>

<?php include "footer.php"; ?>