<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Menu Bar CI4</title>

    <style>
        /* ---------- GLOBAL STYLE ---------- */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #F5F6FA;
        }

        /* ---------- NAVBAR WRAPPER ---------- */
        .navbar {
            background-color: #8FA9D2;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.25);
            padding: 0;
            width: 100%;
        }

        .navbar ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        /* ---------- MENU ITEM ---------- */
        .navbar ul li {
            position: relative;
        }

        .navbar ul li a {
            display: block;
            padding: 14px 25px;
            text-decoration: none;
            color: #FFFFFF;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.25s ease, padding 0.25s ease;
        }

        /* Efek hover lebih lembut */
        .navbar ul li a:hover {
            background-color: #4A7ACF;
            padding-left: 30px;
        }

        /* ---------- SUBMENU ---------- */
        .navbar ul li ul {
            display: none;
            position: absolute;
            background-color: #3C6BC5;
            padding: 4px 0;
            margin: 0;
            min-width: 210px;
            border-radius: 0 0 6px 6px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.25);
            z-index: 1000;
        }

        .navbar ul li ul li a {
            padding: 10px 20px;
            font-weight: normal;
            border-bottom: 1px solid #2E5CB5;
        }

        .navbar ul li ul li:last-child a {
            border-bottom: none;
        }

        .navbar ul li ul li a:hover {
            background-color: #2E5CB5;
            padding-left: 25px;
        }

        /* Tampilkan submenu */
        .navbar ul li:hover ul {
            display: block;
        }

        /* ---------- FOOTER ---------- */
        .footer {
            background-color: #8FA9D2;
            color: #FFFFFF;
            text-align: center;
            padding: 12px;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 0.5px;
            box-shadow: 0px -2px 6px rgba(0,0,0,0.25);
        }

        /* Sedikit ruang agar konten tidak tertutup footer */
        .content {
            padding: 0px 0px 0px;
        }
    </style>
</head>

<body>

    <!-- NAVIGATION BAR -->
    <nav class="navbar">
        <ul>
            <li><a href="<?= base_url('/') ?>">Home</a></li>

            <li>
                <a href="#">Data</a>
                <ul>
                    <li><a href="<?= base_url('mahasiswa/input') ?>">Input Data Mahasiswa</a></li>
                    <li><a href="<?= base_url('mahasiswa/edit') ?>">Edit Data Mahasiswa</a></li>
                </ul>
            </li>

            <li><a href="<?= base_url('/about') ?>">About</a></li>
        </ul>
    </nav>

    <!-- CONTENT WRAPPER -->
    <div class="content">
        <?= $this->renderSection('content') ?>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        Institut Widya Pratama
    </div>

</body>
</html>