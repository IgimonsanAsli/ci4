<?php

namespace App\Controllers;

use App\Models\ParkirModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Parkir extends BaseController
{
    protected $parkirModel;

    public function __construct()
    {
        $this->parkirModel = new ParkirModel();
        helper(['url', 'form']);
    }

    /**
     * Halaman utama
     */
    public function index()
    {
        $data = [
            'title' => 'Sistem Parkir Modern',
            'transaksi' => $this->parkirModel->getTransaksiHariIni(),
            'pendapatan' => $this->parkirModel->getPendapatanHariIni()
        ];

        return view('parkir/index', $data);
    }

    /**
     * Generate QR Code dan simpan transaksi
     */
    public function generate()
    {
        try {
            // Generate ID Parkir
            $idParkir = $this->parkirModel->generateIdParkir();
            $platNomor = $this->request->getPost('plat_nomor');

            // URL untuk QR Code (link ke detail parkir)
            $detailUrl = base_url('parkir/detail/' . $idParkir);

            // Generate QR Code dari API
            $qrImage = $this->generateQRCode($detailUrl);

            if (!$qrImage) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal generate QR Code'
                ]);
            }

            // Simpan transaksi
            $data = [
                'id_parkir' => $idParkir,
                'plat_nomor' => $platNomor,
                'waktu_masuk' => date('Y-m-d H:i:s'),
                'biaya' => 5000,
                'status' => 'masuk',
                'qr_image' => $qrImage
            ];

            $this->parkirModel->insert($data);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'QR Code berhasil digenerate',
                'data' => [
                    'id_parkir' => $idParkir,
                    'qr_image' => $qrImage,
                    'detail_url' => $detailUrl
                ]
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Generate QR Code menggunakan API eksternal
     */
    private function generateQRCode($text)
    {
        $apiUrl = getenv('qr.api.url');
        $apiKey = getenv('qr.api.key');

        $url = $apiUrl . '?text=' . urlencode($text) . '&apikey=' . $apiKey;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200 && $response) {
            // Encode image to base64
            return base64_encode($response);
        }

        return false;
    }

    /**
     * Detail parkir (saat QR di-scan)
     */
    public function detail($idParkir)
    {
        $transaksi = $this->parkirModel->getByIdParkir($idParkir);

        if (!$transaksi) {
            return view('errors/html/error_404');
        }

        // Hitung biaya terkini
        $biayaInfo = $this->parkirModel->hitungBiaya($transaksi['waktu_masuk']);

        $data = [
            'title' => 'Detail Parkir',
            'transaksi' => $transaksi,
            'durasi_menit' => $biayaInfo['durasi_menit'],
            'biaya_terkini' => $biayaInfo['biaya']
        ];

        return view('parkir/detail', $data);
    }

    /**
     * Generate PDF untuk pembayaran
     */
    public function generatePDF($idParkir)
    {
        $transaksi = $this->parkirModel->getByIdParkir($idParkir);

        if (!$transaksi) {
            return redirect()->to('/')->with('error', 'Transaksi tidak ditemukan');
        }

        // Hitung biaya
        $biayaInfo = $this->parkirModel->hitungBiaya($transaksi['waktu_masuk']);

        $data = [
            'transaksi' => $transaksi,
            'durasi_menit' => $biayaInfo['durasi_menit'],
            'biaya' => $biayaInfo['biaya']
        ];

        // Generate PDF
        $html = view('parkir/pdf_template', $data);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream('parkir-' . $idParkir . '.pdf', ['Attachment' => false]);
    }

    /**
     * Proses checkout/keluar parkir
     */
    public function checkout()
    {
        $idParkir = $this->request->getPost('id_parkir');
        $transaksi = $this->parkirModel->getByIdParkir($idParkir);

        if (!$transaksi) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Transaksi tidak ditemukan'
            ]);
        }

        if ($transaksi['status'] == 'keluar') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Transaksi sudah checkout'
            ]);
        }

        // Hitung biaya
        $biayaInfo = $this->parkirModel->hitungBiaya($transaksi['waktu_masuk']);

        // Update transaksi
        $this->parkirModel->update($transaksi['id'], [
            'waktu_keluar' => date('Y-m-d H:i:s'),
            'durasi_menit' => $biayaInfo['durasi_menit'],
            'biaya' => $biayaInfo['biaya'],
            'status' => 'keluar'
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Checkout berhasil',
            'data' => [
                'durasi_menit' => $biayaInfo['durasi_menit'],
                'biaya' => $biayaInfo['biaya']
            ]
        ]);
    }

    /**
     * Dashboard data untuk AJAX
     */
    public function getDashboardData()
    {
        $transaksi = $this->parkirModel->getTransaksiHariIni();
        $pendapatan = $this->parkirModel->getPendapatanHariIni();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'transaksi' => $transaksi,
                'pendapatan' => $pendapatan,
                'total_kendaraan' => count($transaksi)
            ]
        ]);
    }
}