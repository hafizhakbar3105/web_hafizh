<?php
session_start();

// --- 1. PROTEKSI SESSION INTERNAL ACCESS ---
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';
/** @var mysqli $conn */ 

// Ambil pesan dari session jika ada, lalu hapus session-nya agar tidak muncul terus-menerus
$msg_status = $_SESSION['success_msg'] ?? "";
unset($_SESSION['success_msg']);

// --- 2. LOGIKA HAPUS PRODUK ---
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['hapus']);
    
    $ambil_gambar = mysqli_query($conn, "SELECT gambar FROM produk WHERE id = '$id_hapus'");
    $data_gambar = mysqli_fetch_assoc($ambil_gambar);
    $nama_file = $data_gambar['gambar'] ?? '';

    if (mysqli_query($conn, "DELETE FROM produk WHERE id = '$id_hapus'")) {
        if ($nama_file && file_exists("../assets/img/" . $nama_file)) {
            unlink("../assets/img/" . $nama_file);
        }
        $_SESSION['success_msg'] = "Unit instrumen berhasil dihapus!";
        header("Location: admin.php");
        exit;
    }
}

// --- 3. LOGIKA TAMBAH KATEGORI BARU ---
if (isset($_POST['tambah_kategori_baru'])) {
    $kategori_baru = mysqli_real_escape_string($conn, $_POST['nama_kategori_baru']);
    
    if (!empty($kategori_baru)) {
        $cek_kategori = mysqli_query($conn, "SELECT * FROM kategori WHERE nama_kategori = '$kategori_baru'");
        if (mysqli_num_rows($cek_kategori) == 0) {
            mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$kategori_baru')");
            $_SESSION['success_msg'] = "Kategori baru berhasil ditambahkan!";
            header("Location: admin.php");
            exit;
        } else {
            echo "<script>alert('Sistem: Kategori sudah terdaftar!');</script>";
        }
    }
}

// LOGIKA HAPUS KATEGORI
if (isset($_GET['hapus_kategori'])) {
    $id_kat_hapus = mysqli_real_escape_string($conn, $_GET['hapus_kategori']);
    if (mysqli_query($conn, "DELETE FROM kategori WHERE id = '$id_kat_hapus'")) {
        $_SESSION['success_msg'] = "Kategori berhasil dihapus dari sistem!";
        header("Location: admin.php");
        exit;
    }
}

