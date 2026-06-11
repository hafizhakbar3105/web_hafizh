<?php
include 'koneksi.php';
/** @var mysqli $conn */ // Menghilangkan fake-error 'Undefined variable $conn' di VS Code

// --- 1. LOGIKA HAPUS PRODUK ---
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['hapus']);
    
    $ambil_gambar = mysqli_query($conn, "SELECT gambar FROM produk WHERE id = '$id_hapus'");
    $data_gambar = mysqli_fetch_assoc($ambil_gambar);
    $nama_file = $data_gambar['gambar'] ?? '';

    if (mysqli_query($conn, "DELETE FROM produk WHERE id = '$id_hapus'")) {
        // PERBAIKAN: Jalur hapus file diarahkan keluar folder admin ke assets/img/
        if ($nama_file && file_exists("../assets/img/" . $nama_file)) {
            unlink("../assets/img/" . $nama_file);
        }
        echo "<script>alert('Sistem: Unit berhasil dihapus!'); window.location='admin.php';</script>";
    }
}

// --- 2. LOGIKA TAMBAH PRODUK ---
if (isset($_POST['tambah_produk'])) {
    $nama      = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $kategori  = mysqli_real_escape_string($conn, $_POST['kategori']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga     = mysqli_real_escape_string($conn, $_POST['harga']);
    $gambar    = $_FILES['gambar']['name'];
    
    if (mysqli_query($conn, "INSERT INTO produk (nama_produk, kategori, deskripsi, harga, gambar) VALUES ('$nama', '$kategori', '$deskripsi', '$harga', '$gambar')")) {
        // PERBAIKAN: Gambar dipindahkan ke folder WEB_HAFIZH/assets/img/
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../assets/img/" . $gambar);
        echo "<script>alert('Sistem: Registrasi Berhasil!'); window.location='admin.php';</script>";
    }
}

// --- 3. LOGIKA UPDATE/EDIT PRODUK ---
if (isset($_POST['update_produk'])) {
    $id_edit   = mysqli_real_escape_string($conn, $_POST['id_produk']);
    $nama      = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $kategori  = mysqli_real_escape_string($conn, $_POST['kategori']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga     = mysqli_real_escape_string($conn, $_POST['harga']); 

    if ($_FILES['gambar']['name'] != "") {
        $gambar = $_FILES['gambar']['name'];
        // PERBAIKAN: Gambar update dipindahkan ke folder WEB_HAFIZH/assets/img/
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../assets/img/" . $gambar);
        $sql = "UPDATE produk SET nama_produk='$nama', kategori='$kategori', deskripsi='$deskripsi', harga='$harga', gambar='$gambar' WHERE id='$id_edit'";
    } else {
        $sql = "UPDATE produk SET nama_produk='$nama', kategori='$kategori', deskripsi='$deskripsi', harga='$harga' WHERE id='$id_edit'";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Sistem: Data diperbarui!'); window.location='admin.php';</script>";
    }
}

// --- 4. LOGIKA LAPORAN INVENTORY ---
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
        
        /* Custom Styling for DataTables to Match Tailwind Theme */
        .dataTables_wrapper { padding: 1rem; }
        table.dataTable thead th, table.dataTable thead td { border-bottom: 1px solid #f1f5f9; }
        table.dataTable.no-footer { border-bottom: none; }
        
        /* Tombol Export Custom Styling */
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
        
        /* Search Box Custom Styling */
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
                <i class="fas fa-th-large"></i> Katalog Alat
            </a>
            <a href="../produk.html" class="flex items-center gap-4 py-3 px-4 text-white/50 hover:text-white transition font-semibold">
                <i class="fas fa-external-link-alt text-xs"></i> Lihat Katalog Publik
            </a>
            <a href="../index.html" class="flex items-center gap-4 py-3 px-4 text-white/50 hover:text-white transition font-semibold">
                <i class="fas fa-home text-xs"></i> Buka Landing Page
            </a>
        </nav>
    </aside>

    <main class="flex-1 ml-64 p-10">
        <header class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Manajemen Katalog</h2>
                <p class="text-sm text-slate-400 font-medium">Laporan Rekapitulasi Data Base (Total: <?php echo $total_unit; ?> Unit)</p>
            </div>
            <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')" class="bg-[#043978] text-white px-8 py-3 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-[#5AAC41] transition shadow-xl">
                <i class="fas fa-plus mr-2"></i> Tambah Alat
            </button>
        </header>

        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden relative">
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
    </main>

    <div id="modal-tambah" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center hidden">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-10 relative">
            <button onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="absolute top-8 right-8 text-slate-300 hover:text-red-500"><i class="fas fa-times-circle text-2xl"></i></button>
            <h2 class="text-2xl font-black text-slate-800 mb-6">Registrasi Unit</h2>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="text" name="nama_produk" placeholder="Nama Produk" required class="w-full px-5 py-3 rounded-xl bg-slate-50 border-none outline-none font-semibold text-sm focus:ring-2 focus:ring-[#5AAC41]">
                <select name="kategori" class="w-full px-5 py-3 rounded-xl bg-slate-50 border-none outline-none font-semibold text-sm">
                    <option value="Total Station">Total Station</option>
                    <option value="GNSS RTK System">GNSS RTK System</option>
                    <option value="Drone LiDAR System">Drone LiDAR System</option>
                </select>
                <input type="number" name="harga" placeholder="Harga (IDR)" required class="w-full px-5 py-3 rounded-xl bg-slate-50 border-none outline-none font-semibold text-sm">
                <textarea name="deskripsi" placeholder="Deskripsi" required class="w-full px-5 py-3 rounded-xl bg-slate-50 border-none outline-none font-semibold text-sm h-24"></textarea>
                <input type="file" name="gambar" required class="text-xs">
                <button type="submit" name="tambah_produk" class="w-full bg-[#043978] text-white py-4 rounded-xl font-bold uppercase tracking-widest text-xs shadow-lg shadow-blue-900/20">Simpan Unit</button>
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
                <select name="kategori" id="edit-kategori" class="w-full px-5 py-3 rounded-xl bg-slate-50 font-bold text-sm">
                    <option value="Total Station">Total Station</option>
                    <option value="GNSS RTK System">GNSS RTK System</option>
                    <option value="Drone LiDAR System">Drone LiDAR System</option>
                </select>
                <input type="number" name="harga" id="edit-harga" required class="w-full px-5 py-3 rounded-xl bg-slate-50 font-bold text-sm">
                <textarea name="deskripsi" id="edit-deskripsi" required class="w-full px-5 py-3 rounded-xl bg-slate-50 font-medium text-sm h-24"></textarea>
                <p class="text-[10px] text-slate-400 font-bold italic">Biarkan kosong jika tidak ingin ganti foto</p>
                <input type="file" name="gambar" class="text-xs">
                <button type="submit" name="update_produk" class="w-full bg-[#5AAC41] text-white py-4 rounded-xl font-bold uppercase tracking-widest text-xs shadow-lg">Simpan Perubahan</button>
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
            $('#laporanTable').DataTable({
                "dom": '<"flex justify-between items-center mb-4"<"flex gap-2"B><"flex items-center"f>>rt<"flex justify-between items-center mt-4"ip>',
                "buttons": [
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf mr-1"></i> Export Data PDF',
                        title: 'Laporan Rekapitulasi Data Base - NGS Core',
                        exportOptions: {
                            columns: [0, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel mr-1"></i> Export Excel',
                        exportOptions: {
                            columns: [0, 2, 3, 4]
                        }
                    }
                ],
                "language": {
                    "search": "",
                    "searchPlaceholder": "Cari data alat...",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
                }
            });
        });

        function bukaEdit(data) {
            document.getElementById('modal-edit').classList.remove('hidden');
            document.getElementById('edit-id').value = data.id;
            document.getElementById('edit-nama').value = data.nama_produk;
            document.getElementById('edit-kategori').value = data.kategori;
            document.getElementById('edit-harga').value = data.harga;
            document.getElementById('edit-deskripsi').value = data.deskripsi;
        }
    </script>

</body>
</html>