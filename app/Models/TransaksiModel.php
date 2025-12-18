<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table = 'parkir_transaksi';
    protected $primaryKey = 'id';
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
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Generate ID Parkir
     * Format: 01-18122025 (nomor urut-ddmmyyyy)
     */
    public function generateIdParkir()
    {
        $today = date('dmY'); // 18122025

        // Get last transaction today using LIKE
        $builder = $this->db->table($this->table);
        $lastTransaction = $builder
            ->select('id_parkir')
            ->like('id_parkir', "-{$today}", 'after')
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        if ($lastTransaction) {
            // Extract number from id_parkir (01-18122025 -> 01)
            $parts = explode('-', $lastTransaction['id_parkir']);
            $lastNumber = (int) $parts[0];
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Format: 01-18122025
        return sprintf('%02d-%s', $newNumber, $today);
    }

    /**
     * Parse ID Parkir untuk mendapatkan tanggal
     * Format: 01-18122025 (nomor-ddmmyyyy)
     */
    public function parseTanggalFromIdParkir($idParkir)
    {
        $parts = explode('-', $idParkir);
        if (count($parts) !== 2) {
            return null;
        }

        $tanggalStr = $parts[1]; // 18122025
        $hari = substr($tanggalStr, 0, 2);
        $bulan = substr($tanggalStr, 2, 2);
        $tahun = substr($tanggalStr, 4, 4);

        return "$tahun-$bulan-$hari";
    }

    /**
     * Laporan Harian - DIPERBAIKI
     */
    public function laporanHarian($tanggal)
    {
        // Format tanggal untuk ID Parkir: ddmmyyyy
        $dateObj = new \DateTime($tanggal);
        $ddmmyyyy = $dateObj->format('dmY');

        // Gunakan query builder baru setiap kali
        $builder = $this->db->table($this->table);
        $results = $builder
            ->like('id_parkir', "-{$ddmmyyyy}", 'after')
            ->orderBy('waktu_masuk', 'DESC')
            ->get()
            ->getResultArray();

        return $results;
    }

    /**
     * Laporan Mingguan - DIPERBAIKI
     */
    public function laporanMingguan($tanggalMulai, $tanggalAkhir)
    {
        $results = [];
        $currentDate = new \DateTime($tanggalMulai);
        $endDate = new \DateTime($tanggalAkhir);

        // Loop untuk setiap hari dalam minggu
        while ($currentDate <= $endDate) {
            $ddmmyyyy = $currentDate->format('dmY');

            // Buat builder baru untuk setiap query
            $builder = $this->db->table($this->table);
            $transaksi = $builder
                ->like('id_parkir', "-{$ddmmyyyy}", 'after')
                ->get()
                ->getResultArray();

            if (!empty($transaksi)) {
                $results = array_merge($results, $transaksi);
            }

            $currentDate->modify('+1 day');
        }

        // Sort by waktu_masuk DESC
        if (!empty($results)) {
            usort($results, function ($a, $b) {
                return strtotime($b['waktu_masuk']) - strtotime($a['waktu_masuk']);
            });
        }

        return $results;
    }

    /**
     * Laporan Bulanan - DIPERBAIKI
     */
    public function laporanBulanan($bulan, $tahun)
    {
        $results = [];
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, (int) $bulan, (int) $tahun);

        // Loop untuk setiap hari dalam bulan
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $ddmmyyyy = sprintf('%02d%02d%04d', $day, $bulan, $tahun);

            // Buat builder baru untuk setiap query
            $builder = $this->db->table($this->table);
            $transaksi = $builder
                ->like('id_parkir', "-{$ddmmyyyy}", 'after')
                ->get()
                ->getResultArray();

            if (!empty($transaksi)) {
                $results = array_merge($results, $transaksi);
            }
        }

        // Sort by waktu_masuk DESC
        if (!empty($results)) {
            usort($results, function ($a, $b) {
                return strtotime($b['waktu_masuk']) - strtotime($a['waktu_masuk']);
            });
        }

        return $results;
    }

    /**
     * Hitung total pendapatan dari array transaksi
     */
    public function hitungTotalPendapatan($transaksi)
    {
        $total = 0;
        foreach ($transaksi as $t) {
            // Hanya hitung yang sudah keluar
            if ($t['status'] === 'keluar') {
                $total += $t['biaya'];
            }
        }
        return $total;
    }

    /**
     * Get statistik ringkasan - DIPERBAIKI
     */
    public function getStatistik($transaksi)
    {
        $totalKendaraan = count($transaksi);
        $totalPendapatan = $this->hitungTotalPendapatan($transaksi);
        $masih_parkir = count(array_filter($transaksi, fn($t) => $t['status'] === 'masuk'));
        $sudah_keluar = count(array_filter($transaksi, fn($t) => $t['status'] === 'keluar'));

        return [
            'total_kendaraan' => $totalKendaraan,
            'total_pendapatan' => $totalPendapatan,
            'masih_parkir' => $masih_parkir,
            'sudah_keluar' => $sudah_keluar
        ];
    }

    /**
     * Get Transaksi by ID Parkir
     */
    public function getByIdParkir($idParkir)
    {
        $builder = $this->db->table($this->table);
        return $builder->where('id_parkir', $idParkir)->get()->getRowArray();
    }

    /**
     * Update Status Keluar
     */
    public function updateKeluar($idParkir, $biaya, $durasi)
    {
        return $this->db->table($this->table)
            ->where('id_parkir', $idParkir)
            ->update([
                'waktu_keluar' => date('Y-m-d H:i:s'),
                'durasi_menit' => $durasi,
                'biaya' => $biaya,
                'status' => 'keluar',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    }

    /**
     * Get Kendaraan yang Masih Parkir
     */
    public function getKendaraanParkir()
    {
        $builder = $this->db->table($this->table);
        return $builder
            ->where('status', 'masuk')
            ->orderBy('waktu_masuk', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Cari Transaksi (untuk kasir)
     */
    public function cariTransaksi($keyword)
    {
        $builder = $this->db->table($this->table);
        return $builder
            ->where('status', 'masuk')
            ->groupStart()
            ->like('id_parkir', $keyword)
            ->orLike('plat_nomor', $keyword)
            ->groupEnd()
            ->orderBy('waktu_masuk', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get Pendapatan Hari Ini
     */
    public function getPendapatanHariIni()
    {
        $today = date('dmY');
        $builder = $this->db->table($this->table);
        $transaksi = $builder
            ->like('id_parkir', "-{$today}", 'after')
            ->where('status', 'keluar')
            ->get()
            ->getResultArray();

        return $this->hitungTotalPendapatan($transaksi);
    }

    /**
     * Get Total Kendaraan Hari Ini
     */
    public function getTotalKendaraanHariIni()
    {
        $today = date('dmY');
        $builder = $this->db->table($this->table);
        return $builder
            ->like('id_parkir', "-{$today}", 'after')
            ->countAllResults();
    }

    /**
     * Get Kendaraan Masih Parkir Hari Ini
     */
    public function getKendaraanMasihParkirHariIni()
    {
        $today = date('dmY');
        $builder = $this->db->table($this->table);
        return $builder
            ->like('id_parkir', "-{$today}", 'after')
            ->where('status', 'masuk')
            ->countAllResults();
    }

    /**
     * Debug: Tampilkan semua transaksi
     */
    public function getAllTransaksi()
    {
        $builder = $this->db->table($this->table);
        return $builder
            ->orderBy('waktu_masuk', 'DESC')
            ->get()
            ->getResultArray();
    }
}