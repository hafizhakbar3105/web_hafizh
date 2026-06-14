<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nusa Geospatial Solutions | Solusi Presisi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* Pengaturan Khusus Video Background agar Responsif & Mengunci Element */
        .video-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        .video-container video {
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-white text-slate-800">

    <nav class="flex items-center justify-between px-6 md:px-12 py-5 bg-white sticky top-0 z-50 shadow-sm transition-all duration-300" id="main-nav">
        <div class="flex items-center gap-2">
            <img src="assets/img/NGS.png" alt="Logo" class="h-10 md:h-16 w-auto">
        </div>
        <div class="hidden md:flex gap-8 font-semibold text-[#043978]">
            <a href="home.php" class="hover:text-[#5AAC41] transition-colors text-[#5AAC41]">Home</a>
            <a href="produk.php" class="hover:text-[#5AAC41] transition-colors">Produk</a>
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

    <div id="mobile-menu" class="fixed inset-0 bg-[#043978] z-[60] flex flex-col items-center justify-center gap-8 text-white text-2xl font-bold translate-x-full transition-transform duration-300">
        <button id="close-menu" class="absolute top-8 right-8 text-3xl">&times;</button>
        <a href="home.php" class="mobile-link">Home</a>
        <a href="produk.php" class="mobile-link">Produk</a>
        <a href="#rental" class="mobile-link">Rental</a>
        <a href="layanan.php" class="mobile-link">Tentang Kami</a>
    </div>

    <section id="home" class="h-[650px] flex items-center px-6 md:px-12 text-white overflow-hidden relative">
        
        <div class="video-container">
            <video autoplay muted loop playsinline poster="assets/img/tambang.png">
                <source src="assets/video/GNSS.mp4" type="video/mp4">
                Browser Anda tidak mendukung pemutar video.
            </video>
        </div>

        <div class="absolute inset-0 bg-[#043978]/85 mix-blend-multiply z-10"></div>

        <div class="container mx-auto relative z-20">
            <div class="max-w-4xl animate-fade-in-up">
                <div class="w-14 h-1.5 bg-[#E7D532] mb-8 rounded-full"></div>
                
                <h1 class="text-4xl md:text-7xl font-extrabold leading-[1.1] mb-6 tracking-tight">
                    Solusi Presisi untuk<br>
                    <span class="text-white">Masa Depan Geospatial</span>
                </h1>
                
                <p class="text-base md:text-xl mb-10 opacity-90 max-w-2xl leading-relaxed">
                    Penyedia layanan dan alat survey terintegrasi. Kami menghadirkan teknologi GNSS, 
                    Total Station, dan Drone terbaik untuk mendukung akurasi proyek infrastruktur Anda.
                </p>
                
                <div class="flex flex-wrap gap-5">
                    <a href="produk.php" class="bg-[#5AAC41] hover:bg-[#4d9437] px-10 py-4 rounded-lg font-bold flex items-center gap-3 transition-all transform hover:scale-105 shadow-xl shadow-green-900/20 text-white">
                        Jelajahi Produk <i class="fas fa-arrow-right text-sm"></i>
                    </a>
                    <button onclick="openVideoModal()" class="border-2 border-white/60 hover:border-white hover:bg-white hover:text-[#043978] px-10 py-4 rounded-lg font-bold transition-all transform hover:scale-105 flex items-center gap-2">
                        <i class="fas fa-play-circle text-lg"></i> Tonton Profil Video
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section id="rental" class="py-20 px-6 md:px-12 grid grid-cols-1 md:grid-cols-3 gap-12 max-w-7xl mx-auto">
        <div class="border-l-4 border-[#043978] pl-6 group reveal">
            <div class="w-12 h-12 bg-[#043978] text-white rounded-lg flex items-center justify-center mb-6 transition-all group-hover:bg-[#5AAC41] group-hover:rotate-12 shadow-lg">
                <i class="fas fa-box text-xl"></i>
            </div>
            <h3 class="text-2xl font-bold mb-3 text-[#043978]">Rental</h3>
            <p class="text-slate-500 leading-relaxed">Dukung efisiensi proyek Anda dengan armada alat survey terbaru yang selalu terkalibrasi harian.</p>
        </div>
        <div id="kalibrasi" class="border-l-4 border-[#043978] pl-6 group reveal">
            <div class="w-12 h-12 bg-[#043978] text-white rounded-lg flex items-center justify-center mb-6 transition-all group-hover:bg-[#5AAC41] group-hover:rotate-12 shadow-lg">
                <i class="fas fa-cog text-xl"></i>
            </div>
            <h3 class="text-2xl font-bold mb-3 text-[#043978]">Calibration & Service</h3>
            <p class="text-slate-500 leading-relaxed">Menjamin akurasi maksimal melalui laboratorium kalibrasi dengan teknisi ahli bersertifikat.</p>
        </div>
        <div class="border-l-4 border-[#043978] pl-6 group reveal">
            <div class="w-12 h-12 bg-[#043978] text-white rounded-lg flex items-center justify-center mb-6 transition-all group-hover:bg-[#5AAC41] group-hover:rotate-12 shadow-lg">
                <i class="fas fa-shopping-cart text-xl"></i>
            </div>
            <h3 class="text-2xl font-bold mb-3 text-[#043978]">Sales & Solutions</h3>
            <p class="text-slate-500 leading-relaxed">Investasi cerdas untuk teknologi jangka panjang dengan perangkat survei original dan bergaransi.</p>
        </div>
    </section>

    <section id="tentang" class="py-24 px-6 md:px-12 grid grid-cols-1 md:grid-cols-2 gap-16 items-center max-w-7xl mx-auto">
        <div class="reveal">
            <div class="w-12 h-1.5 bg-[#5AAC41] mb-8 rounded-full"></div>
            <h2 class="text-4xl font-bold text-[#043978] mb-8">Corporate Vision</h2>
            <blockquote class="text-2xl font-semibold text-[#195994] border-l-4 border-[#5AAC41] pl-8 leading-relaxed italic">
                "Nusa Geospatial Solutions lahir dari visi untuk memajukan industri pemetaan di Indonesia melalui teknologi presisi."
            </blockquote>
            <div class="grid grid-cols-3 gap-4 md:gap-8 mt-12 text-center">
                <div><h5 class="text-3xl font-extrabold text-[#043978]">15+</h5><p class="text-[10px] text-slate-500 uppercase font-bold tracking-widest">Thn Pengalaman</p></div>
                <div><h5 class="text-3xl font-extrabold text-[#043978]">500+</h5><p class="text-[10px] text-slate-500 uppercase font-bold tracking-widest">Proyek Selesai</p></div>
                <div><h5 class="text-3xl font-extrabold text-[#043978]">100%</h5><p class="text-[10px] text-slate-500 uppercase font-bold tracking-widest">Kepuasan Klien</p></div>
            </div>
        </div>
        <div class="flex justify-center relative reveal">
            <div class="absolute -top-4 -right-4 w-full h-full border-2 border-[#E7D532] rounded-2xl -z-10"></div>
            <img src="assets/img/hafizh.png" class="rounded-2xl shadow-2xl w-full max-w-sm object-cover aspect-square" alt="Team">
        </div>
    </section>

    <section id="produk-unggulan" class="py-24 px-6 md:px-12 max-w-7xl mx-auto relative">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 relative z-10">
            <div>
                <div class="w-12 h-1 bg-[#043978] mb-3"></div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-[#043978] tracking-tight uppercase">
                    Produk <span class="text-[#195994] font-light italic">Unggulan</span>
                </h2>
                <p class="text-slate-400 mt-2 text-sm">Teknologi pemetaan presisi dari brand kelas dunia</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="produk.php" class="text-xs font-bold text-[#043978] border-b-2 border-[#043978] pb-1 hover:text-[#5AAC41] hover:border-[#5AAC41] transition-all flex items-center gap-2 uppercase tracking-wider">
                    Lihat Semua Produk <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 p-6 shadow-xl shadow-slate-200/50 flex flex-col justify-between transition-all hover:scale-[1.03] hover:shadow-2xl duration-300">
                <div>
                    <div class="bg-slate-50 rounded-2xl p-4 flex justify-center items-center mb-6 relative overflow-hidden aspect-square">
                        <span class="absolute top-3 left-3 bg-black text-white text-[8px] font-bold px-2.5 py-1 rounded-full tracking-wider uppercase">
                            IMU Tilt
                        </span>
                        <img src="assets/img/gnss-smart-full-antennas-chcnav-i93.png" alt="CHCNAV I73" class="max-h-48 object-contain" onerror="this.src='assets/img/Topcon_GM100_c.png'">
                    </div>
                    <div class="space-y-2">
                        <span class="text-[9px] font-bold text-[#5AAC41] tracking-widest uppercase">CHCNAV</span>
                        <h3 class="text-lg font-extrabold text-[#043978] leading-snug min-h-[56px]">
                            CHCNAV I73 / I73+ POCKET GNSS RTK
                        </h3>
                        <p class="text-xs text-slate-400 leading-relaxed text-justify line-clamp-6">
                            GNSS RTK ultra-kompak dilengkapi teknologi IMU-RTK terintegrasi untuk kompensasi kemiringan otomatis. Memungkinkan pengukuran titik sulit dengan akurasi tinggi tanpa perlu levelling manual, meningkatkan produktivitas lapangan hingga 30%.
                        </p>
                    </div>
                </div>
                <div class="pt-6">
                    <a href="produk.php" class="w-full bg-black hover:bg-slate-900 text-white text-[11px] font-bold py-3.5 px-4 rounded-xl flex items-center justify-center gap-2 transition uppercase tracking-wider">
                        Detail Produk <i class="fas fa-arrow-circle-right text-xs"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-slate-100 p-6 shadow-xl shadow-slate-200/50 flex flex-col justify-between transition-all hover:scale-[1.03] hover:shadow-2xl duration-300">
                <div>
                    <div class="bg-slate-50 rounded-2xl p-4 flex justify-center items-center mb-6 relative overflow-hidden aspect-square">
                        <span class="absolute top-3 left-3 bg-[#043978] text-white text-[8px] font-bold px-2.5 py-1 rounded-full tracking-wider uppercase">
                            Reflectorless
                        </span>
                        <img src="assets/img/Topcon_GM100_c.png" alt="Topcon GM100" class="max-h-48 object-contain">
                    </div>
                    <div class="space-y-2">
                        <span class="text-[9px] font-bold text-[#5AAC41] tracking-widest uppercase">Topcon</span>
                        <h3 class="text-lg font-extrabold text-[#043978] leading-snug min-h-[56px]">
                            TOTAL STATION TOPCON GM-100 SERIES
                        </h3>
                        <p class="text-xs text-slate-400 leading-relaxed text-justify line-clamp-6">
                            Stasiun total kelas profesional yang menawarkan akurasi tingkat tinggi dengan jangkauan EDM reflectorless mumpuni hingga 500 meter. Dilengkapi sistem keamanan TSshield canggih dan ketahanan air bersertifikasi IP66 untuk medan berat.
                        </p>
                    </div>
                </div>
                <div class="pt-6">
                    <a href="produk.php" class="w-full bg-black hover:bg-slate-900 text-white text-[11px] font-bold py-3.5 px-4 rounded-xl flex items-center justify-center gap-2 transition uppercase tracking-wider">
                        Detail Produk <i class="fas fa-arrow-circle-right text-xs"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-slate-100 p-6 shadow-xl shadow-slate-200/50 flex flex-col justify-between transition-all hover:scale-[1.03] hover:shadow-2xl duration-300">
                <div>
                    <div class="bg-slate-50 rounded-2xl p-4 flex justify-center items-center mb-6 relative overflow-hidden aspect-square">
                        <span class="absolute top-3 left-3 bg-[#5AAC41] text-white text-[8px] font-bold px-2.5 py-1 rounded-full tracking-wider uppercase">
                            Aerial Survey
                        </span>
                        <img src="assets/img/tambang.png" alt="Drone Survey" class="max-h-48 w-full object-cover rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <span class="text-[9px] font-bold text-[#5AAC41] tracking-widest uppercase">DJI Enterprise</span>
                        <h3 class="text-lg font-extrabold text-[#043978] leading-snug min-h-[56px]">
                            DJI MATRICE RTK SERIES DRONE MAPPING
                        </h3>
                        <p class="text-xs text-slate-400 leading-relaxed text-justify line-clamp-6">
                            Solusi fotogrametri udara terintegrasi untuk akurasi peta ortofoto skala besar. Mendukung pemetaan topografi, kalkulasi volume tambang terbuka (*stockpile*), dan inspeksi koridor infrastruktur secara cepat dan efisien.
                        </p>
                    </div>
                </div>
                <div class="pt-6">
                    <a href="produk.php" class="w-full bg-black hover:bg-slate-900 text-white text-[11px] font-bold py-3.5 px-4 rounded-xl flex items-center justify-center gap-2 transition uppercase tracking-wider">
                        Detail Produk <i class="fas fa-arrow-circle-right text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="kontak" class="mx-6 md:mx-12 my-20 bg-[#043978] rounded-[2rem] md:rounded-[3rem] p-10 md:p-20 text-center text-white relative overflow-hidden shadow-2xl reveal">
        <div class="relative z-10">
            <h2 class="text-3xl md:text-5xl font-extrabold mb-6">Siap Memulai Proyek Anda?</h2>
            <p class="text-lg opacity-80 mb-10 max-w-xl mx-auto">Konsultasikan kebutuhan geospatial Anda sekarang dan dapatkan solusi terbaik dari tim ahli kami.</p>
            <div class="flex flex-wrap justify-center gap-5">
                <a href="layanan.php" class="bg-[#5AAC41] text-white px-10 py-4 rounded-xl font-bold flex items-center gap-2 hover:bg-[#4d9437] transition shadow-lg transform hover:-translate-y-1">
                    <i class="fas fa-phone-alt"></i> Hubungi Kami
                </a>
                <button class="border border-white/30 px-10 py-4 rounded-xl font-bold hover:bg-white hover:text-[#043978] transition transform hover:-translate-y-1">
                    Email Kami
                </button>
            </div>
        </div>
        <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>
    </section>

    <div id="videoModal" class="fixed inset-0 bg-black/80 z-[100] hidden items-center justify-center p-4 transition-opacity duration-300 opacity-0">
        <div class="bg-slate-900 rounded-2xl max-w-3xl w-full p-4 relative shadow-2xl border border-white/10">
            <button onclick="closeVideoModal()" class="absolute -top-12 right-0 text-white text-3xl hover:text-red-500 transition">&times; Tutup</button>
            <div class="aspect-video w-full rounded-lg overflow-hidden bg-black">
                <video id="promoVideo" controls class="w-full h-full">
                    <source src="assets/video/GNSS.mp4" type="video/mp4">
                    Browser Anda tidak mendukung pemutar video.
                </video>
            </div>
            <audio id="clickSound" src="audio/click.mp3" preload="auto"></auto>
        </div>
    </div>

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
        const modal = document.getElementById('videoModal');
        const video = document.getElementById('promoVideo');
        const audioBtn = document.getElementById('clickSound');

        function openVideoModal() {
            if(audioBtn) {
                audioBtn.play().catch(err => console.log("Audio klik diblokir browser sebelum interaksi"));
            }
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
            }, 20);
            
            if(video) {
                video.currentTime = 0;
                video.play().catch(error => {
                    console.log("Autoplay diblokir browser, menggunakan mode muted...");
                    video.muted = true;
                    video.play();
                });
            }
        }

        function closeVideoModal() {
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            
            if(video) {
                video.pause();
            }
            
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
    <script src="assets/script.js"></script>
</body>
</html>