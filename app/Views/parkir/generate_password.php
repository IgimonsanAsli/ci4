<?php
/**
 * Script untuk generate password hash
 * Jalankan file ini di browser, lalu copy hash-nya ke database
 */

$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>Password Generator</h2>";
echo "<p><strong>Plain Password:</strong> $password</p>";
echo "<p><strong>Hash:</strong> $hash</p>";
echo "<hr>";
echo "<h3>SQL Query untuk Update:</h3>";
echo "<pre>";
echo "UPDATE akses_admin SET password = '$hash' WHERE username = 'admin';";
echo "</pre>";

// Verifikasi hash
if (password_verify($password, $hash)) {
    echo "<p style='color: green;'><strong>✓ Hash Valid!</strong> Password bisa diverifikasi.</p>";
} else {
    echo "<p style='color: red;'><strong>✗ Hash Tidak Valid!</strong></p>";
}
?>