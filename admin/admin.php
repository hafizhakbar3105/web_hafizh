<?php
session_start();

// --- 1. PROTEKSI SESSION INTERNAL ACCESS ---
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';
/** @var mysqli $conn */ 

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
        echo "<script>alert('Sistem: Unit berhasil dihapus!'); window.location='admin.php';</script>";
    }
}

// --- 3. LOGIKA TAMBAH KATEGORI BARU ---
if (isset($_POST['tambah_kategori_baru'])) {
    $kategori_baru = mysqli_real_escape_string($conn, $_POST['nama_kategori_baru']);
    
    if (!empty($kategori_baru)) {
        $cek_kategori = mysqli_query($conn, "SELECT * FROM kategori WHERE nama_kategori = '$kategori_baru'");
        if (mysqli_num_rows($cek_kategori) == 0) {
            mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$kategori_baru')");
            echo "<script>alert('Sistem: Kategori baru berhasil ditambahkan!'); window.location='admin.php';</script>";
        } else {
            echo "<script>alert('Sistem: Kategori sudah terdaftar!');</script>";
        }
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
        echo "<script>alert('Sistem: Registrasi Berhasil!'); window.location='admin.php';</script>";
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
        echo "<script>alert('Sistem: Data diperbarui!'); window.location='admin.php';</script>";
    }
}

// --- 6. LOGIKA EDIT PERMINTAAN LAYANAN KLIEN ---
if (isset($_POST['update_layanan'])) {
    $id_layanan_edit = mysqli_real_escape_string($conn, $_POST['id_layanan']);
    $nama_l          = mysqli_real_escape_string($conn, $_POST['nama_layanan']);
    $email_l         = mysqli_real_escape_string($conn, $_POST['email_layanan']);
    $kategori_l      = mysqli_real_escape_string($conn, $_POST['kategori_layanan']);
    $pesan_l         = mysqli_real_escape_string($conn, $_POST['pesan_layanan']);

    $sql_l = "UPDATE layanan SET nama='$nama_l', email='$email_l', kategori='$kategori_l', pesan='$pesan_l' WHERE id='$id_layanan_edit'";
    if (mysqli_query($conn, $sql_l)) {
        echo "<script>alert('Sistem: Data permintaan layanan berhasil diperbarui!'); window.location='admin.php';</script>";
    }
}

// --- 7. LOGIKA HAPUS PERMINTAAN LAYANAN KLIEN ---
if (isset($_GET['hapus_layanan'])) {
    $id_layanan_hapus = mysqli_real_escape_string($conn, $_GET['hapus_layanan']);
    if (mysqli_query($conn, "DELETE FROM layanan WHERE id = '$id_layanan_hapus'")) {
        echo "<script>alert('Sistem: Data permintaan layanan berhasil dihapus!'); window.location='admin.php';</script>";
    }
}

