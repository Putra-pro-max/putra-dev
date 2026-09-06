{{-- Mengimpor facade Storage Laravel untuk mengakses file yang diupload user --}}
@use('Illuminate\Support\Facades\Storage')

<!DOCTYPE html>
{{-- scroll-smooth: perilaku scroll halus saat klik anchor link seperti #about, #skills, dll --}}
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    {{-- Agar tampilan responsif di semua ukuran layar termasuk mobile --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    {{-- Favicon: ikon yang muncul di tab browser --}}
    <link rel="icon" type="image/jpg" href="{{ asset('images/logo_public.png') }}">
    <title>PutraDev.</title>

    {{-- Directive Vite: compile dan inject CSS dari resources/css/app.css (berisi Tailwind) --}}
    @vite('resources/css/app.css')

    {{-- Preconnect: browser terhubung lebih awal ke server Google Fonts agar font lebih cepat dimuat --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    {{-- Memuat font Plus Jakarta Sans dengan berbagai ketebalan (300 sampai 800) --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet" />

    {{-- Token CSRF: token keamanan Laravel untuk melindungi request POST dan AJAX dari serangan --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* Reset: semua elemen menggunakan box-sizing border-box
           agar padding tidak memperbesar lebar elemen secara tidak terduga */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0d0d0d; /* Warna latar hitam gelap */
            color: #f5f5f5;            /* Warna teks putih terang */
            overflow-x: hidden;        /* Sembunyikan scrollbar horizontal */
        }

        /* ─────────────────────────────────────────────
           CAHAYA LATAR (GLOW BACKGROUND)
           Tiga lingkaran cahaya hijau transparan sebagai dekorasi latar halaman.
           position fixed: tetap di layar meskipun halaman di-scroll.
           pointer-events none: tidak menghalangi klik pengguna.
           Ketiga glow ini digerakkan mengikuti posisi mouse via JavaScript (efek parallax).
        ───────────────────────────────────────────── */
        .bg-glow-tl {
            position: fixed; top: -220px; left: -220px; /* Posisi pojok kiri atas, sengaja di luar layar */
            width: 620px; height: 620px; border-radius: 50%;
            /* radial-gradient: cahaya hijau memudar dari tengah ke tepi luar */
            background: radial-gradient(circle, rgba(16,185,129,0.12) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
            /* Transisi halus saat elemen digerakkan mengikuti mouse */
            transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .bg-glow-br {
            position: fixed; bottom: -250px; right: -250px; /* Posisi pojok kanan bawah */
            width: 720px; height: 720px; border-radius: 50%;
            background: radial-gradient(circle, rgba(52,211,153,0.08) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
            transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .bg-glow-center {
            position: fixed; top: 40%; left: 55%; /* Posisi tengah-kanan layar */
            width: 500px; height: 500px; border-radius: 50%;
            transform: translate(-50%, -50%); /* Geser agar titik tengahnya tepat di koordinat tersebut */
            background: radial-gradient(circle, rgba(16,185,129,0.05) 0%, transparent 65%);
            pointer-events: none; z-index: 0;
            transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        /* ─────────────────────────────────────────────
           NAVBAR KACA (GLASSMORPHISM)
           Efek kaca: latar semi-transparan dengan blur di belakangnya.
           backdrop-filter blur: mengaburkan konten halaman yang ada di balik navbar.
           Perlu prefix -webkit untuk mendukung browser Safari.
        ───────────────────────────────────────────── */
        .glass-nav {
            background: rgba(255,255,255,0.04);   /* Putih sangat transparan sebagai latar */
            backdrop-filter: blur(24px);           /* Blur konten di belakang navbar */
            -webkit-backdrop-filter: blur(24px);   /* Versi Safari */
            border: 1px solid rgba(255,255,255,0.07);
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }

        /* Tautan navigasi dengan efek garis bawah hijau yang muncul saat hover */
        .nav-link {
            position: relative; color: rgba(255,255,255,0.55);
            transition: color 0.3s ease; font-size: 0.8125rem; letter-spacing: 0.01em;
        }
        /* Pseudo-element after sebagai garis bawah dekoratif, awalnya lebar 0 (tidak terlihat) */
        .nav-link::after {
            content: ''; position: absolute; bottom: -4px; left: 50%;
            transform: translateX(-50%); width: 0; height: 1.5px;
            background: linear-gradient(90deg, #10b981, #34d399);
            border-radius: 2px; transition: width 0.35s ease;
        }
        .nav-link:hover { color: #fff; }
        .nav-link:hover::after { width: 100%; } /* Lebar jadi 100% saat hover, garis muncul dari tengah */

        /* ─────────────────────────────────────────────
           TOMBOL
        ───────────────────────────────────────────── */
        /* Tombol utama: latar gradasi hijau dengan efek cahaya (glow) di sekitarnya */
        .btn-cta {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            color: #0d0d0d; /* Teks hitam agar kontras dengan latar hijau */
            box-shadow: 0 0 20px rgba(16,185,129,0.35), 0 0 50px rgba(16,185,129,0.12);
            transition: box-shadow 0.3s ease, transform 0.25s ease;
        }
        .btn-cta:hover {
            box-shadow: 0 0 30px rgba(16,185,129,0.55), 0 0 70px rgba(16,185,129,0.2); /* Glow makin kuat saat hover */
            transform: translateY(-2px); /* Tombol naik sedikit saat hover */
        }

        /* Tombol outline: hanya border hijau tanpa latar penuh */
        .btn-outline {
            border: 1px solid rgba(16,185,129,0.35);
            color: #34d399;
            background: rgba(16,185,129,0.05);
            transition: all 0.3s ease;
        }
        .btn-outline:hover {
            border-color: rgba(16,185,129,0.6);
            background: rgba(16,185,129,0.1);
            box-shadow: 0 0 20px rgba(16,185,129,0.15);
            transform: translateY(-2px);
        }

        /* ─────────────────────────────────────────────
           UTILITAS TEKS
        ───────────────────────────────────────────── */
        /* Teks dengan warna gradasi hijau, dipakai di logo dan beberapa heading */
        .gradient-text {
            background: linear-gradient(135deg, #34d399 0%, #10b981 50%, #6ee7b7 100%);
            -webkit-background-clip: text; /* Potong background sesuai bentuk teks (Safari) */
            -webkit-text-fill-color: transparent; /* Buat teks transparan agar gradient terlihat */
            background-clip: text;
        }
        /* Teks gradasi merah, dipakai di kata "nesia" pada tulisan Indonesia */
        .danger-text {
            background: linear-gradient(135deg, #d33434 0%, #b91010 50%, #e76e6e 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        /* Teks abu-abu redup untuk konten sekunder */
        .txt-muted { color: rgba(255,255,255,0.45); }

        /* ─────────────────────────────────────────────
           ANIMASI
        ───────────────────────────────────────────── */
        /* Titik hijau berkedip pada badge "Available for work" */
        .dot-pulse {
            width: 9px; height: 9px; background: #34d399; border-radius: 50%;
            box-shadow: 0 0 6px #34d399, 0 0 16px rgba(52,211,153,0.45);
            animation: pulse-dot 2.4s ease-in-out infinite; /* Berulang terus-menerus */
        }
        @keyframes pulse-dot {
            0%, 100% { box-shadow: 0 0 6px #34d399, 0 0 16px rgba(52,211,153,0.35); transform: scale(1); }
            50%       { box-shadow: 0 0 12px #34d399, 0 0 30px rgba(52,211,153,0.6); transform: scale(1.15); } /* Membesar di tengah siklus */
        }

        /* Elemen muncul dari bawah saat halaman pertama kali dimuat */
        .fade-up {
            opacity: 0; transform: translateY(30px); /* Awalnya transparan dan bergeser 30px ke bawah */
            animation: fadeUp 0.95s cubic-bezier(0.16, 1, 0.3, 1) forwards; /* forwards: tahan posisi akhir */
        }
        /* Kelas delay untuk membuat elemen muncul bergantian (efek stagger) */
        .delay-1 { animation-delay: 0.12s; }
        .delay-2 { animation-delay: 0.26s; }
        .delay-3 { animation-delay: 0.42s; }
        .delay-4 { animation-delay: 0.58s; }
        .delay-5 { animation-delay: 0.76s; }
        .delay-6 { animation-delay: 0.92s; }
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } } /* Kondisi akhir: tampak dan posisi normal */

        /* Animasi kedip untuk kursor typewriter di hero section */
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0; } /* Menghilang di tengah siklus, lalu muncul lagi */
        }
        .animate-blink { animation: blink 1s step-end infinite; } /* step-end: langsung kedip tanpa transisi halus */

        /* ─────────────────────────────────────────────
           REVEAL SAAT SCROLL
           Elemen tersembunyi di awal, lalu muncul saat masuk area pandang (viewport).
           Class .visible ditambahkan oleh JavaScript menggunakan IntersectionObserver.
        ───────────────────────────────────────────── */
        .reveal {
            opacity: 0; transform: translateY(40px); /* Awalnya tidak terlihat dan bergeser ke bawah */
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal.visible { opacity: 1; transform: translateY(0); } /* Muncul saat class .visible ditambahkan */

        /* Variasi reveal: muncul dari sisi kiri */
        .reveal-left {
            opacity: 0; transform: translateX(-40px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-left.visible { opacity: 1; transform: translateX(0); }

        /* Variasi reveal: muncul dari sisi kanan */
        .reveal-right {
            opacity: 0; transform: translateX(40px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-right.visible { opacity: 1; transform: translateX(0); }

        /* Variasi reveal: muncul dengan efek zoom masuk kecil */
        .reveal-scale {
            opacity: 0; transform: scale(0.92); /* Sedikit mengecil di awal */
            transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-scale.visible { opacity: 1; transform: scale(1); }

        /* ─────────────────────────────────────────────
           GARIS EYEBROW SECTION
           Teks kapital kecil di atas judul section (misal "About me", "What I Use").
           Garis dekoratif di kiri dan kanannya melebar saat section masuk viewport.
        ───────────────────────────────────────────── */
        .eyebrow-line {
            display: inline-flex; align-items: center; gap: 0.75rem;
            font-size: 0.68rem; font-weight: 600;
            letter-spacing: 0.25em; text-transform: uppercase;
            color: rgba(255,255,255,0.3); margin-bottom: 0.75rem;
            overflow: hidden;
        }
        /* Garis dekoratif tipis di kiri dan kanan teks, awalnya pendek */
        .eyebrow-line::before, .eyebrow-line::after {
            content: '';
            width: 24px; height: 1px;
            background: rgba(255,255,255,0.12);
            transition: width 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.3s;
        }
        /* Saat elemen induk mendapat class .visible, garis melebar dua kali lipat */
        .visible .eyebrow-line::before,
        .visible .eyebrow-line::after { width: 48px; }

        /* Foto di section about bergerak naik-turun perlahan seperti melayang */
        @keyframes float-y {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-12px); } /* Naik 12 piksel di tengah siklus */
        }
        .float-anim { animation: float-y 5s ease-in-out infinite; }
        /* Matikan animasi untuk pengguna yang sensitif terhadap gerakan berlebihan */
        @media (prefers-reduced-motion: reduce) { .float-anim { animation: none; } }

        /* Kartu proyek yang bisa miring mengikuti posisi mouse (efek 3D tilt, digerakkan JS) */
        .tilt-card {
            transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94),
                        border-color 0.5s ease, box-shadow 0.5s ease;
            transform-style: preserve-3d; /* Aktifkan ruang 3D agar rotasi terlihat nyata */
            perspective: 1000px;          /* Jarak perspektif kamera 3D */
        }

        /* ─────────────────────────────────────────────
           STAGGER ANAK ELEMEN
           Setiap anak elemen diberi delay berbeda agar muncul secara bergantian.
        ───────────────────────────────────────────── */
        .stagger-children > *:nth-child(1) { transition-delay: 0ms; }
        .stagger-children > *:nth-child(2) { transition-delay: 80ms; }
        .stagger-children > *:nth-child(3) { transition-delay: 160ms; }
        .stagger-children > *:nth-child(4) { transition-delay: 240ms; }
        .stagger-children > *:nth-child(5) { transition-delay: 320ms; }
        .stagger-children > *:nth-child(6) { transition-delay: 400ms; }

        /* Delay stagger khusus untuk tiga item kontak (email, lokasi, WhatsApp) */
        .contact-stagger > *:nth-child(1) { transition-delay: 0ms; }
        .contact-stagger > *:nth-child(2) { transition-delay: 100ms; }
        .contact-stagger > *:nth-child(3) { transition-delay: 200ms; }

        /* ─────────────────────────────────────────────
           GARIS BAWAH SHIMMER
           Garis di bawah judul section yang muncul dari kiri ke kanan,
           lalu bergerak seperti sorotan cahaya yang melintas (efek shimmer).
        ───────────────────────────────────────────── */
        .shimmer-underline { position: relative; display: inline-block; }
        .shimmer-underline::after {
            content: '';
            position: absolute; bottom: -6px; left: 0;
            width: 100%; height: 2px;
            background: linear-gradient(90deg, transparent, #10b981, #34d399, #10b981, transparent);
            background-size: 200% 100%; /* Lebar dua kali agar bisa digeser untuk efek shimmer */
            transform: scaleX(0);       /* Awalnya tidak terlihat karena lebar nol */
            transform-origin: left;     /* Proses scale dimulai dari sisi kiri */
            transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.4s;
            border-radius: 2px;
        }
        .visible .shimmer-underline::after {
            transform: scaleX(1); /* Garis muncul saat section terlihat */
            animation: shimmer-move 3s ease-in-out 1.2s infinite; /* Lalu bergerak terus */
        }
        @keyframes shimmer-move {
            0%, 100% { background-position: 200% 0; }  /* Cahaya di sisi kanan */
            50%       { background-position: -200% 0; } /* Cahaya di sisi kiri */
        }

        /* Cahaya glow samar di belakang angka statistik saat section terlihat */
        .stat-glow { position: relative; }
        .stat-glow::before {
            content: '';
            position: absolute; inset: -8px -12px;
            background: radial-gradient(ellipse, rgba(16,185,129,0.08) 0%, transparent 70%);
            border-radius: 12px;
            opacity: 0; /* Tersembunyi dulu */
            transition: opacity 0.6s ease 0.8s;
        }
        .visible .stat-glow::before { opacity: 1; } /* Muncul saat section sudah terlihat */

        /* Animasi naik-turun berbeda untuk setiap ikon sosial media di footer */
        @keyframes social-float-1 {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-3px); }
        }
        @keyframes social-float-2 {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-5px); } /* Lebih tinggi dibanding float-1 */
        }
        /* Masing-masing ikon punya durasi dan delay berbeda agar tidak bergerak serentak */
        .social-float-1 { animation: social-float-1 3s ease-in-out 0s infinite; }
        .social-float-2 { animation: social-float-2 3.5s ease-in-out 0.5s infinite; }
        .social-float-3 { animation: social-float-1 2.8s ease-in-out 1s infinite; }

        /* Navbar muncul turun dari atas saat halaman pertama kali dimuat */
        .glass-nav { animation: nav-enter 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.05s both; }
        @keyframes nav-enter {
            from { opacity: 0; transform: translateY(-20px) scale(0.97); } /* Dari atas dan sedikit mengecil */
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* ─────────────────────────────────────────────
           EFEK BERPUTAR PADA TOMBOL CTA
           Saat hover, border di sekitar tombol berputar seperti cincin bercahaya.
           Menggunakan conic-gradient yang di-rotate terus-menerus.
        ───────────────────────────────────────────── */
        .btn-cta { position: relative; z-index: 0; }
        .btn-cta::before {
            content: '';
            position: absolute; inset: -2px;
            border-radius: 9999px;
            background: conic-gradient(from 0deg, #10b981, #34d399, #6ee7b7, #10b981); /* Gradasi melingkar */
            z-index: -1;    /* Berada di belakang tombol */
            opacity: 0;     /* Tidak terlihat saat tidak hover */
            transition: opacity 0.4s ease;
            filter: blur(6px); /* Diburamkan agar terlihat seperti cahaya memancar */
        }
        .btn-cta:hover::before {
            opacity: 0.5;
            animation: spin-glow 3s linear infinite; /* Berputar terus selama hover */
        }
        @keyframes spin-glow { to { transform: rotate(360deg); } }

        /* ─────────────────────────────────────────────
           DEKORASI GARIS DIAGONAL
           Dua garis miring di sisi kanan hero section, murni dekoratif.
           Bergerak naik-turun perlahan dengan arah berlawanan.
        ───────────────────────────────────────────── */
        .slash-deco {
            position: absolute; right: 0; top: 0; width: 38%; height: 100%;
            overflow: hidden; pointer-events: none; z-index: 1;
        }
        .slash-deco::before, .slash-deco::after {
            content: ''; position: absolute; width: 2px; height: 140%;
            transform: rotate(20deg); top: -20%; border-radius: 2px; /* Garis miring 20 derajat */
        }
        .slash-deco::before { right: 18%; background: linear-gradient(180deg, transparent, rgba(16,185,129,0.18), transparent); }
        .slash-deco::after  { right: 10%; background: linear-gradient(180deg, transparent, rgba(52,211,153,0.10), transparent); }
        /* Garis pertama bergerak normal, garis kedua bergerak terbalik (reverse) */
        .slash-deco::before { animation: slash-drift 8s ease-in-out infinite; }
        .slash-deco::after  { animation: slash-drift 8s ease-in-out 2s infinite reverse; }
        @keyframes slash-drift {
            0%, 100% { transform: rotate(20deg) translateY(0); }
            50%       { transform: rotate(20deg) translateY(20px); }
        }

        /* Garis pemisah vertikal tipis di antara statistik (FullStack | Indonesia | 2+ tahun) */
        .stat-divider { width: 1px; height: 40px; background: rgba(255,255,255,0.08); }

        /* Panah scroll indicator memantul naik-turun */
        .scroll-indicator { animation: bounce 2.4s ease-in-out infinite; transition: opacity 0.4s ease; }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); opacity: 0.4; }
            50%       { transform: translateY(8px); opacity: 0.8; } /* Turun dan lebih terang di tengah */
        }

        /* ─────────────────────────────────────────────
           MENU MOBILE
           Menggunakan max-height untuk animasi buka-tutup,
           karena CSS tidak bisa melakukan transition dari height 0 ke height auto secara langsung.
        ───────────────────────────────────────────── */
        .mobile-menu {
            max-height: 0; overflow: hidden; /* Tersembunyi: tinggi nol */
            transition: max-height 0.45s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.35s ease, padding 0.35s ease;
            opacity: 0; padding-top: 0; padding-bottom: 0;
        }
        .mobile-menu.open { max-height: 320px; opacity: 1; padding-top: 1rem; padding-bottom: 1rem; } /* Terbuka */
        .mobile-menu a {
            display: block; padding: 0.6rem 0; color: rgba(255,255,255,0.55);
            font-size: 0.9rem; font-weight: 500; transition: color 0.25s ease, padding-left 0.25s ease;
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        .mobile-menu a:last-child { border-bottom: none; } /* Hapus garis bawah pada item terakhir */
        .mobile-menu a:hover { color: #34d399; padding-left: 6px; } /* Geser sedikit ke kanan saat hover */

        /* Animasi ikon hamburger berubah menjadi tanda silang saat menu terbuka */
        .hamburger-line { transition: all 0.3s ease; transform-origin: center; }
        .hamburger-active .hamburger-line:nth-child(1) { transform: translateY(7px) rotate(45deg); }   /* Garis atas turun dan miring */
        .hamburger-active .hamburger-line:nth-child(2) { opacity: 0; transform: scaleX(0); }           /* Garis tengah menghilang */
        .hamburger-active .hamburger-line:nth-child(3) { transform: translateY(-7px) rotate(-45deg); } /* Garis bawah naik dan miring */

        /* ─────────────────────────────────────────────
           LAPISAN TEKSTUR NOISE
           Tekstur grain/noise halus di seluruh halaman untuk kedalaman visual.
           Dibuat dari SVG inline dengan filter feTurbulence.
           Opacity sangat kecil (0.025) agar tidak mengganggu keterbacaan konten.
        ───────────────────────────────────────────── */
        .noise-overlay {
            position: fixed; inset: 0; z-index: 0; pointer-events: none; opacity: 0.025;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-repeat: repeat; background-size: 180px;
        }

        /* ─────────────────────────────────────────────
           MARQUEE (Scroller Tech Stack)
           mask-image: membuat efek fade di tepi kiri dan kanan agar transisi terlihat halus.
           Track berisi dua salinan daftar yang identik. Saat salinan pertama selesai digeser,
           salinan kedua langsung menyambung sehingga loop terlihat mulus tanpa jeda.
        ───────────────────────────────────────────── */
        .marquee-mask {
            -webkit-mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
            mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
            overflow: hidden;
        }
        .marquee-track {
            display: flex;
            animation: marquee-scroll 150s linear infinite; /* 150 detik per putaran, sangat lambat dan halus */
            width: max-content; /* Lebar menyesuaikan total semua konten di dalamnya */
        }
        @keyframes marquee-scroll {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); } /* Geser separuh dari total lebar (= satu set daftar) */
        }
        .marquee-item img { width: 20px; height: 20px; object-fit: contain; }

        /* Gaya header section yang digunakan di About, Skills, Projects, dan Contact */
        .section-header { display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 3rem; }
        .section-eyebrow { font-size: 0.68rem; font-weight: 600; letter-spacing: 0.25em; text-transform: uppercase; color: rgba(255,255,255,0.3); margin-bottom: 0.75rem; }
        .section-title { font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 800; letter-spacing: -0.02em; color: #fff; display: flex; align-items: center; justify-content: center; gap: 0.75rem; }

        /* ─────────────────────────────────────────────
           FILTER IKON BERDASARKAN TEMA
           Beberapa ikon SVG aslinya berwarna hitam (seperti GitHub dan GPT).
           Di dark mode perlu dibalik (invert) agar terlihat di latar gelap.
           Di light mode dikembalikan ke warna normal.
        ───────────────────────────────────────────── */
        .icon-dark-bg { filter: invert(1); }              /* Balik warna hitam menjadi putih */
        html.light .icon-dark-bg { filter: none; }         /* Di light mode: kembalikan ke normal (hitam) */
        .icon-light-bg { filter: none; }
        html.light .icon-light-bg { filter: invert(1) brightness(0.2); } /* Di light mode: jadikan gelap */

        /* Tombol kembali ke atas, muncul setelah pengguna scroll lebih dari 400 piksel */
        #backToTop {
            position: fixed; bottom: 2rem; right: 2rem; z-index: 100;
            width: 42px; height: 42px; border-radius: 50%;
            background: rgba(16,185,129,0.15);
            border: 1px solid rgba(16,185,129,0.35); color: #34d399;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; opacity: 0; pointer-events: none; /* Awalnya tidak terlihat dan tidak bisa diklik */
            transition: all 0.3s ease; backdrop-filter: blur(12px);
        }
        #backToTop.visible { opacity: 1; pointer-events: auto; } /* Terlihat dan bisa diklik saat class .visible ada */
        #backToTop:hover { background: rgba(16,185,129,0.25); border-color: rgba(16,185,129,0.6); transform: translateY(-3px); box-shadow: 0 0 20px rgba(16,185,129,0.3); }

        /* ─────────────────────────────────────────────
           VARIABEL TEMA CSS (CSS Custom Properties)
           Mendukung dark mode (default) dan light mode.
           Nilai variabel berubah saat class .light ditambahkan ke elemen <html>.
        ───────────────────────────────────────────── */
        :root {
            --bg: #0d0d0d; --text: #f5f5f5;
            --text-muted: rgba(245,245,245,0.45);
            --border: rgba(255,255,255,0.07);
            --glass: rgba(255,255,255,0.04);
            --card: rgba(255,255,255,0.02);
        }
        html.light {
            --bg: #f0f0eb; --text: #0d0d0d; /* Latar terang, teks gelap untuk light mode */
            --text-muted: rgba(13,13,13,0.5);
            --border: rgba(0,0,0,0.08);
            --glass: rgba(255,255,255,0.65);
            --card: rgba(255,255,255,0.5);
        }
        body { background-color: var(--bg); color: var(--text); transition: background-color 0.4s ease, color 0.4s ease; }
        .glass-nav { background: var(--glass); border-color: var(--border); }
        .txt-muted { color: var(--text-muted); }

        /* Override warna-warna Tailwind khusus untuk light mode */
        /* Selector [class*="..."] memilih elemen yang nama class-nya mengandung string tertentu */
        html.light .nav-link { color: rgba(0,0,0,0.5); }
        html.light .nav-link:hover { color: #0d0d0d; }
        html.light .bg-glow-tl, html.light .bg-glow-br, html.light .bg-glow-center { opacity: 0.5; }
        html.light .noise-overlay { opacity: 0.015; }
        html.light [class*="bg-white/[0.02]"] { background: rgba(255,255,255,0.6) !important; }
        html.light [class*="bg-white/[0.03]"] { background: rgba(255,255,255,0.7) !important; }
        html.light [class*="bg-\[#111111\]"]  { background: #ffffff !important; }
        html.light [class*="border-white/[0.05]"],
        html.light [class*="border-white/[0.06]"],
        html.light [class*="border-white/[0.07]"],
        html.light [class*="border-white/[0.08]"] { border-color: rgba(0,0,0,0.08) !important; }
        html.light [class*="text-white/40"], html.light [class*="text-white/45"],
        html.light [class*="text-white/50"], html.light [class*="text-white/55"] { color: rgba(0,0,0,0.5) !important; }
        html.light [class*="text-white/25"], html.light [class*="text-white/30"],
        html.light [class*="text-white/35"] { color: rgba(0,0,0,0.35) !important; }
        html.light [class*="text-white/60"], html.light [class*="text-white/70"] { color: rgba(0,0,0,0.65) !important; }
        html.light .text-white, html.light [class*="text-white "] { color: #0d0d0d !important; }
        html.light [class*="text-white/15"] { color: rgba(0,0,0,0.25) !important; }
        html.light input, html.light textarea { background: rgba(0,0,0,0.03) !important; border-color: rgba(0,0,0,0.1) !important; color: #0d0d0d !important; }
        html.light input::placeholder, html.light textarea::placeholder { color: rgba(0,0,0,0.3) !important; }
        html.light .mobile-menu a { color: rgba(0,0,0,0.5); }
        html.light .mobile-menu a:hover { color: #10b981; }
        html.light .hamburger-line { background: rgba(0,0,0,0.6) !important; }
        html.light .section-eyebrow { color: rgba(0,0,0,0.3); }
        html.light .section-title { color: #0d0d0d; }
        html.light .eyebrow-line { color: rgba(0,0,0,0.3); }
        html.light .eyebrow-line::before, html.light .eyebrow-line::after { background: rgba(0,0,0,0.12); }
        html.light footer { border-color: rgba(0,0,0,0.08); }
        html.light .stat-divider { background: rgba(0,0,0,0.1); }
        html.light .scroll-indicator svg { color: rgba(0,0,0,0.2) !important; }
        html.light #backToTop { background: rgba(16,185,129,0.1); }
        html.light .txt-muted { color: rgba(0,0,0,0.5) !important; }
        html.light .empty-project-icon { background: rgba(0,0,0,0.04) !important; border-color: rgba(0,0,0,0.1) !important; }
        html.light .empty-project-icon svg { color: rgba(0,0,0,0.25) !important; }
        html.light .empty-project-txt { color: rgba(0,0,0,0.35) !important; }
        html.light .empty-project-sub { color: rgba(0,0,0,0.22) !important; }


        /* ─────────────────────────────────────────────
           STORY / PROCESS CARDS
           Shared visual language for the narrative sections.
        ───────────────────────────────────────────── */
        .story-card {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.06);
            background: linear-gradient(180deg, rgba(255,255,255,0.028), rgba(255,255,255,0.012));
            border-radius: 1.5rem;
            transition: transform 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease, background 0.35s ease;
        }
        .story-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 85% 15%, rgba(16,185,129,0.10), transparent 34%);
            opacity: 0;
            transition: opacity 0.35s ease;
            pointer-events: none;
        }
        .story-card:hover {
            transform: translateY(-4px);
            border-color: rgba(16,185,129,0.22);
            box-shadow: 0 20px 50px rgba(0,0,0,0.22), 0 0 30px rgba(16,185,129,0.06);
        }
        .story-card:hover::before { opacity: 1; }

        .story-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            border-radius: 0.75rem;
            border: 1px solid rgba(16,185,129,0.18);
            background: rgba(16,185,129,0.08);
            color: #34d399;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
        }

        .journey-card { min-height: 17rem; }
        .journey-card .story-watermark {
            position: absolute;
            right: -0.25rem;
            bottom: -1rem;
            font-size: 7rem;
            line-height: 1;
            font-weight: 800;
            letter-spacing: -0.08em;
            color: rgba(255,255,255,0.025);
            user-select: none;
            pointer-events: none;
        }

        .build-card {
            min-height: 18rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .build-icon {
            width: 3rem;
            height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 1rem;
            border: 1px solid rgba(16,185,129,0.15);
            background: rgba(16,185,129,0.06);
            color: #34d399;
        }

        .process-wrap {
            position: relative;
        }
        .process-line {
            position: absolute;
            top: 1.65rem;
            left: 7%;
            right: 7%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(16,185,129,0.18), rgba(16,185,129,0.18), transparent);
        }
        .process-card {
            position: relative;
            min-height: 13rem;
            padding: 1.5rem;
            border-radius: 1.5rem;
            border: 1px solid rgba(255,255,255,0.06);
            background: rgba(255,255,255,0.018);
            transition: transform 0.3s ease, border-color 0.3s ease, background 0.3s ease;
        }
        .process-card:hover {
            transform: translateY(-4px);
            border-color: rgba(16,185,129,0.22);
            background: rgba(16,185,129,0.025);
        }
        .process-dot {
            position: relative;
            z-index: 2;
            width: 2rem;
            height: 2rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0d0d0d;
            border: 1px solid rgba(16,185,129,0.35);
            color: #34d399;
            font-size: 0.7rem;
            font-weight: 800;
            box-shadow: 0 0 0 6px rgba(16,185,129,0.035);
        }

        .explore-panel {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 1.75rem;
            background: linear-gradient(135deg, rgba(255,255,255,0.026), rgba(16,185,129,0.025));
        }
        .explore-panel::after {
            content: '';
            position: absolute;
            width: 18rem;
            height: 18rem;
            right: -7rem;
            top: -8rem;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(16,185,129,0.10), transparent 68%);
            pointer-events: none;
        }
        .explore-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.7rem 0.9rem;
            border-radius: 9999px;
            border: 1px solid rgba(255,255,255,0.07);
            background: rgba(255,255,255,0.025);
            color: rgba(255,255,255,0.62);
            font-size: 0.78rem;
            line-height: 1.1;
            transition: all 0.3s ease;
        }
        .explore-chip::before {
            content: '';
            width: 0.36rem;
            height: 0.36rem;
            border-radius: 9999px;
            background: #34d399;
            box-shadow: 0 0 10px rgba(52,211,153,0.35);
        }
        .explore-chip:hover {
            color: rgba(255,255,255,0.9);
            border-color: rgba(16,185,129,0.2);
            transform: translateY(-2px);
        }

        /* Currently Exploring — bright mode counterpart */
        html.light .explore-panel {
            border-color: rgba(13,13,13,0.08);
            background: linear-gradient(
                135deg,
                rgba(255,255,255,0.78),
                rgba(16,185,129,0.06)
            );
            box-shadow:
                0 18px 50px rgba(13,13,13,0.06),
                inset 0 1px 0 rgba(255,255,255,0.72);
        }

        html.light .explore-panel::after {
            background: radial-gradient(
                circle,
                rgba(16,185,129,0.13),
                transparent 68%
            );
        }

        html.light .explore-panel h3 {
            color: #0d0d0d !important;
        }

        html.light .explore-panel p {
            color: rgba(13,13,13,0.52) !important;
        }

        html.light .explore-panel .text-emerald-400 {
            color: #059669 !important;
        }

        html.light .explore-chip {
            border-color: rgba(13,13,13,0.08);
            background: rgba(255,255,255,0.62);
            color: rgba(13,13,13,0.58);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.5);
        }

        html.light .explore-chip:hover {
            color: rgba(13,13,13,0.88);
            border-color: rgba(16,185,129,0.28);
            background: rgba(16,185,129,0.07);
            box-shadow: 0 8px 20px rgba(13,13,13,0.05);
        }

        .section-anchor { scroll-margin-top: 8rem; }

        @media (max-width: 767px) {
            .process-line { display: none; }
            .process-card { min-height: auto; }
            .journey-card, .build-card { min-height: auto; }
        }

        @media (prefers-reduced-motion: reduce) {
            .story-card, .process-card, .explore-chip { transition: none; }
        }

    </style>
</head>

{{-- relative: agar elemen absolute di dalamnya bisa diposisikan relatif terhadap body --}}
{{-- antialiased: membuat teks lebih halus di layar --}}
<body class="relative min-h-screen antialiased">
    {{-- Audio latar belakang yang diputar otomatis dan berulang, disembunyikan dari tampilan --}}
    <audio src="{{ asset('audio/.mp3') }}" autoplay loop style="display:none;"></audio>

    {{-- Lapisan tekstur noise di atas seluruh halaman --}}
    <div class="noise-overlay"></div>
    {{-- Tiga cahaya hijau di latar, digerakkan mengikuti mouse via JavaScript --}}
    <div class="bg-glow-tl" id="glowTl"></div>
    <div class="bg-glow-br" id="glowBr"></div>
    <div class="bg-glow-center" id="glowCenter"></div>

    {{-- ═══════════ NAVBAR ═══════════ --}}
    {{-- fixed: navbar selalu menempel di atas layar saat scroll --}}
    {{-- z-50: pastikan navbar berada di lapisan paling atas --}}
    <header class="fixed top-0 inset-x-0 z-50 flex flex-col items-center pt-5 px-4">
        <nav class="glass-nav rounded-full px-5 md:px-6 py-3 flex items-center justify-between w-full max-w-4xl">
            {{-- Logo: teks "Putra" putih dan "Dev." dengan gradasi hijau --}}
            <a href="#" class="text-white font-extrabold text-lg tracking-tight whitespace-nowrap select-none">
                Putra<span class="gradient-text">Dev.</span>
            </a>

            {{-- Menu navigasi desktop: disembunyikan di mobile (hidden), ditampilkan di md ke atas --}}
            <ul class="hidden md:flex items-center gap-4 lg:gap-5 font-medium">
                <li><a href="#" class="nav-link">Home</a></li>
                <li><a href="#about" class="nav-link">About</a></li>
                <li><a href="#journey" class="nav-link">Journey</a></li>
                <li><a href="#build" class="nav-link">Build</a></li>
                <li><a href="#skills" class="nav-link">Stack</a></li>
                <li><a href="#project" class="nav-link">Work</a></li>
                <li><a href="#contact" class="nav-link">Contact</a></li>
            </ul>

            <div class="flex items-center gap-2">
                {{-- Tombol untuk berganti antara dark mode dan light mode --}}
                <button id="themeToggle"
                    class="flex items-center justify-center w-9 h-9 rounded-full border border-white/10 hover:border-emerald-500/40 bg-white/[0.03] hover:bg-emerald-500/[0.06] transition-all duration-300"
                    aria-label="Toggle theme">
                    {{-- Ikon matahari: ditampilkan saat dark mode aktif --}}
                    <svg id="iconSun" class="w-4 h-4 text-white/50 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="4"/>
                        <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
                    </svg>
                    {{-- Ikon bulan: ditampilkan saat light mode aktif, disembunyikan secara default --}}
                    <svg id="iconMoon" class="w-4 h-4 text-white/50 hidden transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                    </svg>
                </button>

                {{-- Tombol hamburger untuk membuka menu mobile, hanya tampil di layar kecil --}}
                <button id="menuToggle"
                    class="md:hidden flex flex-col items-center justify-center gap-[5px] w-9 h-9 rounded-full hover:bg-white/5 transition-colors"
                    aria-label="Toggle menu">
                    {{-- Tiga garis yang akan beranimasi menjadi tanda X saat menu dibuka --}}
                    <span class="hamburger-line block w-[18px] h-[1.5px] bg-white/70 rounded-full"></span>
                    <span class="hamburger-line block w-[18px] h-[1.5px] bg-white/70 rounded-full"></span>
                    <span class="hamburger-line block w-[18px] h-[1.5px] bg-white/70 rounded-full"></span>
                </button>
            </div>
        </nav>

        {{-- Menu dropdown mobile, tersembunyi secara default (max-height 0) --}}
        {{-- Class .open ditambahkan atau dihapus oleh JavaScript saat tombol hamburger diklik --}}
        <div id="mobileMenu" class="mobile-menu glass-nav rounded-2xl mt-2 w-full max-w-3xl px-6 md:hidden">
            <a href="#">Home</a>
            <a href="#about">About</a>
            <a href="#journey">Journey</a>
            <a href="#build">Build</a>
            <a href="#skills">Stack</a>
            <a href="#project">Work</a>
            <a href="#contact">Contact</a>
        </div>
    </header>

    {{-- ═══════════ HERO SECTION ═══════════ --}}
    {{-- min-h-screen: tinggi minimal satu layar penuh --}}
    {{-- flex flex-col justify-center: konten diposisikan di tengah secara vertikal --}}
    <main class="relative z-10 min-h-screen flex flex-col justify-center px-6 md:px-16 lg:px-28 pt-32 pb-24">
        {{-- Dekorasi garis diagonal di sisi kanan, hanya muncul di desktop --}}
        <div class="slash-deco hidden lg:block"></div>

        {{-- Badge status ketersediaan dengan titik hijau berkedip --}}
        <div class="fade-up delay-1 inline-flex items-center gap-2.5 mb-8 rounded-full px-4 py-2 w-fit">
            <span class="dot-pulse"></span>
            <span class="text-xs font-semibold tracking-[0.18em] uppercase text-white/50">Open to opportunities</span>
        </div>

        {{-- Judul utama hero --}}
        {{-- clamp(): ukuran font responsif dengan nilai minimum, ideal, dan maksimum --}}
        <h1 class="fade-up delay-2 font-extrabold leading-[1.06] tracking-tight max-w-4xl">
            <span class="block text-[clamp(2.4rem,6.5vw,3.2rem)] text-white">I build digital products</span>
            <span class="block text-[clamp(2.4rem,6.5vw,3.2rem)]">
                <span class="gradient-text">with curiosity, code,</span>
                <span class="text-white"> and AI.</span>
            </span>
        </h1>

        {{-- ─────────────────────────────────────────────
             EFEK MESIN KETIK (TYPEWRITER) menggunakan Alpine.js
             x-data: mendefinisikan state dan method untuk komponen ini.
             Fungsi tick() berjalan rekursif: menambah atau mengurangi satu karakter,
             lalu memanggil dirinya sendiri setelah jeda waktu tertentu.
        ───────────────────────────────────────────── --}}
        <div class="fade-up delay-3 mt-7 max-w-xl"
             x-data="{
                 sentences: [
                     'A developer from Banjarmasin who learns by building real things.',
                     'I turn ideas, problems, and experiments into working web experiences.',
                     'My current home base: Laravel, modern frontend, UI/UX, and AI-assisted workflows.',
                     'Still learning. Still building. Always looking for the next thing to improve.'
                 ],
                 sentenceIndex: 0,  /* Indeks kalimat yang sedang aktif */
                 charIndex: 0,      /* Posisi karakter saat ini */
                 isDeleting: false, /* true = sedang menghapus, false = sedang mengetik */
                 text: '',          /* Teks yang ditampilkan ke layar */
                 typeSpeed: 20,     /* Jeda antar karakter saat mengetik (milidetik) */
                 deleteSpeed: 10,   /* Jeda antar karakter saat menghapus (milidetik) */
                 pauseEnd: 2500,    /* Jeda setelah kalimat selesai diketik (milidetik) */
                 pauseStart: 500,   /* Jeda sebelum mulai mengetik kalimat berikutnya (milidetik) */
                 init() { this.tick(); }, /* Mulai berjalan saat Alpine menginisialisasi komponen */
                 tick() {
                     let current = this.sentences[this.sentenceIndex];
                     if (this.isDeleting) {
                         this.text = current.substring(0, this.charIndex - 1); /* Hapus satu karakter dari kanan */
                         this.charIndex--;
                     } else {
                         this.text = current.substring(0, this.charIndex + 1); /* Tambah satu karakter */
                         this.charIndex++;
                     }
                     let delay = this.isDeleting ? this.deleteSpeed : this.typeSpeed;
                     if (!this.isDeleting && this.charIndex === current.length) {
                         delay = this.pauseEnd; this.isDeleting = true; /* Kalimat selesai, mulai menghapus */
                     } else if (this.isDeleting && this.charIndex === 0) {
                         this.isDeleting = false;
                         this.sentenceIndex = (this.sentenceIndex + 1) % this.sentences.length; /* Pindah ke kalimat berikutnya */
                         delay = this.pauseStart;
                     }
                     setTimeout(() => this.tick(), delay); /* Panggil fungsi ini lagi setelah jeda */
                 }
             }">
            <p class="text-[clamp(0.88rem,1.5vw,1.05rem)] txt-muted leading-relaxed font-light min-h-[4.5rem]">
                <span x-text="text"></span> {{-- Teks yang berubah dinamis --}}
                {{-- Kursor berkedip, menggunakan animasi blink dari CSS --}}
                <span class="inline-block w-[2px] h-[1em] bg-emerald-400/70 align-middle ml-0.5 animate-blink"></span>
            </p>
        </div>

        {{-- Tombol ajakan bertindak (CTA) --}}
        <div class="fade-up delay-4 flex flex-wrap items-center gap-4 mt-10">
            <a href="#project" class="btn-cta font-bold px-7 py-3.5 rounded-full text-sm tracking-wide">View My Work</a>
            {{-- Tautan ke halaman personal dengan ikon chevron --}}
            <a href="#journey" class="group flex items-center gap-2.5 text-sm font-medium text-white/50 hover:text-white transition-colors duration-300">
                <span class="flex items-center justify-center w-9 h-9 rounded-full border border-white/10 group-hover:border-white/25 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </span>
                Explore my journey
            </a>
        </div>

        {{-- ═══════════ MARQUEE TECH STACK ═══════════ --}}
        <div class="fade-up delay-5 w-full mt-16 py-6 relative">
            <div class="marquee-mask"> {{-- Fade di tepi kiri dan kanan agar transisi terlihat halus --}}
                <div class="marquee-track"> {{-- Bergerak ke kiri terus-menerus dengan animasi CSS --}}
                    @php
                    /* Array berisi 20 teknologi yang ditampilkan pada marquee */
                    $marqueeSkills = [
                        ['name' => 'HTML',       'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/html5/html5-original.svg'],
                        ['name' => 'CSS',        'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/css3/css3-original.svg'],
                        ['name' => 'JavaScript', 'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg'],
                        ['name' => 'Tailwind CSS','img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tailwindcss/tailwindcss-original.svg'],
                        ['name' => 'Bootstrap',  'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/bootstrap/bootstrap-original.svg'],
                        ['name' => 'Alpine.js',   'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/alpinejs/alpinejs-original.svg'],
                        ['name' => 'PHP',        'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg'],
                        ['name' => 'Laravel',    'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/laravel/laravel-original.svg'],
                        ['name' => 'MySQL',      'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg'],
                        ['name' => 'Node.js',    'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nodejs/nodejs-original.svg'],
                        ['name' => 'Vite',       'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/vitejs/vitejs-original.svg'],
                        ['name' => 'Figma',      'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/figma/figma-original.svg'],
                        ['name' => 'VS Code',    'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/vscode/vscode-original.svg'],
                        ['name' => 'Git',        'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/git/git-original.svg'],
                        ['name' => 'GitHub',     'img' => 'https://unpkg.com/@lobehub/icons-static-svg@latest/icons/github.svg'],
                        ['name' => 'Linux',      'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/linux/linux-original.svg'],
                        ['name' => 'GPT',        'img' => 'https://unpkg.com/@lobehub/icons-static-svg@latest/icons/openai.svg'],
                        ['name' => 'Claude',     'img' => 'https://unpkg.com/@lobehub/icons-static-svg@latest/icons/claude-color.svg'],
                        ['name' => 'Gemini',     'img' => 'https://unpkg.com/@lobehub/icons-static-svg@latest/icons/gemini-color.svg'],
                        ['name' => 'Z.ai',       'img' => 'https://unpkg.com/@lobehub/icons-static-svg@latest/icons/zhipu-color.svg'],
                    ];
                    @endphp

                    {{-- Salinan PERTAMA dari daftar --}}
                    <div class="flex items-center gap-4 shrink-0 pr-4">
                        @foreach($marqueeSkills as $skill)
                        <div class="marquee-item flex items-center gap-3 px-5 py-3 border border-white/[0.06] bg-white/[0.02] rounded-full group cursor-default transition-all hover:border-emerald-500/30 hover:bg-emerald-500/5">
                            <img src="{{ $skill['img'] }}" alt="{{ $skill['name'] }}" class="w-5 h-5" loading="lazy" />
                            <span class="text-sm font-medium text-white/60 group-hover:text-emerald-400 transition-colors whitespace-nowrap">{{ $skill['name'] }}</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Salinan KEDUA, persis sama dengan yang pertama --}}
                    {{-- Saat animasi translateX(-50%) selesai, salinan kedua sudah menyambung sehingga loop terlihat mulus --}}
                    <div class="flex items-center gap-4 shrink-0 pr-4">
                        @foreach($marqueeSkills as $skill)
                        <div class="marquee-item flex items-center gap-3 px-5 py-3 border border-white/[0.06] bg-white/[0.02] rounded-full group cursor-default transition-all hover:border-emerald-500/30 hover:bg-emerald-500/5">
                            <img src="{{ $skill['img'] }}" alt="{{ $skill['name'] }}" class="w-5 h-5" loading="lazy" />
                            <span class="text-sm font-medium text-white/60 group-hover:text-emerald-400 transition-colors whitespace-nowrap">{{ $skill['name'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════ ABOUT SECTION ═══════════ --}}
        {{-- Elemen section kosong sebagai anchor target untuk scroll navigasi --}}
        <section id="about" class="section-anchor"></section>
        {{-- reveal: elemen ini tersembunyi sampai di-scroll ke area pandang --}}
        <div class="section-header mt-22 reveal">
            <p class="eyebrow-line">About me</p>
            <h2 class="section-title"><span class="shimmer-underline">More Than Just Code</span></h2>
        </div>

        {{-- Kartu about, muncul dengan efek zoom saat di-scroll --}}
        <div class="reveal-scale w-full p-8 md:p-12 rounded-3xl border border-white/[0.06] bg-white/[0.02] relative overflow-hidden">
            {{-- Cahaya hijau dekoratif di pojok kiri atas kartu --}}
            <div class="absolute -top-1/2 -left-1/4 w-[600px] h-[600px] bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>

            {{-- Tata letak: kolom di mobile, baris terbalik di desktop (foto di kanan, teks di kiri) --}}
            <div class="relative z-10 flex flex-col md:flex-row-reverse items-center gap-10 md:gap-16">
                {{-- Foto profil dengan efek melayang (float-anim) --}}
                <div class="w-full md:w-2/5 relative group float-anim">
                    {{-- Glow hijau di belakang foto, makin terang saat hover --}}
                    <div class="absolute inset-0 bg-emerald-500/20 blur-2xl rounded-2xl group-hover:bg-emerald-500/30 transition-colors duration-500 scale-90"></div>
                    {{-- asset() menghasilkan URL lengkap ke file di folder public --}}
                    <img src="{{ asset('images/portofolio.png') }}" alt="Muhammad Putra"
                         class="relative w-full max-w-xs mx-auto md:max-w-none h-auto rounded-2xl border border-white/10 object-cover shadow-2xl">
                </div>

                <div class="w-full md:w-3/5">
                    <h2 class="text-4xl md:text-3xl font-extrabold tracking-tight mb-8">
                        Hi, I'm <span class="gradient-text">Muhammad Putra</span>.
                    </h2>
                    <div class="space-y-5 text-white/50 leading-relaxed text-base mb-9 font-light">
                        <p>
                            Saya belajar development dengan satu kebiasaan sederhana: <span class="text-white/80 font-medium">build first, learn from the problem.</span> Saya lebih suka memahami sebuah teknologi lewat sesuatu yang benar-benar dibuat dan diuji daripada sekadar menghafal teorinya.
                        </p>
                        <p>
                            Perjalanan saya berkembang dari frontend dan UI/UX menuju full-stack development dengan Laravel, database, authentication, dashboard, dan sistem web yang lebih nyata. Di saat yang sama, AI menjadi bagian dari workflow saya untuk brainstorming, debugging, riset, dan mempercepat proses tanpa mengambil alih proses berpikir.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-6 sm:gap-8">
                        <div class="stat-glow">
                            <p class="text-3xl font-extrabold text-white"><span class="gradient-text">Web</span></p>
                            <p class="text-[0.68rem] text-white/35 mt-1.5 tracking-[0.14em] uppercase font-medium">Focus</p>
                        </div>
                        <div class="stat-divider hidden sm:block"></div>
                        <div class="stat-glow">
                            <p class="text-3xl font-extrabold text-white">Banjarmasin</p>
                            <p class="text-[0.68rem] text-white/35 mt-1.5 tracking-[0.14em] uppercase font-medium">Based in</p>
                        </div>
                        <div class="stat-divider hidden sm:block"></div>
                        <div class="stat-glow">
                            <p class="text-3xl font-extrabold text-white"><span class="gradient-text">Build-first</span></p>
                            <p class="text-[0.68rem] text-white/35 mt-1.5 tracking-[0.14em] uppercase font-medium">Approach</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- ═══════════ JOURNEY SECTION ═══════════ --}}
    <section id="journey" class="section-anchor relative z-10 py-22 px-6 md:px-16 lg:px-28">
        <div class="section-header mb-14 reveal">
            <p class="eyebrow-line">My Journey</p>
            <h2 class="section-title"><span class="shimmer-underline">Still Becoming</span></h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <article class="story-card journey-card reveal-left p-7 md:p-8">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-7">
                        <span class="text-[0.68rem] font-semibold tracking-[0.2em] uppercase text-emerald-400">Curiosity</span>
                        <span class="story-number">01</span>
                    </div>
                    <h3 class="text-2xl font-bold text-white tracking-tight mb-3">Started by exploring.</h3>
                    <p class="text-sm md:text-[0.92rem] text-white/40 leading-relaxed max-w-lg">
                        I started by wanting to understand how websites actually work — from the interface people see to the code and systems behind it.
                    </p>
                </div>
                <span class="story-watermark">01</span>
            </article>

            <article class="story-card journey-card reveal p-7 md:p-8">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-7">
                        <span class="text-[0.68rem] font-semibold tracking-[0.2em] uppercase text-emerald-400">Building</span>
                        <span class="story-number">02</span>
                    </div>
                    <h3 class="text-2xl font-bold text-white tracking-tight mb-3">Learned through projects.</h3>
                    <p class="text-sm md:text-[0.92rem] text-white/40 leading-relaxed max-w-lg">
                        Frontend, UI/UX, Laravel, databases, authentication, dashboards, and debugging became lessons learned by actually making things.
                    </p>
                </div>
                <span class="story-watermark">02</span>
            </article>

            <article class="story-card journey-card reveal-left p-7 md:p-8">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-7">
                        <span class="text-[0.68rem] font-semibold tracking-[0.2em] uppercase text-emerald-400">Shipping</span>
                        <span class="story-number">03</span>
                    </div>
                    <h3 class="text-2xl font-bold text-white tracking-tight mb-3">Ideas became systems.</h3>
                    <p class="text-sm md:text-[0.92rem] text-white/40 leading-relaxed max-w-lg">
                        The goal shifted from making something look good to making it useful, maintainable, connected, and ready to be used.
                    </p>
                </div>
                <span class="story-watermark">03</span>
            </article>

            <article class="story-card journey-card reveal-right p-7 md:p-8">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-7">
                        <span class="text-[0.68rem] font-semibold tracking-[0.2em] uppercase text-emerald-400">Exploring</span>
                        <span class="story-number">04</span>
                    </div>
                    <h3 class="text-2xl font-bold text-white tracking-tight mb-3">Building with AI.</h3>
                    <p class="text-sm md:text-[0.92rem] text-white/40 leading-relaxed max-w-lg">
                        AI is now part of my workflow for research, brainstorming, debugging, and faster iteration — while direction and final decisions stay human.
                    </p>
                </div>
                <span class="story-watermark">04</span>
            </article>
        </div>

        <div class="reveal mt-5 rounded-2xl border border-white/[0.06] bg-white/[0.018] px-6 py-5 text-center">
            <p class="text-sm md:text-base font-medium text-white/55">
                “The goal isn’t to know everything. It’s to keep getting better at building.”
            </p>
        </div>
    </section>

    {{-- ═══════════ WHAT I BUILD SECTION ═══════════ --}}
    <section id="build" class="section-anchor relative z-10 py-22 px-6 md:px-16 lg:px-28">
        <div class="section-header mb-14 reveal">
            <p class="eyebrow-line">What I Build</p>
            <h2 class="section-title"><span class="shimmer-underline">From Idea to Interface</span></h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <article class="story-card build-card reveal-left p-7 md:p-8">
                <div>
                    <div class="build-icon mb-7">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="14" rx="2"/><path d="M8 21h8M12 18v3"/>
                        </svg>
                    </div>
                    <div class="text-[0.68rem] font-semibold tracking-[0.2em] uppercase text-emerald-400 mb-2">01 · Product</div>
                    <h3 class="text-2xl font-bold text-white tracking-tight mb-3">Web Applications</h3>
                    <p class="text-sm text-white/40 leading-relaxed">
                        Responsive interfaces and functional experiences built around real users, not just screenshots.
                    </p>
                </div>
                <div class="pt-7 mt-7 border-t border-white/[0.06] flex flex-wrap gap-2">
                    <span class="text-[0.7rem] text-white/35 border border-white/[0.07] rounded-full px-3 py-1.5">Responsive UI</span>
                    <span class="text-[0.7rem] text-white/35 border border-white/[0.07] rounded-full px-3 py-1.5">Interaction</span>
                </div>
            </article>

            <article class="story-card build-card reveal p-7 md:p-8">
                <div>
                    <div class="build-icon mb-7">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <ellipse cx="12" cy="5" rx="7" ry="3"/><path d="M5 5v7c0 1.66 3.13 3 7 3s7-1.34 7-3V5M5 12v7c0 1.66 3.13 3 7 3s7-1.34 7-3v-7"/>
                        </svg>
                    </div>
                    <div class="text-[0.68rem] font-semibold tracking-[0.2em] uppercase text-emerald-400 mb-2">02 · Systems</div>
                    <h3 class="text-2xl font-bold text-white tracking-tight mb-3">Full-Stack Systems</h3>
                    <p class="text-sm text-white/40 leading-relaxed">
                        Backend logic, databases, authentication, dashboards, and the frontend pieces that make a product work end to end.
                    </p>
                </div>
                <div class="pt-7 mt-7 border-t border-white/[0.06] flex flex-wrap gap-2">
                    <span class="text-[0.7rem] text-white/35 border border-white/[0.07] rounded-full px-3 py-1.5">Laravel</span>
                    <span class="text-[0.7rem] text-white/35 border border-white/[0.07] rounded-full px-3 py-1.5">MySQL</span>
                </div>
            </article>

            <article class="story-card build-card reveal-right p-7 md:p-8">
                <div>
                    <div class="build-icon mb-7">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="8"/><path d="M8.5 12h7M12 8.5v7"/>
                        </svg>
                    </div>
                    <div class="text-[0.68rem] font-semibold tracking-[0.2em] uppercase text-emerald-400 mb-2">03 · Experience</div>
                    <h3 class="text-2xl font-bold text-white tracking-tight mb-3">Digital Experiences</h3>
                    <p class="text-sm text-white/40 leading-relaxed">
                        UI/UX decisions that aim for clarity, consistency, and a product that feels intentional from first click to last.
                    </p>
                </div>
                <div class="pt-7 mt-7 border-t border-white/[0.06] flex flex-wrap gap-2">
                    <span class="text-[0.7rem] text-white/35 border border-white/[0.07] rounded-full px-3 py-1.5">UI/UX</span>
                    <span class="text-[0.7rem] text-white/35 border border-white/[0.07] rounded-full px-3 py-1.5">Figma</span>
                </div>
            </article>
        </div>
    </section>

    {{-- ═══════════ SKILLS SECTION ═══════════ --}}
    {{-- x-data="techStackFilter()": inisialisasi komponen Alpine untuk filter tab --}}
    <section id="skills" class="section-anchor relative z-10 py-22 px-6 md:px-16 lg:px-28" x-data="techStackFilter()">
        <div class="section-header mb-12 reveal">
            <p class="eyebrow-line">What I Use</p>
            <h2 class="section-title"><span class="shimmer-underline">Tools Behind the Work</span></h2>
        </div>

        {{-- Tombol-tombol filter tab --}}
        <div class="flex flex-wrap justify-center items-center gap-3 mb-10 reveal" style="transition-delay: 0.1s;">
            {{-- x-for: Alpine merender tombol untuk setiap tab dalam array --}}
            <template x-for="tab in tabs" :key="tab.key">
                {{-- @click: ubah activeTab saat diklik, computed property filtered otomatis diperbarui --}}
                {{-- :class: kelas CSS berubah dinamis tergantung apakah tab ini sedang aktif --}}
                <button @click="activeTab = tab.key"
                    :class="activeTab === tab.key
                        ? 'bg-emerald-500 text-[#0d0d0d] border-emerald-500 font-semibold shadow-[0_0_20px_rgba(16,185,129,0.25)]'
                        : 'bg-transparent text-white/40 border-white/10 hover:border-white/20 hover:text-white/60'"
                    class="px-5 py-2 rounded-full text-sm border transition-all duration-300 cursor-pointer"
                    x-text="tab.label"></button>
            </template>
        </div>

        {{-- Daftar kartu skill --}}
        <div class="flex flex-wrap gap-3 stagger-children">
            {{-- x-for: Alpine merender kartu untuk setiap skill dalam array filtered --}}
            <template x-for="(skill, i) in filtered" :key="skill.name">
                {{-- x-transition: animasi masuk dan keluar saat filter tab berubah --}}
                <div x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     :style="'transition-delay:' + (i * 40) + 'ms'" {{-- Tiap kartu lebih lambat 40ms dari sebelumnya --}}
                     class="flex items-center justify-between w-full sm:w-[calc(50%-6px)] lg:w-[calc(33.333%-8px)] px-5 py-4 bg-white/[0.03] border border-white/[0.08] rounded-2xl transition-all duration-300 hover:border-emerald-500/40 hover:bg-emerald-500/[0.03] hover:-translate-y-0.5 hover:shadow-[0_0_30px_rgba(16,185,129,0.08)] cursor-default group">
                    <div class="flex items-center gap-3.5">
                        {{-- :src, :alt, :class: nilai atribut diambil dinamis dari data Alpine --}}
                        <img :src="skill.img" :alt="skill.name" :class="['w-7 h-7', skill.iconClass || '']" loading="lazy" />
                        <span class="text-sm font-semibold text-white/70 group-hover:text-white transition-colors duration-300" x-text="skill.name"></span>
                    </div>
                    {{-- Badge "Advanced" dengan warna hijau --}}
                    <span x-show="skill.level === 'Core' || skill.level === 'Workflow'" class="text-[0.65rem] font-semibold tracking-wider uppercase px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/15" x-text="skill.level"></span>
                    {{-- Badge level lain dengan warna abu, x-cloak menyembunyikan sampai Alpine siap --}}
                    <span x-show="skill.level !== 'Core' && skill.level !== 'Workflow'" x-cloak class="text-[0.65rem] font-semibold tracking-wider uppercase px-2.5 py-1 rounded-full bg-white/[0.05] text-white/40 border border-white/[0.08]" x-text="skill.level"></span>
                </div>
            </template>
        </div>
    </section>

    {{-- ═══════════ PROJECTS SECTION ═══════════ --}}
    <section id="project" class="section-anchor relative z-10 py-22 px-6 md:px-16 lg:px-28">
        <div class="section-header mb-14 reveal">
            <p class="eyebrow-line">Selected Works</p>
            <h2 class="section-title"><span class="shimmer-underline">Featured Projects</span></h2>
        </div>
        {{-- Grid dua kolom di desktop, satu kolom di mobile --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 stagger-children">
            {{-- @forelse: loop Blade dengan fallback otomatis jika koleksi kosong --}}
            @forelse($projects as $project)
            {{-- tilt-card: diproses JavaScript untuk efek miring 3D mengikuti mouse --}}
            <article class="tilt-card group rounded-3xl bg-[#111111] border border-white/[0.05] overflow-hidden hover:border-emerald-500/40 hover:shadow-[0_0_40px_rgba(16,185,129,0.10)]">
                <div class="overflow-hidden aspect-[22/10]">
                    {{-- Jika ada gambar tersimpan: ambil dari Storage Laravel --}}
                    {{-- Jika tidak ada: gunakan gambar placeholder dari picsum.photos --}}
                    <img src="{{ $project->image ? Storage::url($project->image) : 'https://picsum.photos/seed/'.$project->id.'/500/500' }}"
                         alt="{{ $project->title }}"
                         class="w-full h-70 object-cover transition-transform duration-700 ease-out group-hover:scale-105" loading="lazy" />
                </div>
                <div class="p-6 md:p-7">
                    <h3 class="text-lg font-bold text-white tracking-tight mb-2 group-hover:text-emerald-300 transition-colors duration-300">{{ $project->title }}</h3>
                    <p class="text-sm text-white/40 leading-relaxed mb-5 font-light">{{ $project->description }}</p>
                    {{-- Tampilkan tags hanya jika data tags tersedia di database --}}
                    @if($project->tags)
                    <div class="flex flex-wrap items-center gap-2 mb-5">
                        @foreach($project->tags as $tag)
                        <span class="text-[0.7rem] font-medium tracking-wide uppercase text-white/25 bg-white/[0.04] border border-white/[0.06] rounded-full px-3 py-1">{{ $tag }}</span>
                        @endforeach
                    </div>
                    @endif
                    <div class="flex items-center gap-4">
                        {{-- Tombol visit project hanya muncul jika visit_url tersimpan di database --}}
                        @if($project->visit_url)
                        <a href="{{ $project->visit_url }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-400 hover:text-emerald-300 transition-colors duration-300 group/link">
                            <span>Visit Project</span>
                            {{-- Ikon panah diagonal, bergerak sedikit saat hover --}}
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M17 7H7M17 7v10"/></svg>
                        </a>
                        @endif
                        {{-- Tautan GitHub hanya muncul jika github_url tersimpan di database --}}
                        @if($project->github_url)
                        <a href="{{ $project->github_url }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 text-sm font-semibold text-white/40 hover:text-white transition-colors duration-300">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/></svg>
                            GitHub
                        </a>
                        @endif
                    </div>
                </div>
            </article>
            {{-- @empty: konten yang ditampilkan jika belum ada proyek sama sekali --}}
            @empty
            <div class="col-span-2 flex flex-col items-center justify-center py-24 text-center gap-4">
                <div class="empty-project-icon w-16 h-16 rounded-2xl bg-white/[0.03] border border-white/[0.06] flex items-center justify-center mb-2">
                    <svg class="w-7 h-7 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                </div>
                <p class="empty-project-txt text-white/25 text-sm font-light">The showcase is being curated.</p>
                <p class="empty-project-sub text-white/15 text-xs">Real builds, real problems, real lessons — coming next.</p>
            </div>
            @endforelse
        </div>
    </section>

    {{-- ═══════════ HOW I WORK SECTION ═══════════ --}}
    <section id="how-i-work" class="section-anchor relative z-10 py-22 px-6 md:px-16 lg:px-28">
        <div class="section-header mb-14 reveal">
            <p class="eyebrow-line">How I Work</p>
            <h2 class="section-title"><span class="shimmer-underline">Build. Debug. Improve.</span></h2>
            <p class="mt-5 max-w-2xl text-center text-sm md:text-base text-white/35 leading-relaxed">
                My workflow is iterative: understand first, explore the options, build the right thing, then make it better.
            </p>
        </div>

        <div class="process-wrap">
            <div class="process-line hidden md:block"></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <article class="process-card reveal-left">
                    <div class="flex items-center justify-between mb-7"><span class="process-dot">01</span><span class="text-[0.65rem] uppercase tracking-[0.16em] text-white/20">Start</span></div>
                    <h3 class="text-lg font-bold text-white mb-2">Understand</h3>
                    <p class="text-sm text-white/35 leading-relaxed">Start with the problem before choosing the stack.</p>
                </article>
                <article class="process-card reveal">
                    <div class="flex items-center justify-between mb-7"><span class="process-dot">02</span><span class="text-[0.65rem] uppercase tracking-[0.16em] text-white/20">Explore</span></div>
                    <h3 class="text-lg font-bold text-white mb-2">Explore</h3>
                    <p class="text-sm text-white/35 leading-relaxed">Research, compare, prototype, and learn.</p>
                </article>
                <article class="process-card reveal">
                    <div class="flex items-center justify-between mb-7"><span class="process-dot">03</span><span class="text-[0.65rem] uppercase tracking-[0.16em] text-white/20">Create</span></div>
                    <h3 class="text-lg font-bold text-white mb-2">Build</h3>
                    <p class="text-sm text-white/35 leading-relaxed">Turn the idea into something usable.</p>
                </article>
                <article class="process-card reveal">
                    <div class="flex items-center justify-between mb-7"><span class="process-dot">04</span><span class="text-[0.65rem] uppercase tracking-[0.16em] text-white/20">Learn</span></div>
                    <h3 class="text-lg font-bold text-white mb-2">Debug</h3>
                    <p class="text-sm text-white/35 leading-relaxed">Investigate what breaks and understand why.</p>
                </article>
                <article class="process-card reveal-right">
                    <div class="flex items-center justify-between mb-7"><span class="process-dot">05</span><span class="text-[0.65rem] uppercase tracking-[0.16em] text-white/20">Ship</span></div>
                    <h3 class="text-lg font-bold text-white mb-2">Ship</h3>
                    <p class="text-sm text-white/35 leading-relaxed">Polish the details and make it real.</p>
                </article>
            </div>
        </div>

        <div class="reveal mt-5 rounded-2xl border border-white/[0.06] bg-white/[0.018] px-6 py-6 md:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <p class="text-sm md:text-base text-white/55 leading-relaxed max-w-3xl">
                    <span class="text-white/85 font-semibold">AI is part of the workflow — not a replacement for thinking.</span>
                    I use it to move faster, ask better questions, and explore more possibilities while keeping judgment, direction, and final decisions human.
                </p>
                <span class="shrink-0 inline-flex items-center gap-2 text-[0.68rem] uppercase tracking-[0.18em] text-emerald-400 border border-emerald-500/15 bg-emerald-500/[0.05] rounded-full px-3.5 py-2">
                    Human in the loop
                </span>
            </div>
        </div>
    </section>

    {{-- ═══════════ CURRENTLY EXPLORING SECTION ═══════════ --}}
    <section id="exploring" class="section-anchor relative z-10 py-22 px-6 md:px-16 lg:px-28">
        <div class="section-header mb-12 reveal">
            <p class="eyebrow-line">Currently Exploring</p>
            <h2 class="section-title"><span class="shimmer-underline">Next, Not Done</span></h2>
            <p class="mt-5 max-w-2xl text-center text-sm md:text-base text-white/35 leading-relaxed">
                The direction keeps evolving. The habit stays the same: learn, build, reflect, repeat.
            </p>
        </div>

        <div class="explore-panel reveal-scale max-w-5xl mx-auto p-7 md:p-10">
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-[1.05fr_0.95fr] gap-8 lg:gap-12 items-center">
                <div>
                    <div class="flex items-center gap-2 text-[0.68rem] uppercase tracking-[0.2em] text-emerald-400 mb-4">
                        <span class="dot-pulse !w-2 !h-2"></span>
                        In progress
                    </div>
                    <h3 class="text-2xl md:text-3xl font-bold text-white tracking-tight mb-4">Curiosity is still part of the plan.</h3>
                    <p class="text-sm md:text-base text-white/40 leading-relaxed max-w-xl">
                        I’m exploring better ways to build products, work with AI, grow technically, and turn the things I learn into opportunities that are actually useful in the real world.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2.5 lg:justify-end">
                    @foreach(['AI-assisted development','AI training & data work','Full-stack architecture','Better UI/UX','Freelance opportunities','Products people actually use'] as $item)
                        <span class="explore-chip">{{ $item }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════ CONTACT SECTION ═══════════ --}}
    <section id="contact" class="section-anchor relative z-10 py-22 px-6 md:px-16 lg:px-28">
        <div class="section-header mb-16 reveal">
            <p class="eyebrow-line">Contact</p>
            <h2 class="section-title"><span class="shimmer-underline">Let's Talk</span></h2>
        </div>
        {{-- Grid: 2 kolom untuk info kontak dan 3 kolom untuk form di layar besar --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 lg:gap-20">

            {{-- Kolom kiri: informasi kontak --}}
            <div class="lg:col-span-2 flex flex-col justify-between">
                {{-- reveal-left: elemen muncul dari sisi kiri saat di-scroll --}}
                <div class="reveal-left">
                    <h3 class="text-[clamp(1.5rem,3.5vw,2.4rem)] font-extrabold tracking-tight text-white leading-tight mb-6">Have something<br/>worth building?</h3>
                    <p class="text-sm text-white/40 leading-relaxed font-light mb-10 max-w-sm">
                        I'm open to thoughtful projects, collaborations, and conversations about technology, design, and new ideas.
                    </p>
                </div>
                {{-- contact-stagger: tiap item kontak muncul bergantian dari kiri --}}
                <div class="flex flex-col gap-3 contact-stagger">
                    {{-- Klik tautan email langsung membuka aplikasi email --}}
                    <a href="mailto:muhammadputra.dev@gmail.com" class="reveal-left group flex items-center gap-3.5 px-5 py-3.5 bg-white/[0.03] border border-white/[0.08] rounded-xl transition-all duration-300 hover:border-emerald-500/40 hover:bg-emerald-500/[0.03]">
                        <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-white/[0.04] border border-white/[0.06] group-hover:bg-emerald-500/10 group-hover:border-emerald-500/20 transition-all duration-300">
                            <svg class="w-4 h-4 text-white/40 group-hover:text-emerald-400 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 6L2 7"/></svg>
                        </span>
                        <div>
                            <p class="text-[0.65rem] text-white/25 uppercase tracking-wider font-medium mb-0.5">Email</p>
                            <p class="text-sm text-white/60 group-hover:text-white/90 transition-colors duration-300 font-medium">muhammadputra.dev@gmail.com</p>
                        </div>
                    </a>
                    {{-- Klik tautan lokasi membuka Google Maps --}}
                    <a href="https://maps.google.com/?q=Kalimantan+Selatan" target="_blank" rel="noopener" class="reveal-left group flex items-center gap-3.5 px-5 py-3.5 bg-white/[0.03] border border-white/[0.08] rounded-xl transition-all duration-300 hover:border-emerald-500/40 hover:bg-emerald-500/[0.03]">
                        <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-white/[0.04] border border-white/[0.06] group-hover:bg-emerald-500/10 group-hover:border-emerald-500/20 transition-all duration-300">
                            <svg class="w-4 h-4 text-white/40 group-hover:text-emerald-400 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
                        </span>
                        <div>
                            <p class="text-[0.65rem] text-white/25 uppercase tracking-wider font-medium mb-0.5">Location</p>
                            <p class="text-sm text-white/60 group-hover:text-white/90 transition-colors duration-300 font-medium">Kalimantan Selatan, Indonesia</p>
                        </div>
                    </a>
                    {{-- Klik tautan WhatsApp membuka chat WA ke nomor yang sudah ditentukan --}}
                    <a href="https://wa.me/6282250097049" target="_blank" rel="noopener" class="reveal-left group flex items-center gap-3.5 px-5 py-3.5 bg-white/[0.03] border border-white/[0.08] rounded-xl transition-all duration-300 hover:border-emerald-500/40 hover:bg-emerald-500/[0.03]">
                        <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-white/[0.04] border border-white/[0.06] group-hover:bg-emerald-500/10 group-hover:border-emerald-500/20 transition-all duration-300">
                            <svg class="w-4 h-4 text-white/40 group-hover:text-emerald-400 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21l1.65-3.8a9 9 0 1 1 3.4 2.9L3 21"/><path d="M9 10a.5.5 0 0 0 1 0V9a.5.5 0 0 0-1 0v1zm4 0a.5.5 0 0 0 1 0V9a.5.5 0 0 0-1 0v1z"/></svg>
                        </span>
                        <div>
                            <p class="text-[0.65rem] text-white/25 uppercase tracking-wider font-medium mb-0.5">WhatsApp</p>
                            <p class="text-sm text-white/60 group-hover:text-white/90 transition-colors duration-300 font-medium">+62 822 5009 7049</p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Kolom kanan: form kontak --}}
            {{-- x-data="contactForm()": state form dikelola oleh Alpine.js --}}
            {{-- @submit.prevent: mencegah reload halaman, lalu jalankan fungsi submitForm() --}}
            <div class="lg:col-span-3 reveal-right">
                <form x-data="contactForm()" @submit.prevent="submitForm()"
                    class="p-6 sm:p-8 bg-white/[0.02] border border-white/[0.06] rounded-3xl space-y-5">
                    <div>
                        <label class="block text-xs font-semibold text-white/40 uppercase tracking-wider mb-2.5">Name</label>
                        {{-- x-model: menghubungkan input ke state form.name secara dua arah --}}
                        {{-- :disabled: input dikunci selama proses pengiriman berlangsung --}}
                        <input type="text" x-model="form.name" placeholder="Nama lengkap kamu" required :disabled="isSubmitting"
                            class="w-full bg-white/[0.02] border border-white/[0.06] rounded-xl px-5 py-4 text-white text-sm placeholder-white/25 outline-none transition-all duration-300 focus:bg-white/[0.04] focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/50 disabled:opacity-40 disabled:cursor-not-allowed" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-white/40 uppercase tracking-wider mb-2.5">Email</label>
                        <input type="email" x-model="form.email" placeholder="email@gmail.com" required :disabled="isSubmitting"
                            class="w-full bg-white/[0.02] border border-white/[0.06] rounded-xl px-5 py-4 text-white text-sm placeholder-white/25 outline-none transition-all duration-300 focus:bg-white/[0.04] focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/50 disabled:opacity-40 disabled:cursor-not-allowed" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-white/40 uppercase tracking-wider mb-2.5">Message</label>
                        <textarea x-model="form.message" rows="5" placeholder="Ceritakan proyek atau ide kamu..." required :disabled="isSubmitting"
                            class="w-full bg-white/[0.02] border border-white/[0.06] rounded-xl px-5 py-4 text-white text-sm placeholder-white/25 outline-none transition-all duration-300 focus:bg-white/[0.04] focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/50 resize-none disabled:opacity-40 disabled:cursor-not-allowed"></textarea>
                    </div>
                    {{-- Tombol submit, dinonaktifkan saat proses pengiriman --}}
                    <button type="submit" :disabled="isSubmitting"
                        class="w-full bg-emerald-500 text-[#0d0d0d] font-bold text-sm tracking-wide rounded-xl px-8 py-4 transition-all duration-300 flex justify-center items-center gap-2.5 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed hover:bg-emerald-400 hover:shadow-[0_0_20px_rgba(16,185,129,0.3)] disabled:hover:shadow-none">
                        {{-- Ikon spinner berputar, hanya terlihat saat isSubmitting bernilai true --}}
                        <svg x-show="isSubmitting" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                        <span x-show="!isSubmitting">Kirim Pesan</span>
                        {{-- x-cloak: elemen ini disembunyikan sampai Alpine selesai diinisialisasi --}}
                        <span x-show="isSubmitting" x-cloak>Sending...</span>
                    </button>
                    <p class="text-center text-[0.7rem] text-white/20 pt-1">Biasanya saya balas dalam 24 jam.</p>
                </form>
            </div>
        </div>
    </section>

    {{-- ═══════════ FOOTER ═══════════ --}}
    {{-- reveal: footer muncul dengan animasi saat di-scroll --}}
    <footer class="reveal relative z-10 border-t border-white/[0.05] py-10 px-6 md:px-16 lg:px-28">
        <div class="flex flex-col items-center gap-6 md:flex-row md:justify-between">
            <div class="flex flex-col items-center md:items-start gap-1">
                <p class="text-xs text-white/30 font-light">&copy; 2026 Putra<span class="text-emerald-500/60">Dev</span>. All rights reserved.</p>
                <p class="text-[0.65rem] text-white/15 font-light flex items-center gap-1.5">
                    Built with curiosity by Muhammad Putra
                    <svg class="w-2.5 h-2.5 text-emerald-500/50" viewBox="0 0 12 12" fill="currentColor"><path d="M6 1.5C4 1.5 2.5 3 2.5 5c0 3.5 3.5 5.5 3.5 5.5s3.5-2 3.5-5.5C9.5 3 8 1.5 6 1.5z"/></svg>
                </p>
            </div>
            {{-- Ikon sosial media dengan animasi naik-turun yang berbeda-beda --}}
            <div class="flex items-center gap-2">
                {{-- social-float-1/2/3: tiap ikon punya kecepatan dan jeda animasi berbeda --}}
                <a href="https://github.com/Putra-pro-max" target="_blank" rel="noopener"
                   class="social-float-1 group flex items-center justify-center w-10 h-10 rounded-xl border border-white/[0.07] hover:border-emerald-500/40 bg-white/[0.02] hover:bg-emerald-500/[0.05] transition-all duration-300 hover:-translate-y-1" aria-label="GitHub">
                    <svg class="w-5 h-5 text-white/50 group-hover:text-emerald-400 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/></svg>
                </a>
                <a href="https://www.tiktok.com/@franns.xo" target="_blank" rel="noopener"
                   class="social-float-2 group flex items-center justify-center w-10 h-10 rounded-xl border border-white/[0.07] hover:border-emerald-500/40 bg-white/[0.02] hover:bg-emerald-500/[0.05] transition-all duration-300 hover:-translate-y-1" aria-label="TikTok">
                    <svg class="w-5 h-5 text-white/50 group-hover:text-emerald-400 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/></svg>
                </a>
                <a href="https://www.instagram.com/b.coenn/" target="_blank" rel="noopener"
                   class="social-float-3 group flex items-center justify-center w-10 h-10 rounded-xl border border-white/[0.07] hover:border-emerald-500/40 bg-white/[0.02] hover:bg-emerald-500/[0.05] transition-all duration-300 hover:-translate-y-1" aria-label="Instagram">
                    <svg class="w-5 h-5 text-white/50 group-hover:text-emerald-400 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="5"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                </a>
            </div>
        </div>
    </footer>

    {{-- Indikator scroll: dua panah bouncing, muncul saat pengguna diam dan tersembunyi saat dekat footer --}}
    <div class="scroll-indicator fixed bottom-10 left-1/2 -translate-x-1/2 flex-col items-center gap-0.5 transition-opacity duration-500" style="display:none; opacity:0;">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/25" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/12 -mt-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
    </div>

    {{-- Tombol kembali ke atas, onclick langsung memanggil Web API untuk scroll ke posisi 0 --}}
    <button id="backToTop" aria-label="Back to top" onclick="window.scrollTo({top:0,behavior:'smooth'})">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 15l-6-6-6 6"/>
        </svg>
    </button>

    {{-- ═══════════ JAVASCRIPT ═══════════ --}}

    {{-- Script 1: Logika buka-tutup menu mobile --}}
    <script>
        /* DOMContentLoaded: jalankan kode setelah seluruh HTML selesai dimuat */
        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.getElementById('menuToggle');
            const menu   = document.getElementById('mobileMenu');
            if (!toggle || !menu) return; /* Keluar jika elemen tidak ditemukan */

            toggle.addEventListener('click', () => {
                /* classList.toggle('open'): tambahkan class jika belum ada, hapus jika sudah ada */
                const isOpen = menu.classList.toggle('open');
                toggle.classList.toggle('hamburger-active', isOpen); /* Animasi hamburger menjadi tanda X */
                toggle.setAttribute('aria-expanded', isOpen);        /* Perbarui atribut aksesibilitas */
            });

            /* Tutup menu secara otomatis saat salah satu tautan navigasi diklik */
            menu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    menu.classList.remove('open');
                    toggle.classList.remove('hamburger-active');
                    toggle.setAttribute('aria-expanded', 'false');
                });
            });

            /* Tutup menu saat pengguna mengklik area di luar menu atau tombol toggle */
            document.addEventListener('click', (e) => {
                if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.remove('open');
                    toggle.classList.remove('hamburger-active');
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>

    {{-- Memuat Alpine.js dari CDN, defer berarti diproses setelah HTML selesai di-parse --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    {{-- Script 2: Komponen Alpine.js --}}
    <script>
        /* ── Filter Tech Stack ── */
        function techStackFilter() {
            return {
                activeTab: 'all', /* Tab yang sedang aktif */
                tabs: [
                    { key: 'all',      label: 'All Stack' },
                    { key: 'frontend', label: 'Frontend'  },
                    { key: 'backend',  label: 'Backend'   },
                    { key: 'tools',    label: 'Tools'     },
                    { key: 'ai-team',  label: 'AI Workflow'  }
                ],
                skills: [
                    /* Setiap skill memiliki: name, img (URL CDN), level, cat (kategori), dan iconClass opsional */
                    { name: 'HTML',         img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/html5/html5-original.svg',             level: 'Core',      cat: 'frontend' },
                    { name: 'CSS',          img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/css3/css3-original.svg',               level: 'Core',      cat: 'frontend' },
                    { name: 'JavaScript',   img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg',   level: 'Core',      cat: 'frontend' },
                    { name: 'Tailwind CSS', img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tailwindcss/tailwindcss-original.svg', level: 'Core',      cat: 'frontend' },
                    { name: 'Bootstrap',    img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/bootstrap/bootstrap-original.svg',     level: 'Working',   cat: 'frontend' },
                    { name: 'Alpine.js',    img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/alpinejs/alpinejs-original.svg',       level: 'Working',   cat: 'frontend' },
                    { name: 'PHP',          img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg',                 level: 'Core',      cat: 'backend'  },
                    { name: 'Laravel',      img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/laravel/laravel-original.svg',         level: 'Core',      cat: 'backend'  },
                    { name: 'MySQL',        img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg',             level: 'Core',      cat: 'backend'  },
                    { name: 'Node.js',      img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nodejs/nodejs-original.svg',           level: 'Working',   cat: 'backend'  },
                    { name: 'Vite',         img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/vitejs/vitejs-original.svg',           level: 'Working',   cat: 'tools'    },
                    { name: 'Figma',        img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/figma/figma-original.svg',             level: 'Core',      cat: 'tools'    },
                    { name: 'GitHub',       img: 'https://unpkg.com/@lobehub/icons-static-svg@latest/icons/github.svg',                    level: 'Core',      cat: 'tools',    iconClass: 'icon-dark-bg' },
                    { name: 'VS Code',      img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/vscode/vscode-original.svg',           level: 'Core',      cat: 'tools'    },
                    { name: 'Z.ai',         img: 'https://unpkg.com/@lobehub/icons-static-svg@latest/icons/zhipu-color.svg',                level: 'Workflow',  cat: 'ai-team'  },
                    { name: 'Gemini',       img: 'https://unpkg.com/@lobehub/icons-static-svg@latest/icons/gemini-color.svg',              level: 'Workflow',  cat: 'ai-team'  },
                    { name: 'Claude',       img: 'https://unpkg.com/@lobehub/icons-static-svg@latest/icons/claude-color.svg',              level: 'Workflow',  cat: 'ai-team',  iconClass: 'icon-light-bg' },
                    { name: 'GPT',          img: 'https://unpkg.com/@lobehub/icons-static-svg@latest/icons/openai.svg',                    level: 'Workflow',  cat: 'ai-team',  iconClass: 'icon-dark-bg' },
                ],
                /* Computed property: dihitung ulang otomatis setiap activeTab berubah */
                get filtered() {
                    return this.activeTab === 'all'
                        ? this.skills                                          /* Tampilkan semua skill */
                        : this.skills.filter(s => s.cat === this.activeTab);  /* Filter berdasarkan kategori */
                }
            }
        }

        /* ── Form Kontak ── */
        function contactForm() {
            return {
                isSubmitting: false,                              /* Status loading pengiriman */
                form: { name: '', email: '', message: '' },      /* Data form yang terikat ke input */
                submitForm() {
                    if (!this.form.name || !this.form.email || !this.form.message) return; /* Validasi sederhana */
                    this.isSubmitting = true; /* Aktifkan status loading */
                    fetch('/contact', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            /* Kirim token CSRF yang diambil dari meta tag agar Laravel tidak menolak request */
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(this.form) /* Ubah data form menjadi format JSON */
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.form = { name: '', email: '', message: '' }; /* Kosongkan form setelah berhasil */
                            alert('Pesan berhasil dikirim!');
                        }
                    })
                    .catch(() => { alert('Gagal mengirim pesan.'); }) /* Tampilkan pesan jika terjadi error */
                    .finally(() => { this.isSubmitting = false; });   /* Matikan status loading bagaimanapun hasilnya */
                }
            }
        }
    </script>

    {{-- Script 3: Animasi dan Interaksi --}}
    <script>
        /* ── IntersectionObserver untuk Scroll Reveal ──
           Memantau apakah elemen sudah masuk area pandang (viewport).
           threshold 0.12: pemicu diaktifkan saat 12% elemen sudah terlihat.
           rootMargin -40px: pemicu diaktifkan 40px sebelum elemen benar-benar terlihat penuh. */
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible'); /* Tambah class visible sehingga transisi CSS berjalan */
                    revealObserver.unobserve(entry.target); /* Berhenti memantau untuk menghemat performa */
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        /* Daftarkan semua elemen dengan kelas reveal ke observer */
        document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale').forEach(el => {
            revealObserver.observe(el);
        });

        /* ── Stagger Anak Elemen ──
           Setiap anak elemen diberi opacity 0 dan posisi bergeser ke bawah,
           lalu muncul satu per satu dengan jeda waktu berbeda saat parent masuk viewport. */
        document.querySelectorAll('.stagger-children').forEach(container => {
            const children = container.children;
            for (let i = 0; i < children.length; i++) {
                const child = children[i];
                child.style.opacity = '0';
                child.style.transform = 'translateY(24px)';
                /* Delay makin besar untuk anak ke-n: anak pertama 0ms, kedua 80ms, dst */
                child.style.transition = `opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1) ${i * 80}ms, transform 0.6s cubic-bezier(0.16, 1, 0.3, 1) ${i * 80}ms`;
            }
            const staggerObs = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        Array.from(entry.target.children).forEach(child => {
                            child.style.opacity = '1';
                            child.style.transform = 'translateY(0)'; /* Setiap anak muncul sesuai delay masing-masing */
                        });
                        staggerObs.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.08, rootMargin: '0px 0px -20px 0px' });
            staggerObs.observe(container);
        });

        /* ── Stagger Item Kontak ──
           Tiga item kontak (email, lokasi, WA) muncul satu per satu dari sisi kiri. */
        document.querySelectorAll('.contact-stagger').forEach(container => {
            const children = container.children;
            for (let i = 0; i < children.length; i++) {
                const child = children[i];
                child.style.opacity = '0';
                child.style.transform = 'translateX(-20px)'; /* Bergeser dari kiri */
                child.style.transition = `opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1) ${i * 100}ms, transform 0.6s cubic-bezier(0.16, 1, 0.3, 1) ${i * 100}ms`;
            }
            const cObs = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        Array.from(entry.target.children).forEach(child => {
                            child.style.opacity = '1';
                            child.style.transform = 'translateX(0)';
                        });
                        cObs.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            cObs.observe(container);
        });

        /* ── Animasi Counter Statistik ──
           Angka dihitung naik dari 0 ke nilai target saat elemen masuk viewport.
           data-count: nilai target angka
           data-suffix: teks yang ditambahkan setelah angka, misal "+" */
        document.querySelectorAll('[data-count]').forEach(el => {
            const target = parseInt(el.dataset.count);
            const suffix = el.dataset.suffix || '';
            let counted = false; /* Mencegah animasi diputar ulang */
            const countObs = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !counted) {
                        counted = true;
                        let current = 0;
                        const duration = 1200; /* Total durasi animasi dalam milidetik */
                        const step = target / (duration / 16); /* Kenaikan per frame (sekitar 60fps = 16ms per frame) */
                        const counter = setInterval(() => {
                            current += step;
                            if (current >= target) { current = target; clearInterval(counter); }
                            el.textContent = Math.round(current) + suffix; /* Perbarui teks di DOM setiap frame */
                        }, 16);
                        countObs.unobserve(el);
                    }
                });
            }, { threshold: 0.5 });
            countObs.observe(el);
        });

        /* ── Efek Miring 3D pada Kartu Proyek ──
           Saat mouse bergerak di atas kartu, hitung sudut rotasi X dan Y
           berdasarkan posisi kursor relatif terhadap pusat kartu. */
        document.querySelectorAll('.tilt-card').forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();        /* Dapatkan posisi dan ukuran kartu di layar */
                const x = e.clientX - rect.left;                 /* Posisi X kursor di dalam kartu */
                const y = e.clientY - rect.top;                  /* Posisi Y kursor di dalam kartu */
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                /* Rotasi maksimal ±2.5 derajat, proporsional dengan jarak kursor dari tengah kartu */
                const rotateX = ((y - centerY) / centerY) * -2.5;
                const rotateY = ((x - centerX) / centerX) * 2.5;
                card.style.transform = `perspective(800px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.01)`;
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(800px) rotateX(0) rotateY(0) scale(1)'; /* Kembalikan ke posisi normal */
            });
        });

        /* ── Efek Parallax pada Cahaya Latar ──
           Tiga glow bergerak mengikuti mouse tetapi dengan kecepatan lebih lambat,
           menciptakan kesan kedalaman (depth). requestAnimationFrame memastikan
           pergerakan sinkron dengan refresh rate monitor sehingga terasa halus. */
        const glowTl     = document.getElementById('glowTl');
        const glowBr     = document.getElementById('glowBr');
        const glowCenter = document.getElementById('glowCenter');
        let rafId = null;

        document.addEventListener('mousemove', (e) => {
            /* Normalisasi posisi mouse ke rentang -1 sampai 1 */
            const mouseX = (e.clientX / window.innerWidth - 0.5) * 2;
            const mouseY = (e.clientY / window.innerHeight - 0.5) * 2;
            if (!rafId) { /* Cegah penumpukan requestAnimationFrame yang tidak perlu */
                rafId = requestAnimationFrame(() => {
                    /* Tiap glow bergerak dengan jarak berbeda untuk efek kedalaman */
                    if (glowTl)     glowTl.style.transform     = `translate(${mouseX * 30}px, ${mouseY * 25}px)`;
                    if (glowBr)     glowBr.style.transform     = `translate(${mouseX * -25}px, ${mouseY * -30}px)`;
                    if (glowCenter) glowCenter.style.transform = `translate(calc(-50% + ${mouseX * 15}px), calc(-50% + ${mouseY * 15}px))`;
                    rafId = null;
                });
            }
        });

        /* ── Indikator Scroll ──
           Muncul setelah pengguna diam selama 1 detik,
           dan tersembunyi saat footer sudah terlihat di layar. */
        const indicator = document.querySelector('.scroll-indicator');
        const footer     = document.querySelector('footer');
        let scrollTimer  = null;

        function sembunyikanIndikator() {
            indicator.style.opacity = '0';
            setTimeout(() => { indicator.style.display = 'none'; }, 500); /* Tunggu animasi fade selesai */
        }
        function tampilkanIndikator() {
            indicator.style.display = 'flex';
            /* Double requestAnimationFrame memastikan display flex sudah aktif sebelum opacity diubah */
            requestAnimationFrame(() => { requestAnimationFrame(() => { indicator.style.opacity = '1'; }); });
        }

        if (indicator && footer) {
            if (window.innerWidth >= 768) tampilkanIndikator(); /* Hanya tampilkan di layar desktop */
            window.addEventListener('scroll', () => {
                if (window.innerWidth < 768) return;
                const footerTerlihat = footer.getBoundingClientRect().top < window.innerHeight;
                if (footerTerlihat) { clearTimeout(scrollTimer); sembunyikanIndikator(); return; }
                sembunyikanIndikator();
                clearTimeout(scrollTimer);
                /* Tampilkan lagi setelah 1 detik tidak ada scroll */
                scrollTimer = setTimeout(() => {
                    if (footer.getBoundingClientRect().top >= window.innerHeight) tampilkanIndikator();
                }, 1000);
            });
        }

        /* ── Tombol Kembali ke Atas ──
           Muncul saat pengguna sudah scroll lebih dari 400 piksel,
           dan menghilang saat kembali ke bagian atas halaman. */
        const backToTop = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 400) backToTop.classList.add('visible');
            else backToTop.classList.remove('visible');
        });

        /* ── Toggle Tema Gelap / Terang ──
           Preferensi tema disimpan di localStorage agar tetap tersimpan saat halaman direfresh. */
        const themeToggle = document.getElementById('themeToggle');
        const iconSun     = document.getElementById('iconSun');
        const iconMoon    = document.getElementById('iconMoon');
        const html        = document.documentElement; /* Referensi ke elemen <html> */

        /* Terapkan tema yang tersimpan saat halaman pertama kali dibuka */
        if (localStorage.getItem('theme') === 'light') {
            html.classList.add('light');
            iconSun.classList.add('hidden');
            iconMoon.classList.remove('hidden');
        }

        themeToggle.addEventListener('click', () => {
            const isLight = html.classList.toggle('light'); /* Tambah atau hapus class .light pada <html> */
            iconSun.classList.toggle('hidden', isLight);    /* Sembunyikan ikon matahari di light mode */
            iconMoon.classList.toggle('hidden', !isLight);  /* Sembunyikan ikon bulan di dark mode */
            localStorage.setItem('theme', isLight ? 'light' : 'dark'); /* Simpan pilihan pengguna */
        });
    </script>
</body>
</html>