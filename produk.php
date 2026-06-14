<?php
// Hubungkan ke file koneksi. Karena koneksi.php ada di folder admin, langsung panggil admin/koneksi.php
include 'admin/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk | Nusa Geospatial Solutions</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

    <nav class="flex items-center justify-between px-6 md:px-12 py-5 bg-white sticky top-0 z-50 shadow-sm transition-all duration-300" id="main-nav">
        <div class="flex items-center gap-2">
            <img src="assets/img/NGS.png" alt="Logo" class="h-10 md:h-16 w-auto">
        </div>
        <div class="hidden md:flex gap-8 font-semibold text-[#043978]">
            <a href="home.php" class="hover:text-[#5AAC41] transition-colors">Home</a>
            <a href="produk.php" class="hover:text-[#5AAC41] transition-colors text-[#5AAC41]">Produk</a>
            <a href="layanan.php" class="hover:text-[#5AAC41] transition-colors">Tentang Kami</a>
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
            <span class="text-[#E7D532] text-xs font-bold uppercase tracking-widest bg-white/10 px-3 py-1.5 rounded-full">Katalog Alat</span>
            <h1 class="text-3xl md:text-5xl font-extrabold mt-4 mb-4 tracking-tight">Katalog Produk Presisi</h1>
            <p class="text-sm md:text-base text-slate-200 max-w-2xl mx-auto opacity-90">
                Menyediakan instrumentasi survei dan pemetaan berkualitas tinggi dengan garansi resmi dan performa optimal.
            </p>
        </div>
    </header>

    <section class="max-w-7xl mx-auto px-6 md:px-12 mt-10">
        <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 flex justify-end">
            <div class="relative w-full md:w-96 flex items-center">
                <i class="fas fa-search absolute left-4 text-slate-400 text-sm"></i>
                <input type="text" id="searchInput" onkeyup="cariProduk()" placeholder="Cari tipe atau nama instrumen..." class="w-full pl-12 pr-5 py-3.5 rounded-xl bg-slate-50 border border-slate-100 outline-none text-sm font-semibold focus:ring-2 focus:ring-[#5AAC41] focus:bg-white transition">
            </div>
        </div>
    </section>

    <main class="max-w-7xl w-full mx-auto px-6 md:px-12 py-10 flex flex-col md:flex-row gap-8">
        
        <aside class="w-full md:w-64 shrink-0">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 sticky top-32">
                <h3 class="text-sm font-black text-[#043978] uppercase tracking-wider mb-5 flex items-center gap-2">
                    <i class="fas fa-th-list text-xs"></i> Kategori Alat
                </h3>
                
                <div class="flex flex-col gap-2">
                    <button onclick="filterKategori('semua', this)" class="btn-kategori w-full text-left bg-[#043978] text-white text-xs font-bold px-5 py-3.5 rounded-xl transition shadow-md flex justify-between items-center group">
                        <span>Semua Alat</span>
                        <i class="fas fa-chevron-right text-[10px] opacity-70 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                    
                    <?php
                    // Ambil daftar kategori dinamis dari database
                    $get_filters = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                    while($f = mysqli_fetch_assoc($get_filters)) {
                    ?>
                        <button onclick="filterKategori('<?php echo htmlspecialchars($f['nama_kategori']); ?>', this)" class="btn-kategori w-full text-left bg-slate-50 hover:bg-slate-100 text-slate-600 text-xs font-bold px-5 py-3.5 rounded-xl transition flex justify-between items-center group">
                            <span><?php echo htmlspecialchars($f['nama_kategori']); ?></span>
                            <i class="fas fa-chevron-right text-[10px] opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
                        </button>
                    <?php } ?>
                </div>
            </div>
        </aside>

        <section class="flex-1">
            <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <?php
                $query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
                
                if (mysqli_num_rows($query) > 0) {
                    while ($row = mysqli_fetch_assoc($query)) {
                        $text_wa = urlencode("Halo Nusa Geospatial Solutions, saya tertarik dengan produk *" . $row['nama_produk'] . "*.");
                ?>
                <div class="product-card bg-white rounded-[2.5rem] border border-slate-100 p-5 shadow-xl shadow-slate-200/50 flex flex-col justify-between transition-all hover:scale-[1.03] hover:shadow-2xl duration-300"
                     data-nama="<?php echo strtolower(htmlspecialchars($row['nama_produk'])); ?>"
                     data-kategori="<?php echo htmlspecialchars($row['kategori']); ?>">
                    <div>
                        <div class="bg-slate-50 rounded-3xl p-4 flex justify-center items-center mb-5 relative overflow-hidden aspect-square">
                            <span class="absolute top-3 left-3 bg-[#043978] text-white text-[8px] font-black px-2.5 py-1.5 rounded-full tracking-wider uppercase z-10">
                                <?php echo htmlspecialchars($row['kategori']); ?>
                            </span>
                            <img src="assets/img/<?php echo $row['gambar']; ?>" alt="<?php echo htmlspecialchars($row['nama_produk']); ?>" class="max-h-44 w-auto object-contain transition-transform duration-300 hover:scale-105" onerror="this.src='assets/img/Topcon_GM100_c.png'">
                        </div>
                        <div class="space-y-1.5">
                            <span class="text-[8px] font-extrabold text-[#5AAC41] tracking-widest uppercase">PREMIUM EQUIPMENT</span>
                            <h3 class="product-title text-base font-black text-[#043978] leading-snug tracking-tight min-h-[48px] uppercase line-clamp-2">
                                <?php echo htmlspecialchars($row['nama_produk']); ?>
                            </h3>
                            <p class="text-[11px] text-slate-400 leading-relaxed text-justify line-clamp-4">
                                <?php echo htmlspecialchars($row['deskripsi']); ?>
                            </p>
                            <p class="text-sm font-black text-[#5AAC41] pt-1">
                                Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                            </p>
                        </div>
                    </div>
                    <div class="pt-5 border-t border-slate-50 mt-4">
                        <a href="https://wa.me/6281234567890?text=<?php echo $text_wa; ?>" target="_blank" class="w-full bg-[#5AAC41] hover:bg-[#4d9437] text-white text-[11px] font-bold py-3.5 px-4 rounded-xl flex items-center justify-center gap-2 transition uppercase tracking-wider shadow-lg">
                            <i class="fab fa-whatsapp text-sm"></i> Hubungi via WhatsApp
                        </a>
                    </div>
                </div>
                <?php 
                    } 
                } else {
                    echo '<p id="emptyMessage" class="col-span-full text-center text-slate-400 font-medium py-10">Belum ada produk yang terdaftar di katalog.</p>';
                }
                ?>

                <p id="noResult" class="col-span-full text-center text-slate-400 font-medium py-20 hidden">Produk instrumen tidak ditemukan.</p>

            </div>
        </section>

    </main>

    <footer class="bg-[#0f172a] text-white pt-24 pb-12 px-6 md:px-12 relative">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-16 mb-20">
            <div class="col-span-1">
                <img src="assets/img/Brand.png" alt="Logo" class="h-14 w-auto mb-8">
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
        let kategoriAktif = "semua";

        function filterKategori(kategori, el) {
            kategoriAktif = kategori;
            
            // Atur ulang gaya semua tombol kategori menjadi tidak aktif
            document.querySelectorAll('.btn-kategori').forEach(btn => {
                btn.classList.remove('bg-[#043978]', 'text-white', 'shadow-md');
                btn.classList.add('bg-slate-50', 'text-slate-600');
                // Sembunyikan ikon panah kanan pada tombol yang tidak aktif
                const icon = btn.querySelector('i');
                if (icon) icon.classList.add('opacity-0');
            });

            // Berikan gaya aktif pada tombol yang dipilih
            el.classList.remove('bg-slate-50', 'text-slate-600');
            el.classList.add('bg-[#043978]', 'text-white', 'shadow-md');
            const activeIcon = el.querySelector('i');
            if (activeIcon) activeIcon.classList.remove('opacity-0');

            eksekusiFilterDanSearch();
        }

        function cariProduk() {
            eksekusiFilterDanSearch();
        }

        function eksekusiFilterDanSearch() {
            const kataKunci = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.product-card');
            let dataDitemukan = 0;

            cards.forEach(card => {
                const namaProduk = card.getAttribute('data-nama');
                const kategoriProduk = card.getAttribute('data-kategori');

                const cocokKategori = (kategoriAktif === "semua" || kategoriProduk === kategoriAktif);
                const cocokKeyword = namaProduk.includes(kataKunci);

                if (cocokKategori && cocokKeyword) {
                    card.classList.remove('hidden');
                    dataDitemukan++;
                } else {
                    card.classList.add('hidden');
                }
            });

            const pesanKosong = document.getElementById('noResult');
            if (dataDitemukan === 0 && cards.length > 0) {
                pesanKosong.classList.remove('hidden');
            } else {
                pesanKosong.classList.add('hidden');
            }
        }
    </script>
</body>
</html>