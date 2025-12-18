<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Struk Parkir - <?= $transaksi['id_parkir'] ?></title>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 15px;
            font-size: 11px;
            line-height: 1.5;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            border: 3px solid #333;
            padding: 15px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #333;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }

        .header h1 {
            margin: 0 0 5px 0;
            font-size: 22px;
            color: #333;
        }

        .header p {
            margin: 3px 0;
            color: #666;
            font-size: 10px;
        }

        .qr-section {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            background: #f9f9f9;
            border: 2px dashed #ccc;
        }

        .qr-section img {
            width: 120px;
            height: 120px;
            border: 2px solid #333;
            padding: 5px;
            background: white;
            display: block;
            margin: 0 auto 8px auto;
        }

        .qr-section p {
            margin: 5px 0;
            font-size: 9px;
            font-weight: bold;
        }

        .info-section {
            margin: 15px 0;
        }

        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
            border-bottom: 1px dashed #ddd;
            padding-bottom: 6px;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 40%;
            color: #555;
        }

        .info-value {
            display: table-cell;
            color: #000;
            font-weight: bold;
        }

        .tarif-box {
            background-color: #fff8dc;
            padding: 12px;
            margin: 15px 0;
            border-left: 4px solid #ffa500;
            font-size: 10px;
        }

        .tarif-box h3 {
            margin: 0 0 8px 0;
            font-size: 12px;
            color: #ff8c00;
        }

        .tarif-box ul {
            margin: 8px 0;
            padding-left: 20px;
        }

        .tarif-box li {
            margin-bottom: 4px;
        }

        .total-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            margin: 15px 0;
            text-align: center;
            border-radius: 8px;
        }

        .total-section h2 {
            margin: 0 0 8px 0;
            font-size: 14px;
            font-weight: normal;
        }

        .total-amount {
            font-size: 28px;
            font-weight: bold;
            margin: 5px 0;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 9px;
        }

        .status-masuk {
            background-color: #ffc107;
            color: #000;
        }

        .status-keluar {
            background-color: #28a745;
            color: #fff;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 12px;
            border-top: 3px solid #333;
            color: #666;
            font-size: 9px;
        }

        .footer p {
            margin: 4px 0;
        }

        .divider {
            border-top: 2px dashed #ccc;
            margin: 15px 0;
        }

        .highlight {
            background-color: #ffeb3b;
            padding: 2px 5px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🚗 PARKIR MODERN</h1>
            <p>Sistem Parkir Digital dengan QR Code</p>
            <p><strong>STRUK PEMBAYARAN PARKIR</strong></p>
            <p>Tanggal Cetak: <?= date('d/m/Y H:i:s') ?></p>
        </div>

        <!-- QR Code Section -->
        <?php if (!empty($transaksi['qr_image'])): ?>
            <div class="qr-section">
                <img src="data:image/png;base64,<?= $transaksi['qr_image'] ?>" alt="QR Code Parkir">
                <p>SCAN QR CODE UNTUK DETAIL PARKIR</p>
                <p style="font-size: 8px; color: #666;">ID: <?= $transaksi['id_parkir'] ?></p>
            </div>
        <?php endif; ?>

        <div class="divider"></div>

        <!-- Informasi Parkir -->
        <div class="info-section">
            <h3 style="margin: 0 0 10px 0; color: #667eea; font-size: 13px;">📋 DETAIL TRANSAKSI</h3>

            <div class="info-row">
                <span class="info-label">ID Parkir</span>
                <span class="info-value" style="color: #667eea;"><?= $transaksi['id_parkir'] ?></span>
            </div>

            <div class="info-row">
                <span class="info-label">Plat Nomor</span>
                <span class="info-value"><?= $transaksi['plat_nomor'] ?: 'Tidak Dicatat' ?></span>
            </div>

            <div class="info-row">
                <span class="info-label">Waktu Masuk</span>
                <span class="info-value"><?= date('d/m/Y H:i:s', strtotime($transaksi['waktu_masuk'])) ?></span>
            </div>

            <?php if ($transaksi['waktu_keluar']): ?>
                <div class="info-row">
                    <span class="info-label">Waktu Keluar</span>
                    <span class="info-value"><?= date('d/m/Y H:i:s', strtotime($transaksi['waktu_keluar'])) ?></span>
                </div>
            <?php endif; ?>

            <div class="info-row">
                <span class="info-label">Durasi Parkir</span>
                <span class="info-value">
                    <?php
                    $jam = floor($durasi_menit / 60);
                    $menit = $durasi_menit % 60;
                    echo $jam > 0 ? "$jam jam $menit menit" : "$menit menit";
                    ?>
                    <span class="highlight">(<?= $durasi_menit ?> menit)</span>
                </span>
            </div>

            <div class="info-row" style="border-bottom: none;">
                <span class="info-label">Status</span>
                <span>
                    <?php if ($transaksi['status'] == 'masuk'): ?>
                        <span class="status-badge status-masuk">● SEDANG PARKIR</span>
                    <?php else: ?>
                        <span class="status-badge status-keluar">● SUDAH KELUAR</span>
                    <?php endif; ?>
                </span>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Rincian Tarif -->
        <div class="tarif-box">
            <h3>💰 RINCIAN PERHITUNGAN</h3>
            <ul style="list-style: none; padding: 0; margin: 5px 0;">
                <li>✓ Tarif jam pertama (1-60 menit): <strong>Rp 5.000</strong></li>
                <li>✓ Tarif per jam berikutnya: <strong>Rp 2.000</strong></li>
            </ul>

            <?php
            $jamPertama = min($durasi_menit, 60);
            $menitTambahan = max(0, $durasi_menit - 60);
            $jamTambahan = ceil($menitTambahan / 60);
            ?>

            <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #daa520;">
                <strong>Detail Perhitungan:</strong><br>
                <table style="width: 100%; margin-top: 5px; font-size: 10px;">
                    <tr>
                        <td>• Jam pertama (<?= $jamPertama ?> menit)</td>
                        <td style="text-align: right;"><strong>Rp 5.000</strong></td>
                    </tr>
                    <?php if ($menitTambahan > 0): ?>
                        <tr>
                            <td>• Tambahan <?= $menitTambahan ?> menit (<?= $jamTambahan ?> jam)</td>
                            <td style="text-align: right;"><strong>Rp
                                    <?= number_format($jamTambahan * 2000, 0, ',', '.') ?></strong></td>
                        </tr>
                    <?php endif; ?>
                    <tr style="border-top: 2px solid #daa520;">
                        <td><strong>TOTAL</strong></td>
                        <td style="text-align: right;"><strong>Rp <?= number_format($biaya, 0, ',', '.') ?></strong>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Total Biaya -->
        <div class="total-section">
            <h2>TOTAL BIAYA PARKIR</h2>
            <div class="total-amount">Rp <?= number_format($biaya, 0, ',', '.') ?></div>
            <p style="font-size: 9px; margin: 5px 0 0 0;">
                <?php if ($transaksi['status'] == 'keluar'): ?>
                    ✓ Pembayaran Lunas
                <?php else: ?>
                    ⚠ Belum Checkout - Biaya dapat berubah
                <?php endif; ?>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>═══ TERIMA KASIH ═══</strong></p>
            <p>Telah menggunakan layanan Parkir Modern</p>
            <p>Simpan struk ini sebagai bukti pembayaran yang sah</p>
            <div style="margin-top: 10px; padding-top: 8px; border-top: 1px dashed #ccc;">
                <p style="font-size: 8px;">
                    Struk ini dicetak secara otomatis oleh sistem<br>
                    Untuk pertanyaan atau keluhan, hubungi operator parkir<br>
                    <strong>Parkir Modern - Digital Parking System</strong>
                </p>
            </div>
        </div>
    </div>
</body>

</html>