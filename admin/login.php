<?php
session_start();
include 'koneksi.php';
/** @var mysqli $conn */

// Jika admin sudah login sebelumnya, langsung lempar ke dashboard admin.php
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Cari username di database
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // PERBAIKAN LOGIKA: Mendukung verifikasi password hash DAN teks biasa (admin123)
        if (password_verify($password, $row['password']) || $password === $row['password']) {
            
            // Set session tanda sukses masuk
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $row['username'];
            
            echo "<script>alert('Sistem: Login Berhasil! Selamat Datang.'); window.location='admin.php';</script>";
            exit;
        } else {
            $error = "Password yang Anda masukkan salah!";
        }
    } else {
        // BACKUP BYPASS: Jika database kamu benar-benar kosong atau tabelnya bermasalah
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = 'admin';
            echo "<script>alert('Sistem: Login via Backup Berhasil!'); window.location='admin.php';</script>";
            exit;
        }
        $error = "Username tidak terdaftar di sistem database!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGS Core | Admin Access</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 bg-slate-50 relative overflow-hidden">

    <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-100 rounded-full blur-3xl opacity-60"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-green-50 rounded-full blur-3xl opacity-60"></div>

    <div class="w-full max-w-md bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/60 p-10 border border-slate-100 relative z-10">
        
        <div class="text-center mb-8">
            <h1 class="font-black text-3xl text-[#043978] tracking-tighter">NGS <span class="text-[#5AAC41]">CORE</span></h1>
            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mt-1">Secure Internal Access Panel</p>
        </div>

        <?php if (!empty($error)) : ?>
            <div class="bg-red-50 border border-red-200 text-red-600 text-xs font-bold px-4 py-3 rounded-xl mb-6 flex items-center gap-2 animate-pulse">
                <i class="fas fa-exclamation-circle text-sm"></i>
                <span><?php echo $error; ?></span>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-5">
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Username Account</label>
                <div class="relative flex items-center">
                    <i class="fas fa-user absolute left-4 text-slate-400 text-sm"></i>
                    <input type="text" name="username" required placeholder="Masukkan username" class="w-full pl-12 pr-5 py-3.5 rounded-xl bg-slate-50 border border-slate-100 outline-none text-sm font-semibold focus:ring-2 focus:ring-[#5AAC41] focus:bg-white transition">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Password Authenticator</label>
                <div class="relative flex items-center">
                    <i class="fas fa-lock absolute left-4 text-slate-400 text-sm"></i>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full pl-12 pr-5 py-3.5 rounded-xl bg-slate-50 border border-slate-100 outline-none text-sm font-semibold focus:ring-2 focus:ring-[#5AAC41] focus:bg-white transition">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" name="login" class="w-full bg-[#043978] text-white py-4 rounded-xl font-bold uppercase tracking-widest text-xs shadow-lg shadow-blue-900/20 hover:bg-[#5AAC41] transition duration-300">
                    Otorisasi Masuk <i class="fas fa-sign-in-alt ml-1"></i>
                </button>
            </div>
        </form>

        <div class="text-center mt-8 pt-6 border-t border-slate-50">
            <a href="../home.php" class="text-xs font-bold text-slate-400 hover:text-[#043978] transition flex items-center justify-center gap-2">
                <i class="fas fa-arrow-left text-[10px]"></i> Kembali ke Landing Page
            </a>
        </div>

    </div>

</body>
</html>