<?php
include_once('includes/functions.php');
date_default_timezone_set('Asia/Kolkata');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

if (isset($_GET['id'])) {
    $ID = $db->escapeString($fn->xss_clean($_GET['id']));
} else {
    return false;
    exit(0);
}

if (isset($_POST['btnUpdate'])) {
    $candidate_name = $db->escapeString($_POST['candidate_name']);
    $fathers_name = $db->escapeString($_POST['fathers_name']);
    $mothers_name = $db->escapeString($_POST['mothers_name']);
    $dob = $db->escapeString($_POST['dob']);
    $gender = $db->escapeString($_POST['gender']);
    $category_id = $db->escapeString($_POST['category_id']);
    $id_proof_type = $db->escapeString($_POST['id_proof_type']);
    $id_proof_no = $db->escapeString($_POST['id_proof_no']);
    $employeed = $db->escapeString($_POST['employeed']);
    $center_id = $db->escapeString($_POST['center_id']);

    $sql = "UPDATE admission SET candidate_name='$candidate_name', fathers_name='$fathers_name', mothers_name='$mothers_name', dob='$dob', gender='$gender', category_id='$category_id', id_proof_type='$id_proof_type', id_proof_no='$id_proof_no', employeed='$employeed', center_id='$center_id' WHERE id = '$ID'";
    $db->sql($sql);
    $result = $db->getResult();
    if (!empty($result)) {
        $error['update_slide'] = " <span class='label label-danger'>Failed</span>";
    } else {
        $error['update_slide'] = " <span class='label label-success'>Admission Updated Successfully</span>";
    }

    if ($_FILES['image']['size'] != 0 && $_FILES['image']['error'] == 0 && !empty($_FILES['image'])) {
        $old_image = $db->escapeString($_POST['old_image']);
        $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

        $result = $fn->validate_image($_FILES["image"]);
        $target_path = 'upload/images/';
        
        $filename = microtime(true) . '.' . strtolower($extension);
        $full_path = $target_path . $filename;
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $full_path)) {
            echo '<p class="alert alert-danger">Cannot upload image.</p>';
            return false;
            exit();
        }
        if (!empty($old_image) && file_exists($old_image)) {
            unlink($old_image);
        }

        $upload_image = $full_path;
        $sql = "UPDATE admission SET image='$upload_image' WHERE id='$ID'";
        $db->sql($sql);

        $update_result = $db->getResult();
        if (!empty($update_result)) {
            $update_result = 0;
        } else {
            $update_result = 1;
        }

        if ($update_result == 1) {
            $error['update_slide'] = " <section class='content-header'><span class='label label-success'>Admission updated Successfully</span></section>";
        } else {
            $error['update_slide'] = " <span class='label label-danger'>Failed to update</span>";
        }
    }
}

$sql_query = "SELECT * FROM `admission` WHERE id = '$ID'";
$db->sql($sql_query);
$res = $db->getResult();
if (!isset($res[0])) {
    echo '<p class="alert alert-danger">Admission not found.</p>';
    exit();
}
?>

