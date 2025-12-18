<?php

namespace App\Models;

use CodeIgniter\Model;

class ParkirModel extends Model
{
    protected $table = 'parkir_transaksi';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_parkir',
        'plat_nomor',
        'waktu_masuk',
        'waktu_keluar',
        'durasi_menit',
        'biaya',
        'status',
        'qr_image'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'id_parkir' => 'required|is_unique[parkir_transaksi.id_parkir]',
        'waktu_masuk' => 'required'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Generate ID Parkir
     * Format: XX-DDMMYYYY (XX = nomor urut hari ini, DDMMYYYY = tanggal)
     */
    public function generateIdParkir()
    {
        $today = date('dmY');

        // Hitung jumlah transaksi hari ini
        $count = $this->where('DATE(waktu_masuk)', date('Y-m-d'))
            ->countAllResults();

        $nomorUrut = str_pad($count + 1, 2, '0', STR_PAD_LEFT);

        return $nomorUrut . '-' . $today;
    }

    /**
     * Get transaksi by ID Parkir
     */
    public function getByIdParkir($idParkir)
    {
        return $this->where('id_parkir', $idParkir)->first();
    }

    /**
     * Hitung biaya parkir
     */
    public function hitungBiaya($waktuMasuk, $waktuKeluar = null)
    {
        // Set timezone
        date_default_timezone_set('Asia/Jakarta');

        if ($waktuKeluar === null) {
            $waktuKeluar = date('Y-m-d H:i:s');
        }

        // Konversi ke timestamp
        $masuk = strtotime($waktuMasuk);
        $keluar = strtotime($waktuKeluar);

        // Hitung durasi dalam detik
        $durasi = $keluar - $masuk;

        // Pastikan durasi tidak negatif
        if ($durasi < 0) {
            $durasi = 0;
        }

        // Konversi ke menit (round up)
        $durasiMenit = ceil($durasi / 60);

        // Tarif: 5000 untuk jam pertama (0-60 menit), +2000 per jam berikutnya
        $tarifAwal = 5000;
        $tarifPerJam = 2000;

        // Perhitungan biaya
        if ($durasiMenit <= 60) {
            // Jam pertama atau kurang
            $biaya = $tarifAwal;
        } else {
            // Hitung jam tambahan (setiap 60 menit = 1 jam)
            $menitTambahan = $durasiMenit - 60;
            $jamTambahan = ceil($menitTambahan / 60);
            $biaya = $tarifAwal + ($jamTambahan * $tarifPerJam);
        }

        return [
            'durasi_menit' => $durasiMenit,
            'durasi_detik' => $durasi,
            'biaya' => $biaya,
            'waktu_masuk' => $waktuMasuk,
            'waktu_keluar' => $waktuKeluar
        ];
    }

    /**
     * Get transaksi hari ini
     */
    public function getTransaksiHariIni()
    {
        return $this->where('DATE(waktu_masuk)', date('Y-m-d'))
            ->orderBy('waktu_masuk', 'DESC')
            ->findAll();
    }

    /**
     * Get total pendapatan hari ini
     */
    public function getPendapatanHariIni()
    {
        $result = $this->selectSum('biaya')
            ->where('DATE(waktu_masuk)', date('Y-m-d'))
            ->where('status', 'keluar')
            ->first();

        return $result['biaya'] ?? 0;
    }
}