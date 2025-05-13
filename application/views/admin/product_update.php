<!DOCTYPE html>
<html>

<head>
    <title>Update Products</title>
</head>

<body>
    <h2>Update Products via CSV</h2>

    <?php if (!empty($message)) : ?>
        <p style="color: green;"><?php echo $message; ?></p>
    <?php endif; ?>

    <?php if (!empty($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Select CSV File:</label><br>
        <input type="file" name="csv_file" accept=".csv" required><br><br>
        <button type="submit">Upload & Update</button>
    </form>

    <p><strong>Note:</strong> CSV must contain headers: <code>id,name,hsn_code,cgst,sgst</code></p>
</body>

</html>