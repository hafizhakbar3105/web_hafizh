<?php
// 1. Hubungkan ke file koneksi. Karena file ini di dalam folder pages, kita keluar tingkat dulu (../)
include 'admin/koneksi.php';

$notif_sukses = false;

// 2. Logika backend untuk menangkap data kiriman form permintaan layanan
if (isset($_POST['kirim_layanan'])) {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $email    = mysqli_real_escape_string($conn, $_POST['email_perusahaan']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori_permintaan']);
    $pesan    = mysqli_real_escape_string($conn, $_POST['pesan_tambahan']);
    $ttd_data = mysqli_real_escape_string($conn, $_POST['ttd_data']);

    // Insert ke tabel layanan yang barusan kita rancang
    $query = "INSERT INTO layanan (nama, email, kategori, pesan, ttd_data) VALUES ('$nama', '$email', '$kategori', '$pesan', '$ttd_data')";
    
    if (mysqli_query($conn, $query)) {
        $notif_sukses = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hubungi Kami | Nusa Geospatial Solutions</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Styling khusus untuk area Canvas TTD */
        #signature-pad {
            border: 2px dashed #cbd5e1;
            border-radius: 1rem;
            cursor: crosshair;
            touch-action: none; /* Penting agar bisa ditandatangani via layar HP tanpa scroll */
            background-color: #f8fafc;
        }
    </style>
</head>
<body class="bg-white text-slate-800">

    <?php if ($notif_sukses): ?>
        <script>
            alert("Terima kasih! Pesan dan Tanda Tangan Anda berhasil disimpan ke sistem kami.");
            window.location='layanan.php';
        </script>
    <?php endif; ?>

    <nav class="flex items-center justify-between px-6 md:px-12 py-5 bg-white sticky top-0 z-50 shadow-sm transition-all duration-300" id="main-nav">
        <div class="flex items-center gap-2">
            <img src="../assets/img/NGS.png" alt="Logo" class="h-10 md:h-16 w-auto">
        </div>
        <div class="hidden md:flex gap-8 font-semibold text-[#043978]">
            <a href="home.php" class="hover:text-[#5AAC41] transition-colors">Home</a>
            <a href="produk.php" class="hover:text-[#5AAC41] transition-colors">Produk</a>
            <a href="../index.php#rental" class="hover:text-[#5AAC41] transition-colors">Rental</a>
            <a href="../index.php#kalibrasi" class="hover:text-[#5AAC41] transition-colors">Kalibrasi</a>
            <a href="layanan.php" class="hover:text-[#5AAC41] transition-colors text-[#5AAC41]">Tentang Kami</a>
        </div>
        <div class="flex items-center gap-4">
            <a href="layanan.php" class="bg-[#043978] text-white px-6 py-2.5 rounded-lg font-semibold flex items-center gap-2 hover:bg-[#195994] transition shadow-md">
                <i class="fas fa-phone-alt text-xs"></i> Hubungi Kami
            </a>
            <button id="mobile-menu-btn" class="md:hidden text-[#043978] text-2xl focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <header class="bg-[#043978] text-white py-16 px-6 md:px-12 text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="relative z-10 max-w-4xl mx-auto">
            <span class="text-[#E7D532] text-xs font-bold uppercase tracking-widest bg-white/10 px-3 py-1.5 rounded-full">Layanan Pelanggan</span>
            <h1 class="text-3xl md:text-5xl font-extrabold mt-4 mb-4 tracking-tight">Hubungi Tim Kami</h1>
            <p class="text-sm md:text-base text-slate-200 max-w-2xl mx-auto opacity-90">
                Konsultasikan kebutuhan teknis, layanan kalibrasi, atau penyewaan alat survey Anda. Silakan isi form persetujuan di bawah ini.
            </p>
        </div>
    </header>

    <main class="flex-grow max-w-5xl w-full mx-auto px-6 md:px-12 py-16">
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 p-8 md:p-12 border border-slate-100">
            
            <div class="mb-10 text-center">
                <h2 class="text-2xl font-black text-[#043978] mb-2">Form Permintaan Layanan</h2>
                <p class="text-sm text-slate-400">Gunakan form ini untuk mengirimkan detail permintaan Anda beserta tanda tangan digital pengesahan.</p>
            </div>

            <form id="contactForm" action="" method="POST" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap / Instansi</label>
                        <input type="text" name="nama_lengkap" required class="w-full bg-slate-50 border border-slate-200 px-5 py-4 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#5AAC41] focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email Perusahaan</label>
                        <input type="email" name="email_perusahaan" required class="w-full bg-slate-50 border border-slate-200 px-5 py-4 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#5AAC41] focus:border-transparent transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori Permintaan</label>
                    <select name="kategori_permintaan" required class="w-full bg-slate-50 border border-slate-200 px-5 py-4 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#5AAC41] text-slate-600 transition">
                        <option value="Pembelian Unit Baru">Pembelian Unit Baru</option>
                        <option value="Rental Alat Survey">Rental Alat Survey</option>
                        <option value="Kalibrasi & Service">Kalibrasi & Service</option>
                        <option value="Konsultasi Proyek Topografi">Konsultasi Proyek Topografi</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pesan Tambahan</label>
                    <textarea name="pesan_tambahan" rows="4" required class="w-full bg-slate-50 border border-slate-200 px-5 py-4 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#5AAC41] focus:border-transparent transition"></textarea>
                </div>

                <div class="mt-8 pt-8 border-t border-slate-100">
                    <label class="block text-xs font-bold text-[#043978] uppercase tracking-wider mb-2 flex items-center gap-2">
                        <i class="fas fa-pen-nib"></i> Tanda Tangan Digital Pemohon
                    </label>
                    <p class="text-[10px] text-slate-400 mb-4 italic">Silakan goreskan tanda tangan Anda di dalam kotak area putih di bawah ini sebagai bentuk validasi permintaan.</p>
                    
                    <div class="relative w-full">
                        <canvas id="signature-pad" class="w-full h-48 md:h-64"></canvas>
                        
                        <button type="button" onclick="clearCanvas()" class="absolute top-4 right-4 bg-red-100 text-red-500 hover:bg-red-500 hover:text-white text-xs font-bold px-3 py-1.5 rounded-lg transition shadow-sm">
                            <i class="fas fa-eraser"></i> Bersihkan TTD
                        </button>
                    </div>
                    
                    <input type="hidden" name="ttd_data" id="ttd_data">
                </div>

                <div class="pt-6 text-center">
                    <button type="submit" name="kirim_layanan" onclick="return submitForm(event)" class="bg-[#5AAC41] hover:bg-[#4d9437] text-white px-12 py-4 rounded-xl font-bold uppercase tracking-widest text-sm shadow-lg shadow-green-900/20 transition transform hover:-translate-y-1 w-full md:w-auto">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Permintaan Layanan
                    </button>
                </div>
            </form>

        </div>
    </main>

    <footer class="bg-[#0f172a] text-white pt-24 pb-12 px-6 md:px-12 relative">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-16 mb-20">
            <div class="col-span-1">
                <img src="../assets/img/Brand.png" alt="Logo" class="h-14 w-auto mb-8">
                <p class="text-slate-400 text-sm leading-relaxed mb-8">Solusi geospatial terpercaya untuk masa depan infrastruktur Indonesia dengan teknologi terkini.</p>
                <div class="flex">
                    <input type="email" placeholder="Email Anda" class="bg-white/5 border border-white/10 px-4 py-3 rounded-l-lg w-full text-sm focus:outline-none focus:border-[#5AAC41]">
                    <button class="bg-[#5AAC41] px-5 rounded-r-lg hover:bg-[#4d9437]"><i class="fas fa-paper-plane"></i></button>
                </div>
            </div>
            <div>
                <h5 class="font-bold mb-8 uppercase tracking-widest text-sm text-white">Produk</h5>
                <ul class="text-slate-400 text-sm space-y-4">
                    <li><a href="#" class="hover:text-[#5AAC41] transition">Total Station</a></li>
                    <li><a href="#" class="hover:text-[#5AAC41] transition">GNSS/GPS</a></li>
                    <li><a href="#" class="hover:text-[#5AAC41] transition">Drone Survey</a></li>
                </ul>
            </div>
            <div>
                <h5 class="font-bold mb-8 uppercase tracking-widest text-sm text-white">Layanan</h5>
                <div class="text-slate-400 text-sm space-y-6">
                    <div class="flex gap-4"><i class="fas fa-map-marker-alt text-[#5AAC41] mt-1"></i><p>Sukabumi, Indonesia</p></div>
                    <div class="flex gap-4"><i class="fas fa-phone-alt text-[#5AAC41] mt-1"></i><p>+62 812-3456-7890</p></div>
                </div>
            </div>
            <div>
                <h5 class="font-bold mb-4 uppercase tracking-widest text-sm text-white">Sosial Media</h5>
                <div class="flex gap-4 mb-8">
                    <a href="https://www.facebook.com/share/18n6Cx5Heb/" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-[#043978] transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/muhammad_hafizh3105?igsh=MXV5bW5rdWo1dHluMw==" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-[#5AAC41] transition"><i class="fab fa-instagram"></i></a>
                </div>
                
                <h5 class="font-bold mb-4 uppercase tracking-widest text-sm text-[#5AAC41]">Internal System</h5>
                <a href="admin/login.php" class="inline-flex items-center gap-2 text-xs bg-white/5 hover:bg-red-600 border border-white/10 hover:border-red-500 px-4 py-2.5 rounded-lg text-slate-300 hover:text-white transition-all font-semibold tracking-wider">
                    <i class="fas fa-lock text-xs"></i> PANEL LOGIN ADMIN
                </a>
            </div>
        </div>
        <div class="text-center pt-8 border-t border-white/5 text-[11px] text-slate-500 tracking-[0.2em]">
            © 2026 NUSA GEOSPATIAL SOLUTIONS.
        </div>
    </footer>

    <script>
        const canvas = document.getElementById('signature-pad');
        const ctx = canvas.getContext('2d');
        let isDrawing = false;

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            ctx.scale(ratio, ratio);
            
            ctx.lineWidth = 3;
            ctx.lineCap = 'round';
            ctx.strokeStyle = '#043978'; 
        }

        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);

        canvas.addEventListener('touchstart', handleTouchStart, { passive: false });
        canvas.addEventListener('touchmove', handleTouchMove, { passive: false });
        canvas.addEventListener('touchend', stopDrawing);

        function startDrawing(e) {
            isDrawing = true;
            draw(e);
        }

        function draw(e) {
            if (!isDrawing) return;
            const rect = canvas.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            ctx.lineTo(x, y);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(x, y);
        }

        function stopDrawing() {
            isDrawing = false;
            ctx.beginPath(); 
        }

        function handleTouchStart(e) {
            e.preventDefault(); 
            const touch = e.touches[0];
            const mouseEvent = new MouseEvent("mousedown", {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            canvas.dispatchEvent(mouseEvent);
        }

        function handleTouchMove(e) {
            e.preventDefault(); 
            const touch = e.touches[0];
            const mouseEvent = new MouseEvent("mousemove", {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            canvas.dispatchEvent(mouseEvent);
        }

        function clearCanvas() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }

        // PERBAIKAN VALIDASI: Menyiapkan string gambar kanvas sebelum form disubmit secara native
        function submitForm(event) {
            const blank = document.createElement('canvas');
            blank.width = canvas.width;
            blank.height = canvas.height;
            
            if (canvas.toDataURL() === blank.toDataURL()) {
                alert("Mohon bubuhkan Tanda Tangan Digital Anda sebelum mengirim form.");
                event.preventDefault(); // Cegah pengiriman data ke server jika TTD kosong
                return false;
            }

            const dataURL = canvas.toDataURL('image/png');
            document.getElementById('ttd_data').value = dataURL;
            return true;
        }
    </script>
</body>
</html>