<section class="content-header">
    <h1>Edit Admission <small><a href='admission.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Admission</a></small></h1>
    <?php echo isset($error['update_slide']) ? $error['update_slide'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-10">
            <div class="box box-primary">
                <div class="box-header with-border">
                </div>
                <div class="box-header">
                    <?php echo isset($error['cancelable']) ? '<span class="label label-danger">Till status is required.</span>' : ''; ?>
                </div>

                <form id='edit_slide_form' method="post" enctype="multipart/form-data">
                    <div class="box-body">
                    <input type="hidden" name="old_image" value="<?php echo isset($res[0]['image']) ? $res[0]['image'] : ''; ?>">
                    <div class="row">
                        <div class="form-group">
                            <div class='col-md-4'>
                                <label for="candidate_name">Candidate Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['candidate_name']) ? $error['candidate_name'] : ''; ?>
                                <input type="text" class="form-control" name="candidate_name" id="candidate_name" value="<?php echo $res[0]['candidate_name']; ?>" required>
                            </div>
                            <div class='col-md-4'>
                                <label for="fathers_name">Father's Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['fathers_name']) ? $error['fathers_name'] : ''; ?>
                                <input type="text" class="form-control" name="fathers_name" id="fathers_name" value="<?php echo $res[0]['fathers_name']; ?>" required>
                            </div>
                            <div class='col-md-4'>
                                <label for="mothers_name">Mother's Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['mothers_name']) ? $error['mothers_name'] : ''; ?>
                                <input type="text" class="form-control" name="mothers_name" id="mothers_name" value="<?php echo $res[0]['mothers_name']; ?>" required>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group">
                            <div class='col-md-4'>
                                <label for="dob">Date of Birth</label> <i class="text-danger asterik">*</i><?php echo isset($error['dob']) ? $error['dob'] : ''; ?>
                                <input type="date" class="form-control" name="dob" id="dob" value="<?php echo $res[0]['dob']; ?>" required>
                            </div>
                            <div class='col-md-4'>
                               <label for="gender">Gender</label> <i class="text-danger asterik">*</i>
                               <select id='gender' name="gender" class='form-control' required>
                                  <option value='male' <?php echo ($res[0]['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                                  <option value='female' <?php echo ($res[0]['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                                  <option value='others' <?php echo ($res[0]['gender'] == 'others') ? 'selected' : ''; ?>>Others</option>
                               </select>
                            </div>
                            <div class="col-md-4">
                                <label for="image">Photo</label> <i class="text-danger asterik">*</i><?php echo isset($error['image']) ? $error['image'] : ''; ?>
                                <input type="file" name="image" onchange="readURL(this);" accept="image/png,  image/jpeg" id="image" /><br>
                                <img id="blah" src="<?php echo $res[0]['image']; ?>" alt="" style="display:block; width:150px; height:200px;"/>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group">
                            <div class='col-md-4'>
                               <label for="category_id">Category</label> <i class="text-danger asterik">*</i>
                               <select id='category_id' name="category_id" class='form-control' required>
                                     <option value="">--Select--</option>
                                     <?php
                                      $sql = "SELECT id, name FROM `category`";
                                      $db->sql($sql);
                                       $result = $db->getResult();
                                      foreach ($result as $value) {
                                          ?>
                                     <option value='<?= $value['id'] ?>' <?= ($res[0]['category_id'] == $value['id']) ? 'selected' : ''; ?>><?= $value['name'] ?></option>
                                    <?php } ?>
                               </select>
                            </div>
                            <div class='col-md-4'>
                               <label for="id_proof_type">Id Proof Type</label> <i class="text-danger asterik">*</i>
                               <select id='id_proof_type' name="id_proof_type" class='form-control' required>
                                  <option value=''>Select Id Type</option>
                                  <option value='aadhaarcard' <?= ($res[0]['id_proof_type'] == 'aadhaarcard') ? 'selected' : ''; ?>>Aadhaar Card</option>
                                  <option value='hsc' <?= ($res[0]['id_proof_type'] == 'hsc') ? 'selected' : ''; ?>>HSC</option>
                                  <option value='sslc' <?= ($res[0]['id_proof_type'] == 'sslc') ? 'selected' : ''; ?>>SSLC</option>
                               </select>
                            </div>
                            <div class='col-md-4'>
                                <label for="id_proof_no">Id Proof No</label> <i class="text-danger asterik">*</i><?php echo isset($error['id_proof_no']) ? $error['id_proof_no'] : ''; ?>
                                <input type="text" class="form-control" name="id_proof_no" id="id_proof_no" value="<?php echo $res[0]['id_proof_no']; ?>" required>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group">
                            <div class='col-md-4'>
                               <label for="center_id">Center</label> <i class="text-danger asterik">*</i>
                               <select id='center_id' name="center_id" class='form-control' required>
                                     <option value="">--Select--</option>
                                     <?php
                                      $sql = "SELECT id, name FROM `center`";
                                      $db->sql($sql);
                                       $result = $db->getResult();
                                      foreach ($result as $value) {
                                          ?>
                                     <option value='<?= $value['id'] ?>' <?= ($res[0]['center_id'] == $value['id']) ? 'selected' : ''; ?>><?= $value['name'] ?></option>
                                    <?php } ?>
                               </select>
                            </div>
                            <div class='col-md-4'>
                               <label for="employeed">Are You Employeed</label> <i class="text-danger asterik">*</i>
                               <select id='employeed' name="employeed" class='form-control' required>
                                  <option value='1' <?= ($res[0]['employeed'] == '1') ? 'selected' : ''; ?>>Yes</option>
                                  <option value='0' <?= ($res[0]['employeed'] == '0') ? 'selected' : ''; ?>>No</option>
                               </select>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <input type="submit" class="btn-primary btn" value="Update" name="btnUpdate" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<div class="separator"> </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $('#edit_slide_form').validate({
        ignore: [],
        debug: false,
        rules: {
            name: "required",
        }
    });
</script>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(200)
                    .css('display', 'block');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>