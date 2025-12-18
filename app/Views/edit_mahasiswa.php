<?= $this->include("navbar"); ?>

<h2>Edit Data Mahasiswa</h2>

<form action="/mahasiswa/update/<?= $mhs['id'] ?>" method="post" enctype="multipart/form-data">
    <table>
        <tr>
            <td>NIM</td>
            <td>::</td>
            <td><input type="text" name="nim" value="<?= esc($mhs['nim']) ?>" required></td>
        </tr>

        <tr>
            <td>Nama</td>
            <td>::</td>
            <td><input type="text" name="nama" value="<?= esc($mhs['nama']) ?>" required></td>
        </tr>

        <tr>
            <td>Tempat Lahir</td>
            <td>::</td>
            <td><input type="text" name="tpt_lhr" value="<?= esc($mhs['tpt_lhr']) ?>" required></td>
        </tr>

        <tr>
            <td>Tanggal Lahir</td>
            <td>::</td>
            <td><input type="date" name="tgl_lhr" value="<?= esc($mhs['tgl_lhr']) ?>" required></td>
        </tr>

        <tr>
            <td>Alamat</td>
            <td>::</td>
            <td>
                <textarea name="alamat" required><?= esc($mhs['alamat']) ?></textarea>
            </td>
        </tr>

        <tr>
            <td>Program Studi</td>
            <td>::</td>
            <td>
                <select name="prodi" required>
                    <option value="Teknik Informatika" <?= $mhs['prodi'] == 'Teknik Informatika' ? 'selected' : '' ?>>Teknik
                        Informatika</option>
                    <option value="Sistem Informatika" <?= $mhs['prodi'] == 'Sistem Informatika' ? 'selected' : '' ?>>Sistem
                        Informatika</option>
                    <option value="Manajemen Informatika" <?= $mhs['prodi'] == 'Manajemen Informatika' ? 'selected' : '' ?>>
                        Manajemen Informatika</option>
                    <option value="Komputerisasi Akuntansi" <?= $mhs['prodi'] == 'Komputerisasi Akuntansi' ? 'selected' : '' ?>>Komputerisasi Akuntansi</option>
                </select>
            </td>
        </tr>

        <tr>
            <td>Foto</td>
            <td>::</td>
            <td>
                <img src="/<?= $mhs['foto'] ?>" width="70"><br>
                <input type="file" name="foto" accept="image/*">
                <small>*Kosongkan jika tidak ingin ganti foto</small>
            </td>
        </tr>

        <tr>
            <td></td>
            <td></td>
            <td>
                <button type="submit">Update</button>
                <a href="/mahasiswa/input">Kembali</a>
            </td>
        </tr>
    </table>
</form>

<div class="footer">
    Institut Widya Pratama
</div>