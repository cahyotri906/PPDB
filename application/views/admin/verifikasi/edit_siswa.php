<!-- Main content -->
<div class="content-wrapper">
    <!-- Content area -->
    <div class="content">
        <!-- Edit form -->
        <form action="panel_admin/update_siswa" method="POST">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title"><b>Edit Data Siswa</b></h5>
                    <hr style="margin:0px;">
                </div>
                <div class="panel-body">
                    <?php
          // Retrieve the data for the selected student using the provided "no_pendaftaran" parameter
          // You can use this data to populate the form fields
          $selected_student = get_student_data($no_pendaftaran); // Replace "get_student_data" with your own function to retrieve the student data
          ?>

                    <!-- Display form fields with the corresponding student data -->
                    <div class="form-group">
                        <label>No. Pendaftaran:</label>
                        <input type="text" name="no_pendaftaran" class="form-control"
                            value="<?php echo $selected_student->no_pendaftaran; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>NISN:</label>
                        <input type="text" name="nisn" class="form-control"
                            value="<?php echo $selected_student->nisn; ?>">
                    </div>

                    <div class="form-group">
                        <label>NIK:</label>
                        <input type="text" name="nik" class="form-control"
                            value="<?php echo $selected_student->nik; ?>">
                    </div>

                    <div class="form-group">
                        <label>Nama Lengkap:</label>
                        <input type="text" name="nama_lengkap" class="form-control"
                            value="<?php echo $selected_student->nama_lengkap; ?>">
                    </div>

                    <!-- Add more form fields as needed -->

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- /content area -->
</div>
<!-- /main content -->