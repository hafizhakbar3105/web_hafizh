<?php
include 'koneksi.php';

// --- 1. LOGIKA HAPUS PRODUK ---
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['hapus']);
    
    // Ambil nama gambar untuk dihapus dari folder
    $ambil_gambar = mysqli_query($conn, "SELECT gambar FROM produk WHERE id = '$id_hapus'");
    $data_gambar = mysqli_fetch_assoc($ambil_gambar);
    $nama_file = $data_gambar['gambar'] ?? '';

    if (mysqli_query($conn, "DELETE FROM produk WHERE id = '$id_hapus'")) {
        if ($nama_file && file_exists("img/" . $nama_file)) {
            unlink("img/" . $nama_file);
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
        move_uploaded_file($_FILES['gambar']['tmp_name'], "img/" . $gambar);
        echo "<script>alert('Sistem: Registrasi Berhasil!'); window.location='admin.php';</script>";
    }
}

// --- 3. LOGIKA UPDATE/EDIT PRODUK ---
if (isset($_POST['update_produk'])) {
    $id_edit   = $_POST['id_produk'];
    $nama      = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $kategori  = mysqli_real_escape_string($conn, $_POST['kategori']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga     = $_POST['harga'];

    if ($_FILES['gambar']['name'] != "") {
        $gambar = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "img/" . $gambar);
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
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .glass-header { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(0,0,0,0.05); }
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
            <a href="index.html" class="flex items-center gap-4 py-3 px-4 text-white/50 hover:text-white transition font-semibold">
                <i class="fas fa-external-link-alt text-xs"></i> Buka Website
            </a>
        </nav>
    </aside>

    <main class="flex-1 ml-64 p-10">
        <header class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Manajemen Katalog</h2>
                <p class="text-sm text-slate-400 font-medium">Muhammad Hafizh Akbar S - Sukabumi</p>
            </div>
            <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')" class="bg-[#043978] text-white px-8 py-3 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-[#5AAC41] transition shadow-xl">
                <i class="fas fa-plus mr-2"></i> Tambah Alat
            </button>
        </header>

        <div class="grid grid-cols-12 gap-8">
            <div class="col-span-12 lg:col-span-3">
                <div class="bg-[#043978] p-8 rounded-[2.5rem] text-white shadow-2xl relative overflow-hidden">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-50 mb-2">Laporan Inventory</p>
                    <h4 class="text-4xl font-black text-[#5AAC41] tracking-tighter"><?php echo $total_unit; ?> <span class="text-sm font-normal text-white/60">Unit</span></h4>
                    <i class="fas fa-box-open absolute -right-4 -bottom-4 text-8xl opacity-10"></i>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-9">
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Detail Alat</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kategori</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php
                            $res = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
                            while($row = mysqli_fetch_assoc($res)) {
                            ?>
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-5">
                                        <img src="img/<?php echo $row['gambar']; ?>" class="w-14 h-14 rounded-2xl object-cover bg-slate-100 p-1">
                                        <div>
                                            <p class="font-bold text-slate-800 text-sm uppercase"><?php echo $row['nama_produk']; ?></p>
                                            <p class="text-[10px] font-bold text-[#5AAC41]">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-[9px] font-black text-[#043978] bg-blue-50 px-3 py-1 rounded-lg uppercase"><?php echo $row['kategori']; ?></span>
                                </td>
                                <td class="px-8 py-6 flex justify-center gap-2">
                                    <button onclick='bukaEdit(<?php echo json_encode($row); ?>)' class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 hover:bg-[#043978] hover:text-white transition flex items-center justify-center">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <a href="admin.php?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Hapus unit ini?')" class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 hover:bg-red-500 hover:text-white transition flex items-center justify-center">
                                        <i class="fas fa-trash text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
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

    <script>
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