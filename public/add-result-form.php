<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
?>
<?php
if (isset($_POST['btnAdd'])) {

    $year_semester = $db->escapeString(($_POST['year_semester']));
    $exam_month_year = $db->escapeString(($_POST['exam_month_year']));
    $total_marks = $db->escapeString(($_POST['total_marks']));
    $obtained_marks = $db->escapeString(($_POST['obtained_marks']));
    $sgpa = $db->escapeString(($_POST['sgpa']));
    $status = $db->escapeString(($_POST['status']));

    $error = array();

    if (empty($year_semester)) {
        $error['year_semester'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($exam_month_year)) {
        $error['exam_month_year'] = " <span class='label label-danger'>Required!</span>";
    } 
    if (empty($total_marks)) {
        $error['total_marks'] = " <span class='label label-danger'>Required!</span>";
    } 
    if (empty($obtained_marks)) {
        $error['obtained_marks'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($sgpa)) {
        $error['sgpa'] = " <span class='label label-danger'>Required!</span>";
    } 
    if (empty($status)) {
        $error['status'] = " <span class='label label-danger'>Required!</span>";
    }

    if (!empty($year_semester) && !empty($exam_month_year) && !empty($total_marks) && !empty($obtained_marks) && !empty($sgpa) && !empty($status)) 
    {
        $sql_query = "INSERT INTO result (year_semester,exam_month_year,total_marks,obtained_marks,sgpa,status) VALUES('$year_semester','$exam_month_year','$total_marks','$obtained_marks','$sgpa','$status')";
        $db->sql($sql_query);
        $result = $db->getResult();
        if (!empty($result)) {
            $result = 0;
        } else {
            $result = 1;
        }

        if ($result == 1) {
            $error['add_languages'] = "<section class='content-header'>
                                            <span class='label label-success'>Result Added Successfully</span> </section>";
        } else {
            $error['add_languages'] = " <span class='label label-danger'>Failed</span>";
        }
    }
}
?>
<section class="content-header">
    <h1>Add New Results <small><a href='result.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Results</a></small></h1>

    <?php echo isset($error['add_languages']) ? $error['add_languages'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr />
</section>
<section class="content">
    <div class="row">
        <div class="col-md-10">
           
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">

                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form url="add-languages-form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                       <div class="row">
                            <div class="form-group">
                                <div class='col-md-4'>
                                    <label for="exampleInputtitle">Year/Semester</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="year_semester" required>
                                </div>
                                <div class='col-md-4'>
                                    <label for="exampleInputtitle">Exam Month and Year</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="exam_month_year" required>
                                </div>
                                <div class='col-md-4'>
                                    <label for="exampleInputtitle">Total Marks</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="total_marks" required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-4'>
                                    <label for="exampleInputtitle">Obtained Marks</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="obtained_marks" required>
                                </div>
                                <div class='col-md-4'>
                                    <label for="exampleInputtitle">SGPA</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="sgpa" required>
                                </div>
                                <div class='col-md-4'>
                                    <label for="status">Status</label> <i class="text-danger asterik">*</i><br>
                                    <label class="btn btn-success" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                        <input type="radio" name="status" value="1">Pass
                                    </label>
                                    <label class="btn btn-danger" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                        <input type="radio" name="status" value="0"> Fail
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnAdd">Submit</button>
                        <input type="reset" onClick="refreshPage()" class="btn-warning btn" value="Clear" />
                    </div>

                </form>
                <div id="result"></div>

            </div><!-- /.box -->
        </div>
    </div>
</section>
<div class="separator"> </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $('#add_leave_form').validate({
        ignore: [],
        debug: false,
        rules: {
            reason: "required",
            date: "required",
        }
    });
    $('#btnClear').on('click', function() {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].setData('');
        }
    });
</script>
<script>
    $(document).ready(function () {
        $('#user_id').select2({
            width: 'element',
            placeholder: 'Type in name to search',
        });
    });

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

<!--code for page clear-->
<script>
    function refreshPage(){
        window.location.reload();
    } 
</script>

<?php $db->disconnect(); ?>