// --- 4. LOGIKA TAMBAH PRODUK ---
if (isset($_POST['tambah_produk'])) {
    $nama      = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $kategori  = mysqli_real_escape_string($conn, $_POST['kategori']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga     = mysqli_real_escape_string($conn, $_POST['harga']);
    $gambar    = $_FILES['gambar']['name'];
    
    if (mysqli_query($conn, "INSERT INTO produk (nama_produk, kategori, deskripsi, harga, gambar) VALUES ('$nama', '$kategori', '$deskripsi', '$harga', '$gambar')")) {
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../assets/img/" . $gambar);
        $_SESSION['success_msg'] = "Produk berhasil ditambahkan!";
        header("Location: admin.php");
        exit;
    }
}

// --- 5. LOGIKA UPDATE/EDIT PRODUK ---
if (isset($_POST['update_produk'])) {
    $id_edit   = mysqli_real_escape_string($conn, $_POST['id_produk']);
    $nama      = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $kategori  = mysqli_real_escape_string($conn, $_POST['kategori']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga     = mysqli_real_escape_string($conn, $_POST['harga']); 

    if ($_FILES['gambar']['name'] != "") {
        $gambar = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../assets/img/" . $gambar);
        $sql = "UPDATE produk SET nama_produk='$nama', kategori='$kategori', deskripsi='$deskripsi', harga='$harga', gambar='$gambar' WHERE id='$id_edit'";
    } else {
        $sql = "UPDATE produk SET nama_produk='$nama', kategori='$kategori', deskripsi='$deskripsi', harga='$harga' WHERE id='$id_edit'";
    }

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success_msg'] = "Data produk diperbarui!";
        header("Location: admin.php");
        exit;
    }
}

// --- 6. LOGIKA EDIT PERMINTAAN LAYANAN KLIEN ---
if (isset($_POST['update_layanan'])) {
    $id_layanan_edit = mysqli_real_escape_string($conn, $_POST['id_layanan']);
    $nama_l          = mysqli_real_escape_string($conn, $_POST['nama_layanan']);
    $email_l         = mysqli_real_escape_string($conn, $_POST['email_layanan']);
    $telepon_l       = mysqli_real_escape_string($conn, $_POST['telepon_layanan']);
    $kategori_l      = mysqli_real_escape_string($conn, $_POST['kategori_layanan']);
    $pesan_l         = mysqli_real_escape_string($conn, $_POST['pesan_layanan']);

    $sql_l = "UPDATE layanan SET nama='$nama_l', email='$email_l', telepon='$telepon_l', kategori='$kategori_l', pesan='$pesan_l' WHERE id='$id_layanan_edit'";
    if (mysqli_query($conn, $sql_l)) {
        $_SESSION['success_msg'] = "Data layanan diperbarui!";
        header("Location: admin.php");
        exit;
    }
}

// --- 7. LOGIKA HAPUS PERMINTAAN LAYANAN KLIEN ---
if (isset($_GET['hapus_layanan'])) {
    $id_layanan_hapus = mysqli_real_escape_string($conn, $_GET['hapus_layanan']);
    if (mysqli_query($conn, "DELETE FROM layanan WHERE id = '$id_layanan_hapus'")) {
        $_SESSION['success_msg'] = "Data ulasan berhasil dihapus!";
        header("Location: admin.php");
        exit;
    }
}

// --- 8. LOGIKA LAPORAN INVENTORY ---
$data_inventory = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM produk"));
$total_unit = $data_inventory['total'] ?? 0;

$data_layanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM layanan"));
$total_layanan = $data_layanan['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Nusa Geospatial Solutions</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        
        .dataTables_wrapper { padding: 1.5rem; background: white; border-radius: 1.5rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        table.dataTable { border-collapse: collapse !important; width: 100% !important; margin: 1rem 0 !important; }
        table.dataTable thead th { background-color: #f8fafc; color: #64748b !important; font-weight: 700 !important; text-transform: uppercase; font-size: 10px; letter-spacing: 0.05em; padding: 1rem !important; border-bottom: 1px solid #e2e8f0 !important; }
        table.dataTable tbody td { padding: 1rem !important; border-bottom: 1px solid #f1f5f9 !important; font-size: 13px; color: #334155; }
        table.dataTable.no-footer { border-bottom: none; }
        
        /* Menyembunyikan tombol asli DataTables */
        .dt-buttons { display: none !important; }
        
        .dataTables_filter input {
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.5rem 1rem;
            outline: none;
            font-size: 13px;
            transition: all 0.2s;
        }
        .dataTables_filter input:focus { border-color: #5AAC41; background-color: white; }

        #signature-pad-admin {
            border: 2px dashed #cbd5e1;
            border-radius: 1rem;
            cursor: crosshair;
            background-color: #f8fafc;
            touch-action: none;
        }
    </style>
</head>
<body class="bg-[#f8fafc] flex flex-col min-h-screen">

    <nav class="w-full bg-white border-b border-slate-100 px-6 md:px-12 py-4 flex items-center justify-between sticky top-0 z-50 shadow-sm">
        <div class="flex items-center gap-2">
            <img src="../assets/img/NGS.png" alt="Logo" class="h-10 w-auto">
            <div class="h-6 w-[1px] bg-slate-200 mx-2 hidden sm:block"></div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest hidden sm:block">Control Panel</span>
        </div>
        
        <div class="flex items-center gap-6 text-xs font-bold text-slate-500">
            <a href="admin.php" class="text-[#043978] border-b-2 border-[#043978] pb-1">DASHBOARD ADMIN</a>
            <a href="../home.php" target="_blank" class="hover:text-[#5AAC41] transition">LIHAT SITUS</a>
        </div>
        
        <div>
            <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')" class="border border-red-200 text-red-500 hover:bg-red-50 hover:text-red-600 px-4 py-2 rounded-xl font-bold text-xs uppercase tracking-wider transition flex items-center gap-2 shadow-sm">
                <i class="fas fa-power-off text-[10px]"></i> Logout
            </a>
        </div>
    </nav>

    <main class="max-w-7xl w-full mx-auto px-6 md:px-12 py-10 flex-grow">
        
        <div class="bg-gradient-to-br from-[#043978] to-[#14478a] rounded-3xl p-8 md:p-10 text-white shadow-xl mb-12 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
            <div>
                <span class="text-[9px] font-black uppercase tracking-widest bg-white/15 px-3 py-1 rounded-full text-yellow-300 border border-white/10">Administrator Portal</span>
                <h2 class="text-2xl md:text-3xl font-black tracking-tight mt-3 uppercase">Selamat Datang, Admin</h2>
                <p class="text-xs text-blue-100/80 mt-2 max-w-xl font-medium leading-relaxed">Anda memiliki akses penuh untuk mengelola konten halaman beranda. Pastikan data yang dimasukkan akurat dan sesuai dengan standar perusahaan.</p>
            </div>
            
            <div class="flex gap-4 w-full md:w-auto">
                <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/10 text-center flex-1 md:flex-none min-w-[100px]">
                    <h4 class="text-2xl font-black text-white"><?= $total_unit; ?></h4>
                    <p class="text-[9px] font-bold text-blue-200 uppercase tracking-wider mt-1">Total Produk</p>
                </div>
                <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/10 text-center flex-1 md:flex-none min-w-[100px]">
                    <h4 class="text-2xl font-black text-white"><?= $total_layanan; ?></h4>
                    <p class="text-[9px] font-bold text-blue-200 uppercase tracking-wider mt-1">Ulasan Aktif</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-between sm:items-end mb-6 gap-4">
            <div>
                <h3 class="text-base font-black text-slate-800 tracking-tight uppercase flex items-center gap-2">
                    <div class="w-1.5 h-4 bg-[#043978] rounded-full"></div> KELOLA <span class="text-[#195994] font-light italic">PRODUK UTAMA</span>
                </h3>
                <p class="text-[11px] text-slate-400 font-semibold mt-1">Gunakan tombol unduh di bawah untuk mengekspor data rekapitulasi inventaris resmi.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="document.getElementById('modal-kategori').classList.remove('hidden')" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-5 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition shadow-sm flex items-center gap-2">
                    <i class="fas fa-folder-plus text-[10px] text-[#043978]"></i> Kelola Kategori
                </button>
                <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')" class="bg-[#043978] hover:bg-[#5AAC41] text-white px-5 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition shadow-md flex items-center gap-2">
                    <i class="fas fa-plus text-[10px]"></i> Tambah Alat Baru
                </button>
            </div>
        </div>

        <div class="flex gap-2 mb-4">
            <button onclick="bukaModalTtd('pdf')" class="bg-[#043978] hover:bg-[#5AAC41] text-white text-xs font-bold px-5 py-3 rounded-xl transition shadow-sm flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> Export PDF Resmi
            </button>
            <button onclick="bukaModalTtd('excel')" class="bg-[#043978] hover:bg-[#5AAC41] text-white text-xs font-bold px-5 py-3 rounded-xl transition shadow-sm flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden mb-14 p-2">
            <table id="laporanTable" class="w-full text-left display">
                <thead>
                    <tr>
                        <th class="text-center w-12">No</th>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th class="text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php
                    $no = 1;
                    $res = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
                    while($row = mysqli_fetch_assoc($res)) {
                    ?>
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="text-center font-bold text-slate-400 text-xs"><?= $no++; ?></td>
                        <td>
                            <img src="../assets/img/<?= $row['gambar']; ?>" class="w-10 h-10 rounded-xl object-cover bg-slate-50 border border-slate-100 p-1" onerror="this.src='../assets/img/Topcon_GM100_c.png'">
                        </td>
                        <td class="font-bold text-slate-700 uppercase tracking-tight text-xs"><?= htmlspecialchars($row['nama_produk']); ?></td>
                        <td>
                            <span class="text-[8px] font-black text-[#043978] bg-blue-50 px-2.5 py-1 rounded-md uppercase tracking-wider border border-blue-100/50"><?= htmlspecialchars($row['kategori']); ?></span>
                        </td>
                        <td class="font-extrabold text-[#5AAC41] text-xs">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                        <td>
                            <div class="flex justify-center items-center gap-1.5">
                                <button onclick='bukaEdit(<?= json_encode($row); ?>)' class="w-7 h-8 rounded-lg bg-slate-50 border border-slate-200 text-slate-400 hover:bg-[#043978] hover:text-white hover:border-transparent transition flex items-center justify-center">
                                    <i class="fas fa-edit text-[10px]"></i>
                                </button>
                                <a href="admin.php?hapus=<?= $row['id']; ?>" onclick="return confirm('Yakin hapus unit ini?')" class="w-7 h-8 rounded-lg bg-slate-50 border border-slate-200 text-slate-400 hover:bg-red-500 hover:text-white hover:border-transparent transition flex items-center justify-center">
                                    <i class="fas fa-trash text-[10px]"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="flex flex-col sm:flex-row justify-between sm:items-end mb-6 gap-4">
            <div>
                <h3 class="text-base font-black text-slate-800 tracking-tight uppercase flex items-center gap-2">
                    <div class="w-1.5 h-4 bg-[#5AAC41] rounded-full"></div> KELOLA <span class="text-[#5AAC41] font-light italic">INSIGHT & LAYANAN</span>
                </h3>
                <p class="text-[11px] text-slate-400 font-semibold mt-1">Dokumentasi kegiatan lapangan dan publikasi ulasan artikel teknis.</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-2">
            <table id="layananTable" class="w-full text-left display">
                <thead>
                    <tr>
                        <th class="text-center w-12">No</th>
                        <th>Nama Pemohon</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Kategori</th>
                        <th>Pesan</th>
                        <th class="text-center">Validasi TTD</th>
                        <th class="text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php
                    $no_l = 1;
                    $res_l = mysqli_query($conn, "SELECT * FROM layanan ORDER BY id DESC");
                    while($row_l = mysqli_fetch_assoc($res_l)) {
                    ?>
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="text-center font-bold text-slate-400 text-xs"><?= $no_l++; ?></td>
                        <td class="font-bold text-slate-700 text-xs uppercase tracking-tight"><?= htmlspecialchars($row_l['nama']); ?></td>
                        <td class="font-medium text-slate-500 text-xs"><?= htmlspecialchars($row_l['email']); ?></td>
                        <td class="font-bold text-slate-600 text-xs"><?= htmlspecialchars($row_l['telepon'] ?? '-'); ?></td>
                        <td>
                            <span class="text-[8px] font-black text-[#5AAC41] bg-green-50 px-2.5 py-1 rounded-md uppercase tracking-wider border border-green-100/50"><?= htmlspecialchars($row_l['kategori']); ?></span>
                        </td>
                        <td class="text-xs text-slate-400 max-w-xs truncate"><?= htmlspecialchars($row_l['pesan']); ?></td>
                        <td>
                            <div class="flex justify-center">
                                <img src="<?= $row_l['ttd_data']; ?>" class="h-10 w-auto bg-slate-50 border border-slate-100 rounded-lg p-1 object-contain" alt="TTD">
                            </div>
                        </td>
                        <td>
                            <div class="flex justify-center items-center gap-1.5">
                                <button onclick='bukaEditLayanan(<?= json_encode($row_l); ?>)' class="w-7 h-8 rounded-lg bg-slate-50 border border-slate-200 text-slate-400 hover:bg-[#043978] hover:text-white hover:border-transparent transition flex items-center justify-center">
                                    <i class="fas fa-edit text-[10px]"></i>
                                </button>
                                <a href="admin.php?hapus_layanan=<?= $row_l['id']; ?>" onclick="return confirm('Yakin hapus data ini?')" class="w-7 h-8 rounded-lg bg-slate-50 border border-slate-200 text-slate-400 hover:bg-red-500 hover:text-white hover:border-transparent transition flex items-center justify-center">
                                    <i class="fas fa-trash text-[10px]"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

    <div id="modal-ttd-export" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[150] flex items-center justify-center hidden">
        <div class="bg-white w-full max-w-xl rounded-[2rem] p-8 relative mx-4 shadow-2xl border border-slate-100">
            <button onclick="tutupModalTtd()" class="absolute top-6 right-6 text-slate-300 hover:text-red-500 transition"><i class="fas fa-times-circle text-xl"></i></button>
            
            <h2 class="text-xl font-black text-slate-800 mb-1 flex items-center gap-2"><i class="fas fa-file-signature text-[#043978]"></i> Verifikasi Penanggung Jawab</h2>
            <p class="text-xs text-slate-400 mb-6">Silakan masukkan nama lengkap dan bubuhkan tanda tangan digital Anda di bawah ini sebelum mengunduh berkas.</p>
            
            <div class="mb-4">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Nama Lengkap Pelapor / Admin</label>
                <input type="text" id="nama_pelapor_pdf" placeholder="Masukkan nama lengkap Anda..." required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 outline-none font-semibold text-xs focus:ring-2 focus:ring-[#5AAC41] focus:bg-white transition">
            </div>

            <div class="relative w-full mb-6">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Goresan Tanda Tangan</label>
                <canvas id="signature-pad-admin" class="w-full h-44"></canvas>
                <button type="button" onclick="clearAdminCanvas()" class="absolute top-9 right-3 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white text-[10px] font-bold px-2.5 py-1.5 rounded-lg transition shadow-sm">
                    <i class="fas fa-eraser"></i> Reset Goresan
                </button>
            </div>
            
            <button id="btnConfirmExport" class="w-full bg-[#5AAC41] hover:bg-[#4d9437] text-white py-4 rounded-xl font-bold uppercase tracking-widest text-xs transition shadow-lg shadow-green-900/20">
                Konfirmasi TTD & Unduh Berkas
            </button>
        </div>
    </div>

    <div id="modal-kategori" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center hidden">
        <div class="bg-white w-full max-w-lg rounded-[2rem] p-8 relative mx-4 max-h-[95vh] flex flex-col">
            <button onclick="document.getElementById('modal-kategori').classList.add('hidden')" class="absolute top-6 right-6 text-slate-300 hover:text-red-500 transition"><i class="fas fa-times-circle text-xl"></i></button>
            <h2 class="text-xl font-black text-slate-800 mb-1">Manajemen Kategori</h2>
            <p class="text-xs text-slate-400 mb-5">Tambahkan kategori instrumen baru atau hapus klasifikasi yang tidak digunakan lagi.</p>
            <form action="" method="POST" class="space-y-3 mb-6 pb-6 border-b border-slate-100">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tambah Kategori Baru</label>
                <div class="flex gap-2">
                    <input type="text" name="nama_kategori_baru" placeholder="Contoh: Drone LiDAR, Global GPS" required class="flex-1 px-4 py-3 rounded-xl bg-slate-50 border border-slate-100 outline-none font-semibold text-xs focus:ring-2 focus:ring-[#5AAC41] focus:bg-white transition">
                    <button type="submit" name="tambah_kategori_baru" class="bg-[#043978] hover:bg-[#5AAC41] text-white px-5 rounded-xl font-bold uppercase text-[10px] transition shadow-md">Simpan</button>
                </div>
            </form>
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Daftar Kategori Aktif</label>
            <div class="flex-1 overflow-y-auto space-y-2 max-h-64 pr-1">
                <?php 
                $list_kat = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                while($lk = mysqli_fetch_assoc($list_kat)) {
                ?>
                    <div class="flex justify-between items-center bg-slate-50 border border-slate-100 rounded-xl px-4 py-3">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-tight"><?= htmlspecialchars($lk['nama_kategori']); ?></span>
                        <a href="admin.php?hapus_kategori=<?= $lk['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')" class="text-red-400 hover:text-red-600 text-xs p-1.5 transition">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div id="modal-tambah" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center hidden">
        <div class="bg-white w-full max-w-lg rounded-[2rem] p-8 relative mx-4 max-h-[90vh] overflow-y-auto">
            <button onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="absolute top-6 right-6 text-slate-300 hover:text-red-500 transition"><i class="fas fa-times-circle text-xl"></i></button>
            <h2 class="text-xl font-black text-slate-800 mb-5">Registrasi Alat Baru</h2>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="text" name="nama_produk" placeholder="Nama Produk Instrumen" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-100 outline-none font-semibold text-xs focus:ring-2 focus:ring-[#5AAC41]">
                <select name="kategori" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-100 outline-none font-semibold text-xs focus:ring-2 focus:ring-[#5AAC41]">
                    <?php 
                    $get_kat = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                    while($k = mysqli_fetch_assoc($get_kat)) {
                        echo "<option value='".htmlspecialchars($k['nama_kategori'])."'>".htmlspecialchars($k['nama_kategori'])."</option>";
                    }
                    ?>
                </select>
                <input type="number" name="harga" placeholder="Harga Jual / Sewa (IDR)" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-100 outline-none font-semibold text-xs focus:ring-2 focus:ring-[#5AAC41]">
                <textarea name="deskripsi" placeholder="Deskripsi Spesifikasi Teknis Alat..." required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-100 outline-none font-semibold text-xs h-24 focus:ring-2 focus:ring-[#5AAC41]"></textarea>
                <div class="bg-slate-50 p-4 rounded-xl border border-dashed border-slate-200">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Upload Foto Gambar</label>
                    <input type="file" name="gambar" required class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#043978] hover:file:bg-blue-100">
                </div>
                <button type="submit" name="tambah_produk" class="w-full bg-[#5AAC41] hover:bg-[#4d9437] text-white py-3.5 rounded-xl font-bold uppercase tracking-widest text-[10px] transition shadow-md">Simpan Unit Alat</button>
            </form>
        </div>
    </div>

    <div id="modal-edit" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center hidden">
        <div class="bg-white w-full max-w-lg rounded-[2rem] p-8 relative mx-4">
            <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="absolute top-6 right-6 text-slate-300 hover:text-red-500 transition"><i class="fas fa-times-circle text-xl"></i></button>
            <h2 class="text-xl font-black text-slate-800 mb-5 text-center">Update Unit</h2>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="id_produk" id="edit-id">
                <input type="text" name="nama_produk" id="edit-nama" required class="w-full px-4 py-3 rounded-xl bg-slate-50 font-bold text-xs border border-slate-100 outline-none focus:ring-2 focus:ring-[#5AAC41]">
                <select name="kategori" id="edit-kategori" required class="w-full px-4 py-3 rounded-xl bg-slate-50 font-bold text-xs border border-slate-100 outline-none focus:ring-2 focus:ring-[#5AAC41]">
                    <?php 
                    $get_kat_edit = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                    while($ke = mysqli_fetch_assoc($get_kat_edit)) {
                        echo "<option value='".htmlspecialchars($ke['nama_kategori'])."'>".htmlspecialchars($ke['nama_kategori'])."</option>";
                    }
                    ?>
                </select>
                <input type="number" name="harga" id="edit-harga" required class="w-full px-4 py-3 rounded-xl bg-slate-50 font-bold text-xs border border-slate-100 outline-none focus:ring-2 focus:ring-[#5AAC41]">
                <textarea name="deskripsi" id="edit-deskripsi" required class="w-full px-4 py-3 rounded-xl bg-slate-50 font-medium text-xs h-24 border border-slate-100 outline-none focus:ring-2 focus:ring-[#5AAC41]"></textarea>
                <p class="text-[10px] text-slate-400 font-bold italic">Biarkan kosong jika tidak ingin ganti foto gambar</p>
                <input type="file" name="gambar" class="text-xs">
                <button type="submit" name="update_produk" class="w-full bg-[#043978] text-white py-3.5 rounded-xl font-bold uppercase tracking-widest text-[10px] transition shadow-md">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <div id="modal-edit-layanan" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center hidden">
        <div class="bg-white w-full max-w-lg rounded-[2rem] p-8 relative mx-4">
            <button onclick="document.getElementById('modal-edit-layanan').classList.add('hidden')" class="absolute top-6 right-6 text-slate-300 hover:text-red-500 transition"><i class="fas fa-times-circle text-xl"></i></button>
            <h2 class="text-xl font-black text-slate-800 mb-5 text-center">Edit Berkas Layanan Klien</h2>
            <form action="" method="POST" class="space-y-4">
                <input type="hidden" name="id_layanan" id="edit-l-id">
                <input type="text" name="nama_layanan" id="edit-l-nama" required class="w-full px-4 py-3 rounded-xl bg-slate-50 font-bold text-xs border border-slate-100 outline-none">
                <input type="email" name="email_layanan" id="edit-l-email" required class="w-full px-4 py-3 rounded-xl bg-slate-50 font-bold text-xs border border-slate-100 outline-none">
                <input type="text" name="telepon_layanan" id="edit-l-telepon" required class="w-full px-4 py-3 rounded-xl bg-slate-50 font-bold text-xs border border-slate-100 outline-none">
                <select name="kategori_layanan" id="edit-l-kategori" required class="w-full px-4 py-3 rounded-xl bg-slate-50 font-bold text-xs border border-slate-100 outline-none">
                    <option value="Pembelian Unit Baru">Pembelian Unit Baru</option>
                    <option value="Rental Alat Survey">Rental Alat Survey</option>
                    <option value="Kalibrasi & Service">Kalibrasi & Service</option>
                    <option value="Konsultasi Proyek Topografi">Konsultasi Proyek Topografi</option>
                </select>
                <textarea name="pesan_layanan" id="edit-l-pesan" required class="w-full px-4 py-3 rounded-xl bg-slate-50 font-medium text-xs h-24 border border-slate-100 outline-none"></textarea>
                <button type="submit" name="update_layanan" class="w-full bg-[#043978] text-white py-3.5 rounded-xl font-bold uppercase tracking-widest text-[10px] transition shadow-md">Simpan Perubahan Berkas</button>
            </form>
        </div>
    </div>

    <div id="custom-alert" class="fixed inset-0 bg-slate-900/40 backdrop-blur-md z-[200] flex items-center justify-center hidden transition-all duration-300">
        <div class="bg-white max-w-sm w-full rounded-[2rem] p-8 text-center border border-slate-100 shadow-2xl transform scale-95 transition-all duration-300">
            <div class="w-16 h-16 bg-green-50 text-[#5AAC41] rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fas fa-check-circle"></i>
            </div>
            <h4 class="text-sm font-black text-slate-800 tracking-tight">Notifikasi Sistem</h4>
            <p id="alert-text" class="text-xs text-slate-400 font-semibold mt-2 mb-6 leading-relaxed"></p>
            <button onclick="tutupAlert()" class="w-full bg-[#043978] hover:bg-[#5AAC41] text-white py-3.5 rounded-xl font-bold uppercase tracking-widest text-[10px] transition shadow-lg">
                Selesai & Muat Ulang
            </button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    <script>
        const adminCanvas = document.getElementById('signature-pad-admin');
        const adminCtx = adminCanvas.getContext('2d');
        let adminIsDrawing = false;
        let modeExportTersimpan = ""; 

        function resizeAdminCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            adminCanvas.width = adminCanvas.offsetWidth * ratio;
            adminCanvas.height = adminCanvas.offsetHeight * ratio;
            adminCtx.scale(ratio, ratio);
            adminCtx.lineWidth = 2.5;
            adminCtx.lineCap = 'round';
            adminCtx.strokeStyle = '#043978';
        }

        adminCanvas.addEventListener('mousedown', (e) => { adminIsDrawing = true; putarGaris(e); });
        adminCanvas.addEventListener('mousemove', putarGaris);
        adminCanvas.addEventListener('mouseup', () => { adminIsDrawing = false; adminCtx.beginPath(); });
        adminCanvas.addEventListener('mouseout', () => { adminIsDrawing = false; adminCtx.beginPath(); });

        adminCanvas.addEventListener('touchstart', (e) => { e.preventDefault(); adminIsDrawing = true; putarGaris(e.touches[0]); }, { passive: false });
        adminCanvas.addEventListener('touchmove', (e) => { e.preventDefault(); putarGaris(e.touches[0]); }, { passive: false });
        adminCanvas.addEventListener('touchend', () => { adminCtx.beginPath(); });

        function putarGaris(e) {
            if (!adminIsDrawing) return;
            const rect = adminCanvas.getBoundingClientRect();
            const clientX = e.clientX || e.pageX;
            const clientY = e.clientY || e.pageY;
            const x = clientX - rect.left;
            const y = clientY - rect.top;

            adminCtx.lineTo(x, y);
            adminCtx.stroke();
            adminCtx.beginPath();
            adminCtx.moveTo(x, y);
        }

        function clearAdminCanvas() {
            adminCtx.clearRect(0, 0, adminCanvas.width, adminCanvas.height);
        }

        function dapatkanTtdBase64() {
            const blank = document.createElement('canvas');
            blank.width = adminCanvas.width;
            blank.height = adminCanvas.height;
            if (adminCanvas.toDataURL() === blank.toDataURL()) {
                return null;
            }
            return adminCanvas.toDataURL('image/png');
        }

        function bukaModalTtd(mode) {
            modeExportTersimpan = mode;
            document.getElementById('nama_pelapor_pdf').value = ""; // Reset form nama
            clearAdminCanvas();
            document.getElementById('modal-ttd-export').classList.remove('hidden');
            setTimeout(resizeAdminCanvas, 50); 
        }

        function tutupModalTtd() {
            document.getElementById('modal-ttd-export').classList.add('hidden');
        }

        $(document).ready(function() {
            // 1. DataTables Master Alat + PROPERTI KUSTOMISASI PDF INTEGRASI INPUT NAMA PELAPOR
            var tableAlat = $('#laporanTable').DataTable({
                "dom": '<"flex justify-between items-center mb-4"f>rt<"flex justify-between items-center mt-4"ip>',
                "buttons": [
                    {
                        extend: 'pdfHtml5',
                        title: '',
                        exportOptions: { columns: [0, 2, 3, 4] },
                        customize: function (doc) {
                            const ttdAdmin = dapatkanTtdBase64();
                            // Ambil nama dari value input teks modal popup secara dinamis
                            const namaInputan = document.getElementById('nama_pelapor_pdf').value.toUpperCase() || 'ADMINISTRATOR';

                            doc.pageMargins = [40, 40, 40, 40];
                            doc.content.splice(0, 0, 
                                { text: 'NUSA GEOSPATIAL SOLUTIONS', fontSize: 16, bold: true, color: '#043978', alignment: 'center', margin: [0, 0, 0, 4] },
                                { text: 'Laporan Rekapitulasi Inventaris Aset Alat & Instrumen Survei', fontSize: 10, fontStyle: 'italic', alignment: 'center', margin: [0, 0, 0, 20] },
                                { canvas: [{ type: 'line', x1: 0, y1: 0, x2: 515, y2: 0, lineWidth: 1.5, strokeColor: '#043978' }], margin: [0, 0, 0, 15] }
                            );

                            var objLayout = {};
                            objLayout['hLineWidth'] = function() { return .5; };
                            objLayout['vLineWidth'] = function() { return 0; };
                            objLayout['hLineColor'] = function() { return '#e2e8f0'; };
                            objLayout['paddingLeft'] = function() { return 10; };
                            objLayout['paddingRight'] = function() { return 10; };
                            doc.content[3].layout = objLayout;

                            // Menyisipkan Tanda Tangan Beserta Nama Pelapor Dinamis di Akhir PDF
                            doc.content.push(
                                { text: 'Sukabumi, ' + new Date().toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }), alignment: 'right', margin: [0, 30, 40, 5], fontSize: 10 },
                                { text: 'Petugas Penanggung Jawab,', alignment: 'right', margin: [0, 0, 40, 10], fontSize: 10, bold: true },
                                { image: ttdAdmin, width: 100, alignment: 'right', margin: [0, 0, 35, 5] },
                                { text: '( ' + namaInputan + ' )', alignment: 'right', margin: [0, 0, 20, 0], fontSize: 10, bold: true, color: '#043978' }
                            );
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        title: 'Laporan Inventaris Barang NGS Core',
                        exportOptions: { columns: [0, 2, 3, 4] }
                    }
                ],
                "language": { "search": "", "searchPlaceholder": "Cari data alat...", "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data" }
            });

            // LOGIKA EKSEKUSI KLIK DOWNLOAD DI DALAM POPUP MODAL TTD
            document.getElementById('btnConfirmExport').addEventListener('click', function() {
                const namaPelapor = document.getElementById('nama_pelapor_pdf').value.trim();
                const ttdData = dapatkanTtdBase64();

                // Validasi Kelengkapan Berkas
                if (namaPelapor === "") {
                    alert("⚠️ BERKAS DITOLAK: Harap isi Nama Lengkap Pelapor terlebih dahulu.");
                    return;
                }
                if (!ttdData) {
                    alert("⚠️ BERKAS DITOLAK: Mohon lengkapi goresan Tanda Tangan Anda.");
                    return;
                }

                tutupModalTtd();

                // Jalankan trigger tombol bawaan DataTables
                if (modeExportTersimpan === "pdf") {
                    tableAlat.button('.buttons-pdf').trigger();
                } else if (modeExportTersimpan === "excel") {
                    // Berhubung excel tidak bisa membaca blob kanvas langsung dari JS, kita tambahkan info via alert
                    tableAlat.button('.buttons-excel').trigger();
                }
            });

            $('#layananTable').DataTable({
                "language": { "search": "", "searchPlaceholder": "Cari data pemohon...", "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data pemohon" }
            });

            <?php if (!empty($msg_status)) : ?>
                bukaAlert("<?php echo $msg_status; ?>");
            <?php endif; ?>
        });

        function bukaAlert(pesan) {
            var audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2019/2019-84.wav');
            audio.volume = 0.6; 
            audio.play().catch(function(error) { console.log("Audio play diblokir oleh browser."); });

            document.getElementById('alert-text').innerText = pesan;
            const alertBox = document.getElementById('custom-alert');
            alertBox.classList.remove('hidden');
            setTimeout(() => {
                alertBox.querySelector('div').classList.remove('scale-95');
                alertBox.querySelector('div').classList.add('scale-100');
            }, 50);
        }

        function tutupAlert() { window.location.href = 'admin.php'; }

        function bukaEdit(data) {
            document.getElementById('modal-edit').classList.remove('hidden');
            document.getElementById('edit-id').value = data.id;
            document.getElementById('edit-nama').value = data.nama_produk;
            document.getElementById('edit-kategori').value = data.kategori;
            document.getElementById('edit-harga').value = data.harga;
            document.getElementById('edit-deskripsi').value = data.deskripsi;
        }

        function bukaEditLayanan(data) {
            document.getElementById('modal-edit-layanan').classList.remove('hidden');
            document.getElementById('edit-l-id').value = data.id;
            document.getElementById('edit-l-nama').value = data.nama;
            document.getElementById('edit-l-email').value = data.email;
            document.getElementById('edit-l-telepon').value = data.telepon; 
            document.getElementById('edit-l-kategori').value = data.kategori;
            document.getElementById('edit-l-pesan').value = data.pesan;
        }
    </script>
</body>
</html>