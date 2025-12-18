<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            padding: 30px 0;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
        }

        .btn-parkir {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 15px 40px;
            font-size: 18px;
            border-radius: 50px;
            transition: transform 0.3s;
        }

        .btn-parkir:hover {
            transform: scale(1.05);
            color: white;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }

        .badge-status {
            padding: 8px 15px;
            border-radius: 20px;
        }

        #qrModal .modal-content {
            border-radius: 15px;
        }

        .qr-container {
            text-align: center;
            padding: 20px;
        }

        .qr-container img {
            max-width: 300px;
            border: 5px solid #667eea;
            border-radius: 10px;
            padding: 10px;
            background: white;
        }
    </style>
</head>

<body>
    <div class="container main-container">
        <!-- Header -->
        <div class="text-center text-white mb-4">
            <h1 class="display-4 fw-bold"><i class="bi bi-car-front-fill"></i> Parkir Modern</h1>
            <p class="lead">Sistem Parkir Digital dengan QR Code</p>
        </div>

        <!-- Statistik -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card text-center">
                    <div class="stat-icon text-primary">
                        <i class="bi bi-car-front"></i>
                    </div>
                    <h3 id="totalKendaraan"><?= count($transaksi) ?></h3>
                    <p class="text-muted mb-0">Kendaraan Hari Ini</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card text-center">
                    <div class="stat-icon text-success">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <h3 id="totalPendapatan">Rp <?= number_format($pendapatan, 0, ',', '.') ?></h3>
                    <p class="text-muted mb-0">Pendapatan Hari Ini</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card text-center">
                    <div class="stat-icon text-info">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h3 id="waktuSekarang"><?= date('H:i:s') ?></h3>
                    <p class="text-muted mb-0"><?= date('d F Y') ?></p>
                </div>
            </div>
        </div>

        <!-- Form Parkir -->
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Kendaraan Masuk</h4>
            </div>
            <div class="card-body text-center">
                <form id="formParkir">
                    <div class="mb-3">
                        <input type="text" class="form-control form-control-lg" id="platNomor"
                            placeholder="Masukkan Plat Nomor (Opsional)" style="border-radius: 10px;">
                    </div>
                    <button type="submit" class="btn btn-parkir">
                        <i class="bi bi-qr-code"></i> Generate QR Parkir
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabel Transaksi -->
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-list-ul"></i> Transaksi Hari Ini</h4>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID Parkir</th>
                                <th>Plat Nomor</th>
                                <th>Waktu Masuk</th>
                                <th>Waktu Keluar</th>
                                <th>Durasi</th>
                                <th>Biaya</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="transaksiTable">
                            <?php foreach ($transaksi as $t): ?>
                                <tr>
                                    <td><strong><?= $t['id_parkir'] ?></strong></td>
                                    <td><?= $t['plat_nomor'] ?: '-' ?></td>
                                    <td><?= date('H:i', strtotime($t['waktu_masuk'])) ?></td>
                                    <td><?= $t['waktu_keluar'] ? date('H:i', strtotime($t['waktu_keluar'])) : '-' ?></td>
                                    <td><?= $t['durasi_menit'] ? $t['durasi_menit'] . ' menit' : '-' ?></td>
                                    <td>Rp <?= number_format($t['biaya'], 0, ',', '.') ?></td>
                                    <td>
                                        <?php if ($t['status'] == 'masuk'): ?>
                                            <span class="badge badge-status bg-warning text-dark">Parkir</span>
                                        <?php else: ?>
                                            <span class="badge badge-status bg-success">Keluar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('parkir/detail/' . $t['id_parkir']) ?>"
                                            class="btn btn-sm btn-info" target="_blank">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal QR Code -->
    <div class="modal fade" id="qrModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-gradient"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="modal-title"><i class="bi bi-qr-code"></i> QR Code Parkir</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="qr-container">
                        <div id="qrImage"></div>
                        <div class="mt-3">
                            <h4 id="idParkirText"></h4>
                            <p class="text-muted">Scan QR Code untuk melihat detail parkir</p>
                            <a id="detailLink" href="#" class="btn btn-primary" target="_blank">
                                <i class="bi bi-box-arrow-up-right"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update waktu real-time
        setInterval(() => {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID');
            document.getElementById('waktuSekarang').textContent = timeStr;
        }, 1000);

        // Form submit
        document.getElementById('formParkir').addEventListener('submit', async (e) => {
            e.preventDefault();

            const platNomor = document.getElementById('platNomor').value;
            const btn = e.target.querySelector('button[type="submit"]');
            const originalHtml = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';

            try {
                const response = await fetch('<?= base_url('parkir/generate') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `plat_nomor=${encodeURIComponent(platNomor)}`
                });

                const result = await response.json();

                if (result.status === 'success') {
                    // Tampilkan QR Code
                    const qrImage = `<img src="data:image/png;base64,${result.data.qr_image}" alt="QR Code">`;
                    document.getElementById('qrImage').innerHTML = qrImage;
                    document.getElementById('idParkirText').textContent = result.data.id_parkir;
                    document.getElementById('detailLink').href = result.data.detail_url;

                    // Tampilkan modal
                    const modal = new bootstrap.Modal(document.getElementById('qrModal'));
                    modal.show();

                    // Reset form
                    document.getElementById('platNomor').value = '';

                    // Refresh data setelah 2 detik
                    setTimeout(() => location.reload(), 2000);
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        });
    </script>
</body>

</html>