// --- 8. LOGIKA LAPORAN INVENTORY ---
$data_inventory = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM produk"));
$total_unit = $data_inventory['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGS Core | Admin Dashboard</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .dataTables_wrapper { padding: 1rem; }
        table.dataTable thead th, table.dataTable thead td { border-bottom: 1px solid #f1f5f9; }
        table.dataTable.no-footer { border-bottom: none; }
        
        .dt-buttons .dt-button {
            background-color: #043978 !important;
            color: white !important;
            border: none !important;
            border-radius: 0.5rem !important;
            font-weight: 700 !important;
            font-size: 0.75rem !important;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
        }
        .dt-buttons .dt-button:hover { background-color: #5AAC41 !important; color: white !important;}
        
        .dataTables_filter input {
            background-color: #f1f5f9;
            border: none;
            border-radius: 0.5rem;
            padding: 0.4rem 0.8rem;
            outline: none;
            font-size: 0.875rem;
        }
    </style>
</head>
<body class="flex min-h-screen">

    <aside class="w-64 bg-[#043978] text-white flex flex-col fixed h-full z-50">
        <div class="p-8 border-b border-white/10">
            <h1 class="font-black text-xl tracking-tighter">NGS <span class="text-[#5AAC41]">CORE</span></h1>
            <p class="text-[10px] text-blue-300 uppercase font-bold tracking-widest mt-1">Admin Panel v2</p>
        </div>
        <nav class="flex-1 p-6 space-y-3">
            <a href="admin.php" class="flex items-center gap-4 py-3 px-4 rounded-xl bg-[#5AAC41] text-white font-bold shadow-lg">
                <i class="fas fa-th-large"></i> Dashboard Admin
            </a>
            <a href="../produk.php" class="flex items-center gap-4 py-3 px-4 text-white/50 hover:text-white transition font-semibold">
                <i class="fas fa-external-link-alt text-xs"></i> Lihat Katalog Publik
            </a>
            <a href="../home.php" class="flex items-center gap-4 py-3 px-4 text-white/50 hover:text-white transition font-semibold">
                <i class="fas fa-home text-xs"></i> Buka Landing Page
            </a>
        </nav>
        <div class="p-6 border-t border-white/10">
            <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar dari panel admin?')" class="flex items-center gap-4 py-3 px-4 rounded-xl bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white transition font-bold text-sm">
                <i class="fas fa-sign-out-alt"></i> LOGOUT SISTEM
            </a>
        </div>
    </aside>

    <main class="flex-1 ml-64 p-10">
        
        <header class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">DATABASE INSTRUMEN</h2>
                <p class="text-sm text-slate-400 font-medium">Sistem manajemen inventaris katalog website (Total: <?php echo $total_unit; ?> Unit).</p>
            </div>
            <div class="flex gap-4">
                <button onclick="document.getElementById('modal-kategori').classList.remove('hidden')" class="bg-white text-[#043978] border-2 border-[#043978] px-6 py-3 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-slate-50 transition shadow-md">
                    <i class="fas fa-folder-plus mr-2"></i> + Kategori Baru
                </button>
                <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')" class="bg-[#5AAC41] text-white px-6 py-3 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-[#4d9437] transition shadow-xl">
                    <i class="fas fa-plus mr-2"></i> + Tambah Alat Baru
                </button>
            </div>
        </header>

        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden relative mb-14">
            <div class="absolute top-0 left-0 w-2 h-full bg-[#5AAC41]"></div>
            
            <div class="p-6 overflow-x-auto">
                <table id="laporanTable" class="w-full text-left display" style="width:100%">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">No</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Gambar</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Produk</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kategori</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Harga</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php
                        $no = 1;
                        $res = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
                        while($row = mysqli_fetch_assoc($res)) {
                        ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-bold text-sm text-slate-400 text-center"><?php echo $no++; ?></td>
                            <td class="px-6 py-4">
                                <img src="../assets/img/<?php echo $row['gambar']; ?>" class="w-12 h-12 rounded-xl object-cover bg-slate-100 p-1" onerror="this.src='../assets/img/Topcon_GM100_c.png'">
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800 text-sm uppercase">
                                <?php echo htmlspecialchars($row['nama_produk']); ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[9px] font-black text-[#043978] bg-blue-50 px-3 py-1 rounded-lg uppercase"><?php echo htmlspecialchars($row['kategori']); ?></span>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-[#5AAC41]">
                                Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                            </td>
                            <td class="px-6 py-4 flex justify-center items-center gap-2">
                                <button onclick='bukaEdit(<?php echo json_encode($row); ?>)' class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 hover:bg-[#043978] hover:text-white transition flex items-center justify-center">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <a href="admin.php?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Yakin hapus unit ini?')" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 hover:bg-red-500 hover:text-white transition flex items-center justify-center">
                                    <i class="fas fa-trash text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-16 mb-5">
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">PERMINTAAN LAYANAN & KONTRAK</h2>
            <p class="text-sm text-slate-400 font-medium">Daftar masuk formulir pemohon beserta lampiran verifikasi tanda tangan digital.</p>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden relative mb-10">
            <div class="absolute top-0 left-0 w-2 h-full bg-[#043978]"></div>
            
            <div class="p-6 overflow-x-auto">
                <table id="layananTable" class="w-full text-left display" style="width:100%">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">No</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Pemohon</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Email</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kategori</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pesan</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Validasi TTD</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php
                        $no_l = 1;
                        $res_l = mysqli_query($conn, "SELECT * FROM layanan ORDER BY id DESC");
                        while($row_l = mysqli_fetch_assoc($res_l)) {
                        ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-bold text-sm text-slate-400 text-center"><?php echo $no_l++; ?></td>
                            <td class="px-6 py-4 font-bold text-slate-800 text-sm uppercase"><?php echo htmlspecialchars($row_l['nama']); ?></td>
                            <td class="px-6 py-4 text-xs font-semibold text-slate-600"><?php echo htmlspecialchars($row_l['email']); ?></td>
                            <td class="px-6 py-4">
                                <span class="text-[9px] font-black text-[#5AAC41] bg-green-50 px-3 py-1 rounded-lg uppercase"><?php echo htmlspecialchars($row_l['kategori']); ?></span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 max-w-xs truncate"><?php echo htmlspecialchars($row_l['pesan']); ?></td>
                            <td class="px-6 py-4 flex justify-center">
                                <img src="<?php echo $row_l['ttd_data']; ?>" class="h-12 w-auto bg-slate-50 border border-slate-100 rounded-lg p-1 object-contain" alt="Tanda Tangan Klien">
                            </td>
                            <td class="px-6 py-4 flex justify-center items-center gap-2">
                                <button onclick='bukaEditLayanan(<?php echo json_encode($row_l); ?>)' class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 hover:bg-[#043978] hover:text-white transition flex items-center justify-center">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <a href="admin.php?hapus_layanan=<?php echo $row_l['id']; ?>" onclick="return confirm('Yakin hapus data berkas permintaan layanan ini?')" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 hover:bg-red-500 hover:text-white transition flex items-center justify-center">
                                    <i class="fas fa-trash text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="modal-kategori" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center hidden">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] p-10 relative">
            <button onclick="document.getElementById('modal-kategori').classList.add('hidden')" class="absolute top-8 right-8 text-slate-300 hover:text-red-500"><i class="fas fa-times-circle text-2xl"></i></button>
            <h2 class="text-2xl font-black text-slate-800 mb-2">Kategori Baru</h2>
            <p class="text-xs text-slate-400 mb-6">Tambahkan klasifikasi instrumen baru ke database internal panel NGS Core.</p>
            <form action="" method="POST" class="space-y-4">
                <input type="text" name="nama_kategori_baru" placeholder="Contoh: GNSS RTK, Aksesoris Alat" required class="w-full px-5 py-4 rounded-xl bg-slate-50 border border-slate-100 outline-none font-semibold text-sm focus:ring-2 focus:ring-[#5AAC41] focus:bg-white transition">
                <button type="submit" name="tambah_kategori_baru" class="w-full bg-[#043978] text-white py-4 rounded-xl font-bold uppercase tracking-widest text-xs shadow-lg shadow-blue-900/20 hover:bg-[#5AAC41] transition">Simpan Kategori</button>
            </form>
        </div>
    </div>

    <div id="modal-tambah" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center hidden">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-10 relative">
            <button onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="absolute top-8 right-8 text-slate-300 hover:text-red-500"><i class="fas fa-times-circle text-2xl"></i></button>
            <h2 class="text-2xl font-black text-slate-800 mb-6">Registrasi Alat Baru</h2>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="text" name="nama_produk" placeholder="Nama Produk Instrumen" required class="w-full px-5 py-3 rounded-xl bg-slate-50 border-none outline-none font-semibold text-sm focus:ring-2 focus:ring-[#5AAC41]">
                <select name="kategori" required class="w-full px-5 py-3 rounded-xl bg-slate-50 border-none outline-none font-semibold text-sm focus:ring-2 focus:ring-[#5AAC41]">
                    <?php 
                    $get_kat = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                    while($k = mysqli_fetch_assoc($get_kat)) {
                        echo "<option value='".htmlspecialchars($k['nama_kategori'])."'>".htmlspecialchars($k['nama_kategori'])."</option>";
                    }
                    ?>
                </select>
                <input type="number" name="harga" placeholder="Harga Jual / Sewa (IDR)" required class="w-full px-5 py-3 rounded-xl bg-slate-50 border-none outline-none font-semibold text-sm focus:ring-2 focus:ring-[#5AAC41]">
                <textarea name="deskripsi" placeholder="Deskripsi Spesifikasi Teknis Alat..." required class="w-full px-5 py-3 rounded-xl bg-slate-50 border-none outline-none font-semibold text-sm h-24 focus:ring-2 focus:ring-[#5AAC41]"></textarea>
                <div class="bg-slate-50 p-4 rounded-xl border border-dashed border-slate-200">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Upload Foto Gambar</label>
                    <input type="file" name="gambar" required class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#043978] hover:file:bg-blue-100">
                </div>
                <button type="submit" name="tambah_produk" class="w-full bg-[#5AAC41] text-white py-4 rounded-xl font-bold uppercase tracking-widest text-xs shadow-lg shadow-green-900/20 hover:bg-[#4d9437] transition">Simpan Unit Alat</button>
            </form>
        </div>
    </div>

    <div id="modal-edit" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center hidden">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-10 relative">
            <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="absolute top-8 right-8 text-slate-300 hover:text-red-500"><i class="fas fa-times-circle text-2xl"></i></button>
            <h2 class="text-2xl font-black text-slate-800 mb-6 text-center">Update Unit</h2>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="id_produk" id="edit-id">
                <input type="text" name="nama_produk" id="edit-nama" required class="w-full px-5 py-3 rounded-xl bg-slate-50 font-bold text-sm">
                <select name="kategori" id="edit-kategori" required class="w-full px-5 py-3 rounded-xl bg-slate-50 font-bold text-sm">
                    <?php 
                    $get_kat_edit = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                    while($ke = mysqli_fetch_assoc($get_kat_edit)) {
                        echo "<option value='".htmlspecialchars($ke['nama_kategori'])."'>".htmlspecialchars($ke['nama_kategori'])."</option>";
                    }
                    ?>
                </select>
                <input type="number" name="harga" id="edit-harga" required class="w-full px-5 py-3 rounded-xl bg-slate-50 font-bold text-sm">
                <textarea name="deskripsi" id="edit-deskripsi" required class="w-full px-5 py-3 rounded-xl bg-slate-50 font-medium text-sm h-24"></textarea>
                <p class="text-[10px] text-slate-400 font-bold italic">Biarkan kosong jika tidak ingin ganti foto gambar</p>
                <input type="file" name="gambar" class="text-xs">
                <button type="submit" name="update_produk" class="w-full bg-[#043978] text-white py-4 rounded-xl font-bold uppercase tracking-widest text-xs shadow-lg">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <div id="modal-edit-layanan" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center hidden">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-10 relative">
            <button onclick="document.getElementById('modal-edit-layanan').classList.add('hidden')" class="absolute top-8 right-8 text-slate-300 hover:text-red-500"><i class="fas fa-times-circle text-2xl"></i></button>
            <h2 class="text-2xl font-black text-slate-800 mb-6 text-center">Edit Berkas Layanan Klien</h2>
            <form action="" method="POST" class="space-y-4">
                <input type="hidden" name="id_layanan" id="edit-l-id">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Pemohon</label>
                    <input type="text" name="nama_layanan" id="edit-l-nama" required class="w-full px-5 py-3 rounded-xl bg-slate-50 font-bold text-sm focus:outline-none focus:ring-2 focus:ring-[#043978]">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Email Perusahaan</label>
                    <input type="email" name="email_layanan" id="edit-l-email" required class="w-full px-5 py-3 rounded-xl bg-slate-50 font-bold text-sm focus:outline-none focus:ring-2 focus:ring-[#043978]">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kategori Permintaan</label>
                    <select name="kategori_layanan" id="edit-l-kategori" required class="w-full px-5 py-3 rounded-xl bg-slate-50 font-bold text-sm focus:outline-none focus:ring-2 focus:ring-[#043978]">
                        <option value="Pembelian Unit Baru">Pembelian Unit Baru</option>
                        <option value="Rental Alat Survey">Rental Alat Survey</option>
                        <option value="Kalibrasi & Service">Kalibrasi & Service</option>
                        <option value="Konsultasi Proyek Topografi">Konsultasi Proyek Topografi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pesan Tambahan</label>
                    <textarea name="pesan_layanan" id="edit-l-pesan" required class="w-full px-5 py-3 rounded-xl bg-slate-50 font-medium text-sm h-24 focus:outline-none focus:ring-2 focus:ring-[#043978]"></textarea>
                </div>
                <button type="submit" name="update_layanan" class="w-full bg-[#043978] text-white py-4 rounded-xl font-bold uppercase tracking-widest text-xs shadow-lg">Simpan Perubahan Berkas</button>
            </form>
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
        $(document).ready(function() {
            // 1. Inisialisasi DataTables Inventaris Alat
            $('#laporanTable').DataTable({
                "dom": '<"flex justify-between items-center mb-4"<"flex gap-2"B><"flex items-center"f>>rt<"flex justify-between items-center mt-4"ip>',
                "buttons": [
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf mr-1"></i> Export Data PDF',
                        title: 'Laporan Rekapitulasi Data Base - NGS Core',
                        exportOptions: { columns: [0, 2, 3, 4] }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel mr-1"></i> Export Excel',
                        exportOptions: { columns: [0, 2, 3, 4] }
                    }
                ],
                "language": {
                    "search": "",
                    "searchPlaceholder": "Cari data alat...",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
                }
            });

            // 2. Inisialisasi DataTables Layanan Pelanggan & TTD Kontrak
            $('#layananTable').DataTable({
                "language": {
                    "search": "",
                    "searchPlaceholder": "Cari data pemohon...",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data pemohon"
                }
            });
        });

        // Handler form modal edit alat
        function bukaEdit(data) {
            document.getElementById('modal-edit').classList.remove('hidden');
            document.getElementById('edit-id').value = data.id;
            document.getElementById('edit-nama').value = data.nama_produk;
            document.getElementById('edit-kategori').value = data.kategori;
            document.getElementById('edit-harga').value = data.harga;
            document.getElementById('edit-deskripsi').value = data.deskripsi;
        }

        // Handler form modal edit layanan
        function bukaEditLayanan(data) {
            document.getElementById('modal-edit-layanan').classList.remove('hidden');
            document.getElementById('edit-l-id').value = data.id;
            document.getElementById('edit-l-nama').value = data.nama;
            document.getElementById('edit-l-email').value = data.email;
            document.getElementById('edit-l-kategori').value = data.kategori;
            document.getElementById('edit-l-pesan').value = data.pesan;
        }
    </script>
</body>
</html>