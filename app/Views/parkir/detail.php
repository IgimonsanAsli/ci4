<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - <?= $transaksi['id_parkir'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .detail-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 600px;
            margin: 0 auto;
        }

        .header-section {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .header-section h2 {
            color: #667eea;
            font-weight: bold;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-label {
            font-weight: 600;
            color: #666;
        }

        .info-value {
            font-weight: bold;
            color: #333;
        }

        .biaya-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            text-align: center;
        }

        .biaya-section h3 {
            font-size: 48px;
            font-weight: bold;
            margin: 10px 0;
        }

        .status-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: bold;
        }

        .btn-action {
            width: 100%;
            padding: 15px;
            font-size: 18px;
            border-radius: 10px;
            margin-top: 10px;
        }

        .qr-section {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 15px;
            border: 2px dashed #667eea;
        }

        .qr-section img {
            max-width: 250px;
            border: 3px solid #667eea;
            border-radius: 10px;
            padding: 10px;
            background: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="detail-card">
            <!-- Header -->
            <div class="header-section">
                <i class="bi bi-car-front-fill" style="font-size: 60px; color: #667eea;"></i>
                <h2>Detail Parkir</h2>
                <p class="text-muted mb-0">Informasi Lengkap Transaksi</p>
            </div>

            <!-- QR Code Section -->
            <?php if (!empty($transaksi['qr_image'])): ?>
                <div class="qr-section">
                    <img src="data:image/png;base64,<?= $transaksi['qr_image'] ?>" alt="QR Code Parkir">
                    <p class="text-muted mt-2 mb-0"><small>Scan QR Code untuk detail parkir</small></p>
                </div>
            <?php endif; ?>

            <!-- Informasi Parkir -->
            <div class="info-row">
                <span class="info-label"><i class="bi bi-hash"></i> ID Parkir</span>
                <span class="info-value"><?= $transaksi['id_parkir'] ?></span>
            </div>

            <div class="info-row">
                <span class="info-label"><i class="bi bi-credit-card"></i> Plat Nomor</span>
                <span class="info-value"><?= $transaksi['plat_nomor'] ?: 'Tidak dicatat' ?></span>
            </div>

            <div class="info-row">
                <span class="info-label"><i class="bi bi-clock"></i> Waktu Masuk</span>
                <span class="info-value"><?= date('d/m/Y H:i:s', strtotime($transaksi['waktu_masuk'])) ?></span>
            </div>

            <?php if ($transaksi['waktu_keluar']): ?>
                <div class="info-row">
                    <span class="info-label"><i class="bi bi-clock-history"></i> Waktu Keluar</span>
                    <span class="info-value"><?= date('d/m/Y H:i:s', strtotime($transaksi['waktu_keluar'])) ?></span>
                </div>
            <?php endif; ?>

            <div class="info-row">
                <span class="info-label"><i class="bi bi-hourglass-split"></i> Durasi Parkir</span>
                <span class="info-value" id="durasiParkir" style="font-size: 20px; color: #667eea; font-weight: bold;">
                    <?= $durasi_menit ?> menit
                </span>
            </div>

            <div class="info-row">
                <span class="info-label"><i class="bi bi-circle-fill"></i> Status</span>
                <span>
                    <?php if ($transaksi['status'] == 'masuk'): ?>
                        <span class="status-badge bg-warning text-dark">
                            <i class="bi bi-car-front"></i> Sedang Parkir
                        </span>
                    <?php else: ?>
                        <span class="status-badge bg-success">
                            <i class="bi bi-check-circle"></i> Sudah Keluar
                        </span>
                    <?php endif; ?>
                </span>
            </div>

            <!-- Biaya -->
            <div class="biaya-section">
                <p class="mb-2"><i class="bi bi-cash-stack"></i> Total Biaya</p>
                <h3 id="biayaTotal">Rp <?= number_format($biaya_terkini, 0, ',', '.') ?></h3>
                <small>
                    Tarif: Rp 5.000 (jam pertama) + Rp 2.000/jam berikutnya
                </small>
            </div>

            <!-- Tombol Aksi -->
            <?php if ($transaksi['status'] == 'masuk'): ?>
                <button class="btn btn-success btn-action" onclick="checkout()">
                    <i class="bi bi-box-arrow-right"></i> Checkout & Bayar
                </button>
            <?php endif; ?>

            <a href="<?= base_url('/') ?>" class="btn btn-secondary btn-action">
                <i class="bi bi-house"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Parsing waktu masuk dari server (format: YYYY-MM-DD HH:mm:ss)
        const waktuMasukStr = '<?= $transaksi['waktu_masuk'] ?>';
        const waktuKeluarStr = '<?= $transaksi['waktu_keluar'] ?? '' ?>';
        const status = '<?= $transaksi['status'] ?>';

        // Parse waktu masuk
        const waktuMasukParts = waktuMasukStr.split(' ');
        const dateParts = waktuMasukParts[0].split('-');
        const timeParts = waktuMasukParts[1].split(':');

        const waktuMasuk = new Date(
            parseInt(dateParts[0]),
            parseInt(dateParts[1]) - 1,
            parseInt(dateParts[2]),
            parseInt(timeParts[0]),
            parseInt(timeParts[1]),
            parseInt(timeParts[2])
        );

        // Parse waktu keluar jika ada
        let waktuKeluar = null;
        if (waktuKeluarStr) {
            const waktuKeluarParts = waktuKeluarStr.split(' ');
            const datePartsKeluar = waktuKeluarParts[0].split('-');
            const timePartsKeluar = waktuKeluarParts[1].split(':');

            waktuKeluar = new Date(
                parseInt(datePartsKeluar[0]),
                parseInt(datePartsKeluar[1]) - 1,
                parseInt(datePartsKeluar[2]),
                parseInt(timePartsKeluar[0]),
                parseInt(timePartsKeluar[1]),
                parseInt(timePartsKeluar[2])
            );
        }

        console.log('Waktu Masuk:', waktuMasukStr);
        console.log('Waktu Keluar:', waktuKeluarStr);
        console.log('Status:', status);

        // Fungsi untuk format angka dengan pemisah ribuan
        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Fungsi untuk menghitung durasi dan biaya
        function hitungDurasiDanBiaya(waktuMulai, waktuSelesai = null) {
            const waktuAkhir = waktuSelesai || new Date();
            const diffMs = waktuAkhir - waktuMulai;
            const diffMenit = Math.floor(diffMs / 1000 / 60);

            // Hitung biaya
            let biaya = 5000;
            if (diffMenit > 60) {
                const menitTambahan = diffMenit - 60;
                const jamTambahan = Math.ceil(menitTambahan / 60);
                biaya = 5000 + (jamTambahan * 2000);
            }

            return {
                menit: diffMenit,
                biaya: biaya
            };
        }

        // Update durasi dan biaya
        function updateDurasi() {
            // Jika sudah checkout, hitung durasi final (tidak berubah lagi)
            if (status === 'keluar' && waktuKeluar) {
                const hasil = hitungDurasiDanBiaya(waktuMasuk, waktuKeluar);

                // Format durasi
                let durasiText = hasil.menit + ' menit';
                if (hasil.menit >= 60) {
                    const jam = Math.floor(hasil.menit / 60);
                    const sisaMenit = hasil.menit % 60;
                    durasiText = jam + ' jam ' + sisaMenit + ' menit';
                }

                // Update tampilan (hanya sekali)
                document.getElementById('durasiParkir').textContent = durasiText;
                document.getElementById('biayaTotal').textContent = 'Rp ' + formatRupiah(hasil.biaya);

                console.log('Durasi Final:', hasil.menit, 'menit, Biaya: Rp', hasil.biaya);
                return; // Stop, tidak perlu update lagi
            }

            // Jika masih parkir, hitung real-time
            if (status === 'masuk') {
                const hasil = hitungDurasiDanBiaya(waktuMasuk);

                // Format durasi
                let durasiText = hasil.menit + ' menit';
                if (hasil.menit >= 60) {
                    const jam = Math.floor(hasil.menit / 60);
                    const sisaMenit = hasil.menit % 60;
                    durasiText = jam + ' jam ' + sisaMenit + ' menit';
                }

                // Update tampilan
                document.getElementById('durasiParkir').textContent = durasiText;
                document.getElementById('biayaTotal').textContent = 'Rp ' + formatRupiah(hasil.biaya);

                console.log('Update Real-time - Durasi:', hasil.menit, 'menit, Biaya: Rp', hasil.biaya);
            }
        }

        // Jalankan update
        updateDurasi(); // Update langsung saat load

        // Hanya set interval jika masih parkir
        if (status === 'masuk') {
            setInterval(updateDurasi, 1000); // Update setiap detik
        }

        // Fungsi checkout
        async function checkout() {
            if (!confirm('Apakah Anda yakin ingin checkout?')) return;

            const btn = event.target;
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';

            try {
                const response = await fetch('<?= base_url('parkir/checkout') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id_parkir=<?= $transaksi['id_parkir'] ?>'
                });

                const result = await response.json();

                if (result.status === 'success') {
                    const jam = Math.floor(result.data.durasi_menit / 60);
                    const menit = result.data.durasi_menit % 60;
                    const durasiText = jam > 0 ? jam + ' jam ' + menit + ' menit' : menit + ' menit';

                    alert('Checkout berhasil!\nDurasi: ' + durasiText + '\nTotal: Rp ' +
                        formatRupiah(result.data.biaya));
                    location.reload();
                } else {
                    alert('Error: ' + result.message);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-box-arrow-right"></i> Checkout & Bayar';
                }
            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-box-arrow-right"></i> Checkout & Bayar';
            }
        }
    </script>
</body>

</html>