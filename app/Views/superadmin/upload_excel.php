<!-- app/Views/upload_excel.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Import Excel</title>
</head>
<body>
    <h2>Import user data Excel ke Database</h2>
    <form action="<?= base_url('import/proses') ?>" method="post" enctype="multipart/form-data">
        <input type="file" name="file_excel" accept=".xls,.xlsx" required>
        <button type="submit">Upload & Import</button>
    </form>


    <hr>

    <h2>Import data desa Excel ke Database</h2>
    <form action="<?= base_url('import/desa') ?>" method="post" enctype="multipart/form-data">
        <input type="file" name="file_excel" accept=".xls,.xlsx" required>
        <button type="submit">Upload & Import</button>
    </form>


    <hr>

<h2>Import data kecamatan Excel ke Database</h2>
<form action="<?= base_url('import/kecamatan') ?>" method="post" enctype="multipart/form-data">
    <input type="file" name="file_excel" accept=".xls,.xlsx" required>
    <button type="submit">Upload & Import</button>
</form>


<hr>

<h2>Import data kecamatan Excel ke Database</h2>
<form action="<?= base_url('import/admin') ?>" method="post" enctype="multipart/form-data">
    <input type="file" name="file_excel" accept=".xls,.xlsx" required>
    <button type="submit">Upload & Import</button>
</form>



<a href="<?= base_url('auth/logoutadmin') ?>" class="btn btn-secondary btn-flat float-end">Sign out</a>


</body>
</html>
