<?php
include ('components/navbar.php');
include ('db/connect.php');


if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.2/css/dataTables.dataTables.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Payments</h1>
        <div class="text-right mb-3">
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addPaymentModal">Add Payment</button>
        </div>
        <div class="mb-3">
            <input type="text" class="form-control" id="searchStudent" placeholder="Search by Student Name">
        </div>
        <!-- Table for payments -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Receipt No</th>
                    <th scope="col">Fee Type</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Date</th>
                    <th scope="col">Student Name</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT Payment.*, Student.student_name FROM Payment JOIN Student ON Payment.student_id = Student.id";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                        <td>" . $row["receipt_no"] . "</td>
                        <td>" . $row["fee_type"] . "</td>
                        <td>" . $row["amount"] . "</td>
                        <td>" . $row["date"] . "</td>
                        <td>" . $row["student_name"] . "</td>
                        <td>
                           <button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deletePaymentModal' data-id='" . $row["id"] . "'>Delete</button>
                             <button class='btn btn-secondary btn-sm' onclick='printReceipt(" . $row["id"] . ")'>Print</button>
                        </td>
                    </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Add Payment Modal -->
    <div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog" aria-labelledby="addPaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaymentModalLabel">Add Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addPaymentForm" method="POST" action="db/add_payment.php">
                        <div class="form-group">
                            <label for="add-receipt">Receipt No</label>
                            <?php
                            $sql_last_receipt = "SELECT receipt_no FROM Payment ORDER BY id DESC LIMIT 1";
                            $result_last_receipt = $conn->query($sql_last_receipt);
                            $last_receipt = $result_last_receipt->fetch_assoc();
                            $next_number = $last_receipt ? (int) substr($last_receipt['receipt_no'], 1) + 1 : 1;
                            $next_receipt_no = 'R' . str_pad($next_number, 3, '0', STR_PAD_LEFT);
                            ?>
                            <input type="text" class="form-control" id="add-receipt" name="receipt_no"
                                value="<?php echo $next_receipt_no; ?>" readonly>
                        </div>
                        <div class="form-group position-relative">
                            <input type="hidden" id="student-id" name="student-id">
                            <label for="admission-no">Admission No</label>
                            <input type="text" class="form-control" id="admission-no" name="admission_no"
                                oninput="fetchStudents()" required>
                            <div id="student-dropdown" class="dropdown-menu w-100" style="display: none;"></div>
                        </div>
                        <div class="form-group">
                            <label for="student-name">Student Name</label>
                            <input type="text" class="form-control" id="student-name" name="student_name" readonly>
                        </div>
                        <div class="form-group">
                            <label for="father-name">Father's Name</label>
                            <input type="text" class="form-control" id="father-name" name="father_name" readonly>
                        </div>
                        <div class="form-group">
                            <label for="grade">Grade</label>
                            <input type="text" class="form-control" id="grade" name="grade" readonly>
                        </div>
                        <div class="form-group">
                            <label for="class">Class</label>
                            <input type="text" class="form-control" id="class" name="class" readonly>
                        </div>
                        <div class="form-group">
                            <input type="hidden" id="fee-types" name="fee_type">
                            <label>Fee Type</label><br>
                            <div class="form-check">
                                <input class="form-check-input fee-checkbox" type="checkbox" value="Facility Fee"
                                    id="facilityFee" data-amount="500">
                                <label class="form-check-label" for="facilityFee">Facility Fee</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input fee-checkbox" type="checkbox" value="SDC Fee" id="sdcFee"
                                    data-amount="200">
                                <label class="form-check-label" for="sdcFee">SDC Fee</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input fee-checkbox" type="checkbox" value="Balancing Money"
                                    id="balancingMoney" data-amount="300">
                                <label class="form-check-label" for="balancingMoney">Balancing Money</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="add-amount">Amount</label>
                            <input type="number" step="0.01" class="form-control" id="add-amount" readonly
                                name="amount">
                        </div>
                        <div class="form-group">
                            <label for="add-date">Date</label>
                            <input type="date" class="form-control" id="add-date" name="date" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Delete Payment Modal -->
    <div class="modal fade" id="deletePaymentModal" tabindex="-1" role="dialog"
        aria-labelledby="deletePaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePaymentModalLabel">Delete Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this payment?</p>
                    <form id="deletePaymentForm" method="POST" action="db/delete_payment.php">
                        <input type="hidden" id="delete-payment-id" name="id">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Print Receipt Modal -->
    <div class="modal fade" id="printReceiptModal" tabindex="-1" role="dialog" aria-labelledby="printReceiptModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="printReceiptModalLabel">Print Receipt</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="printReceiptContent">
                    <!-- Receipt content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"
                        onclick="printDiv('printReceiptContent')">Print</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.min.js"></script>
    <script>
        document.getElementById('searchStudent').addEventListener('input', function () {
            var searchTerm = this.value.toLowerCase();
            var tableRows = document.querySelectorAll('table tbody tr');

            tableRows.forEach(function (row) {
                var studentName = row.cells[4].textContent.toLowerCase();
                if (studentName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        // Fetch students and populate dropdown
        function fetchStudents() {
            var admissionNo = document.getElementById('admission-no').value;
            var dropdown = document.getElementById('student-dropdown');

            if (admissionNo.length > 0) {
                fetch('db/fetch_students.php?admission_no=' + admissionNo)
                    .then(response => response.json())
                    .then(data => {
                        dropdown.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(student => {
                                var item = document.createElement('a');
                                item.className = 'dropdown-item';
                                item.href = '#';
                                item.textContent = student.student_name;
                                item.onclick = function () {

                                    document.getElementById('student-id').value = student.id;
                                    document.getElementById('admission-no').value = student.admission_no;
                                    document.getElementById('student-name').value = student.student_name;
                                    document.getElementById('father-name').value = student.father_name;
                                    document.getElementById('grade').value = student.grade;
                                    document.getElementById('class').value = student.class;
                                    dropdown.style.display = 'none';
                                };
                                dropdown.appendChild(item);
                            });
                            dropdown.style.display = 'block';
                        } else {
                            dropdown.style.display = 'none';
                        }
                    });
            } else {
                dropdown.style.display = 'none';
            }
        }

        // Calculate amount based on selected fee types
        document.querySelectorAll('.fee-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                let amount = 0;
                let fee_type = [];

                document.querySelectorAll('.fee-checkbox:checked').forEach(checked => {
                    amount += parseFloat(checked.getAttribute('data-amount'));
                    fee_type.push(checked.getAttribute('value'));
                });

                // Update the amount and fee types in the form
                document.getElementById('add-amount').value = amount;
                document.getElementById('fee-types').value = fee_type.join(','); // Join with a comma to separate the values
            });
        });
        $('#deletePaymentModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var paymentId = button.data('id'); // Extract info from data-* attributes

            var modal = $(this);
            modal.find('#delete-payment-id').val(paymentId);
        });
        // Fill Edit Payment Modal
        $('#editPaymentModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var paymentId = button.data('id'); // Extract info from data-* attributes
            var receiptNo = button.data('receipt');
            var feeType = button.data('fee');
            var amount = button.data('amount');
            var date = button.data('date');
            var studentId = button.data('student');

            var modal = $(this);
            modal.find('#edit-payment-id').val(paymentId);
            modal.find('#edit-receipt').val(receiptNo);
            modal.find('#edit-fee-types').val(feeType);
            modal.find('#edit-amount').val(amount);
            modal.find('#edit-date').val(date);

            // Set student information
            modal.find('#edit-student-id').val(studentId);
            // Fetch and populate student details
            fetchStudents();
        });


        // Update Payment
        function updatePayment() {
            var form = document.getElementById('editPaymentForm');
            var formData = new FormData(form);

            fetch('update_payment.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        location.reload();
                    } else {
                        alert('Error updating payment');
                    }
                });
        }

        // Print Receipt
        function printReceipt(id) {
            $.ajax({
                url: 'db/get_receipt.php',
                method: 'POST',
                data: { id: id },
                success: function (response) {
                    $('#printReceiptContent').html(response);
                    $('#printReceiptModal').modal('show');
                }
            });
        }

        function printDiv(divId) {
            var divContents = document.getElementById(divId).innerHTML;
            var a = window.open('', '', 'height=500, width=500');
            a.document.write('<html><head><title>Receipt</title>');
            a.document.write('<style>');
            a.document.write('.receipt { font-family: Arial, sans-serif; border: 1px solid #ddd; padding: 20px; margin: 10px; }');
            a.document.write('.receipt-header { text-align: center; margin-bottom: 20px; }');
            a.document.write('.receipt-details { margin-bottom: 20px; }');
            a.document.write('.receipt-footer { text-align: center; margin-top: 20px; }');
            a.document.write('</style></head><body>');
            a.document.write(divContents);
            a.document.write('</body></html>');
            a.document.close();
            a.print();
        }
    </script>
</body>

</html>