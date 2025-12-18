<?= $this->include("navbar"); ?>

<h2>Form Input Data Mahasiswa</h2>

<form action="/mahasiswa/simpan" method="post" enctype="multipart/form-data">
    <table>
        <tr>
            <td>NIM</td>
            <td>::</td>
            <td><input type="text" name="nim" required></td>
        </tr>

        <tr>
            <td>Nama</td>
            <td>::</td>
            <td><input type="text" name="nama" required></td>
        </tr>

        <tr>
            <td>Tempat Lahir</td>
            <td>::</td>
            <td><input type="text" name="tpt_lhr" required></td>
        </tr>

        <tr>
            <td>Tanggal Lahir</td>
            <td>::</td>
            <td><input type="date" name="tgl_lhr" required></td>
        </tr>

        <tr>
            <td>Alamat</td>
            <td>::</td>
            <td><textarea name="alamat" required></textarea></td>
        </tr>

        <tr>
            <td>Program Studi</td>
            <td>::</td>
            <td>
                <select name="prodi" required>
                    <option value="">-- Pilih Program Studi --</option>
                    <option value="Teknik Informatika">Teknik Informatika</option>
                    <option value="Sistem Informatika">Sistem Informatika</option>
                    <option value="Manajemen Informatika">Manajemen Informatika</option>
                    <option value="Komputerisasi Akuntansi">Komputerisasi Akuntansi</option>
                </select>
            </td>
        </tr>

        <tr>
            <td>Foto</td>
            <td>::</td>
            <td><input type="file" name="foto" accept="image/*" required></td>
        </tr>

        <tr>
            <td></td>
            <td></td>
            <td>
                <button type="submit">Simpan</button>
                <button type="reset">Reset</button>
            </td>
        </tr>
    </table>
</form>

<hr>

<h3>Daftar Mahasiswa</h3>

<?php if (!empty($mahasiswa)): ?>

    <?php
    $page = $pager->getCurrentPage() ?? 1;
    $no = 1 + (5 * ($page - 1));
    ?>

    <table border="1" cellpadding="8">
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Tempat, Tanggal Lahir</th>
            <th>Alamat</th>
            <th>Prodi</th>
            <th>Foto</th>
            <th>Aksi</th>
        </tr>

        <?php foreach ($mahasiswa as $m): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($m['nim']) ?></td>
                <td><?= esc($m['nama']) ?></td>
                <td><?= esc($m['tpt_lhr']) ?>, <?= esc($m['tgl_lhr']) ?></td>
                <td><?= esc($m['alamat']) ?></td>
                <td><?= esc($m['prodi']) ?></td>
                <td><img src="/<?= $m['foto'] ?>" width="50"></td>
                <td>
                    <a href="/mahasiswa/edit/<?= $m['id'] ?>">Edit</a> |
                    <a href="/mahasiswa/delete/<?= $m['id'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>

    <br>

    <?= $pager->links() ?>

<?php else: ?>
    <p>Belum ada data mahasiswa.</p>
<?php endif; ?>


<div class="footer">
    Institut Widya Pratama
</div>