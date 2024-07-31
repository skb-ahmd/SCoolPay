<?php include('components/navbar.php'); ?>
<?php include('db/connect.php'); ?>
<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>


<div class="container mt-5">
    <h1>Students</h1>
    <div class="text-right mb-3">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">Add Student</button>
    </div>
    <!-- Table for students -->
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Admission No</th>
                <th scope="col">Student Name</th>
                <th scope="col">Father Name</th>
                <th scope="col">Grade</th>
                <th scope="col">Class</th>
                <th scope="col">Contact No</th>
                <!-- <th scope="col">Actions</th> -->
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM Student";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <th scope='row'>" . $row["id"] . "</th>
                        <td>" . $row["admission_no"] . "</td>
                        <td>" . $row["student_name"] . "</td>
                        <td>" . $row["father_name"] . "</td>
                        <td>" . $row["grade"] . "</td>
                        <td>" . $row["class"] . "</td>
                        <td>" . $row["contact_no"] . "</td>
                       
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add Student</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addForm">
                    <div class="form-group">
                        <label for="add-admission">Admission No</label>
                        <?php
                            $sql_last_admission = "SELECT admission_no FROM student ORDER BY id DESC LIMIT 1";
                            $result_last_admission = $conn->query($sql_last_admission);
                            $last_admission = $result_last_admission->fetch_assoc();
                            $next_number = $last_admission ? (int) substr($last_admission['admission_no'], 1) + 1 : 1;
                            $next_admission_no = 'A' . str_pad($next_number, 3, '0', STR_PAD_LEFT);
                        ?>
                        <input type="text" class="form-control" id="add-admission" value="<?php echo $next_admission_no; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="add-name">Student Name</label>
                        <input type="text" class="form-control" id="add-name" required>
                    </div>
                    <div class="form-group">
                        <label for="add-father">Father Name</label>
                        <input type="text" class="form-control" id="add-father">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="add-grade">Grade</label>
                            <select id="add-grade" class="form-control">
                                <?php for ($i = 6; $i <= 11; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="add-class">Class</label>
                            <select id="add-class" class="form-control">
                                <?php foreach (range('A', 'F') as $letter): ?>
                                    <option value="<?php echo $letter; ?>"><?php echo $letter; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add-contact">Contact No</label>
                        <input type="text" class="form-control" id="add-contact">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Student</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Edit Modal -->




<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
   

    $('#addForm').on('submit', function(event) {
        event.preventDefault();
        var admission = $('#add-admission').val();
        var name = $('#add-name').val();
        var father = $('#add-father').val();
        var grade = $('#add-grade').val();
        var className = $('#add-class').val();
        var contact = $('#add-contact').val();

        $.ajax({
            url: 'db/add_student.php',
            method: 'POST',
            data: {
                admission_no: admission,
                student_name: name,
                father_name: father,
                grade: grade,
                class: className,
                contact_no: contact
            },
            success: function(response) {
                location.reload();
            }
        });
    });

    $('#editForm').on('submit', function(event) {
        event.preventDefault();
        var id = $('#edit-id').val();
        var admission = $('#edit-admission').val();
        var name = $('#edit-name').val();
        var father = $('#edit-father').val();
        var grade = $('#edit-grade').val();
        var className = $('#edit-class').val();
        var contact = $('#edit-contact').val();

        $.ajax({
            url: 'db/edit_student.php',
            method: 'POST',
            data: {
                id: id,
                admission_no: admission,
                student_name: name,
                father_name: father,
                grade: grade,
                class: className,
                contact_no: contact
            },
            success: function(response) {
                location.reload();
            }
        });
    });

    
});
</script>

<?php $conn->close(); ?>
