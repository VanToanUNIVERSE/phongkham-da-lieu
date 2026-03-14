<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Phòng Khám Da Liễu - Chuyên Sâu & Tận Tâm</title>
    <meta name="description" content="Phòng khám da liễu chuyên nghiệp, đội ngũ bác sĩ giàu kinh nghiệm. Đặt lịch khám trực tuyến nhanh chóng, tiện lợi.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; color: #1e293b; background: #fff; }

        /* NAV */
        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            background: rgba(255,255,255,0.95); backdrop-filter: blur(12px);
            border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 48px; height: 68px;
            transition: box-shadow 0.3s;
        }
        nav.scrolled { box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .nav-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .nav-logo {
            width: 38px; height: 38px; background: linear-gradient(135deg, #0ea5e9, #6366f1);
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
        }
        .nav-logo svg { width: 22px; height: 22px; stroke: white; }
        .nav-title { font-size: 1rem; font-weight: 700; color: #0f172a; line-height: 1.2; }
        .nav-title small { font-size: 0.65rem; font-weight: 500; color: #64748b; display: block; }
        .nav-links { display: flex; align-items: center; gap: 32px; }
        .nav-links a { text-decoration: none; font-size: 0.875rem; font-weight: 500; color: #475569; transition: color 0.2s; }
        .nav-links a:hover { color: #0ea5e9; }
        .btn-booking-nav {
            background: linear-gradient(135deg, #0ea5e9, #6366f1); color: white;
            padding: 9px 22px; border-radius: 10px; text-decoration: none;
            font-size: 0.875rem; font-weight: 600; transition: opacity 0.2s, transform 0.2s;
        }
        .btn-booking-nav:hover { opacity: 0.9; transform: translateY(-1px); color: white; }
        .login-link { color: #64748b; font-size: 0.81rem; font-weight: 500; }

        /* HERO */
        .hero {
            min-height: 100vh; padding-top: 68px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e8f4fd 40%, #eef2ff 100%);
            display: flex; align-items: center; position: relative; overflow: hidden;
        }
        .hero::before {
            content: ''; position: absolute; top: -10%; right: -5%;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(14,165,233,0.12) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero::after {
            content: ''; position: absolute; bottom: -10%; left: -5%;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(99,102,241,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero-inner {
            max-width: 1200px; margin: 0 auto; padding: 80px 48px;
            display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: center;
            position: relative; z-index: 1;
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(14,165,233,0.1); color: #0284c7;
            padding: 6px 14px; border-radius: 999px; font-size: 0.78rem; font-weight: 600;
            border: 1px solid rgba(14,165,233,0.2); margin-bottom: 20px;
        }
        .hero-badge span { font-size: 1rem; }
        h1.hero-title {
            font-size: 3rem; font-weight: 800; line-height: 1.15; color: #0f172a; margin-bottom: 20px;
        }
        h1.hero-title .highlight {
            background: linear-gradient(135deg, #0ea5e9, #6366f1);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .hero-desc { font-size: 1.05rem; color: #475569; line-height: 1.75; margin-bottom: 36px; }
        .hero-ctas { display: flex; gap: 16px; flex-wrap: wrap; }
        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9, #6366f1); color: white;
            padding: 14px 32px; border-radius: 12px; text-decoration: none;
            font-size: 0.95rem; font-weight: 700; transition: all 0.25s;
            box-shadow: 0 4px 16px rgba(14,165,233,0.35);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(14,165,233,0.4); color: white; }
        .btn-secondary {
            background: white; color: #0ea5e9; padding: 14px 32px; border-radius: 12px;
            text-decoration: none; font-size: 0.95rem; font-weight: 600;
            border: 2px solid #e0f2fe; transition: all 0.25s;
        }
        .btn-secondary:hover { border-color: #0ea5e9; background: #f0f9ff; color: #0284c7; }
        .hero-stats {
            display: flex; gap: 32px; margin-top: 40px; padding-top: 36px;
            border-top: 1px solid rgba(14,165,233,0.15);
        }
        .stat-item { text-align: left; }
        .stat-number { font-size: 1.75rem; font-weight: 800; color: #0f172a; line-height: 1; }
        .stat-label { font-size: 0.8rem; color: #64748b; font-weight: 500; margin-top: 4px; }
        /* Hero card floating */
        .hero-card {
            background: white; border-radius: 24px; padding: 40px;
            box-shadow: 0 20px 60px rgba(14,165,233,0.12), 0 4px 16px rgba(0,0,0,0.06);
            position: relative;
        }
        .hero-card-title { font-size: 1.2rem; font-weight: 700; color: #0f172a; margin-bottom: 6px; }
        .hero-card-sub { font-size: 0.85rem; color: #64748b; margin-bottom: 28px; }
        .floating-badge {
            position: absolute; top: -14px; right: 24px;
            background: linear-gradient(135deg, #0ea5e9, #6366f1);
            color: white; font-size: 0.75rem; font-weight: 700;
            padding: 6px 14px; border-radius: 999px;
        }
        .quick-form { display: flex; flex-direction: column; gap: 14px; }
        .quick-form label { font-size: 0.8rem; font-weight: 600; color: #374151; margin-bottom: 4px; display: block; }
        .quick-form label .req { color: #ef4444; }
        .quick-form input, .quick-form select {
            width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: 0.9rem; font-family: 'Inter', sans-serif;
            transition: border-color 0.2s;
        }
        .quick-form input:focus, .quick-form select:focus { outline: none; border-color: #0ea5e9; }
        .row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .btn-submit {
            width: 100%; padding: 13px; background: linear-gradient(135deg, #0ea5e9, #6366f1);
            color: white; border: none; border-radius: 12px; font-size: 0.95rem;
            font-weight: 700; cursor: pointer; font-family: 'Inter', sans-serif;
            transition: all 0.25s; box-shadow: 0 4px 14px rgba(14,165,233,0.3); margin-top: 4px;
        }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(14,165,233,0.4); }
        .btn-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        /* SECTIONS */
        section { padding: 96px 48px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .section-badge {
            display: inline-block; background: #f0f9ff; color: #0284c7;
            font-size: 0.78rem; font-weight: 700; padding: 5px 14px; border-radius: 999px;
            border: 1px solid #bae6fd; margin-bottom: 16px; letter-spacing: 0.05em; text-transform: uppercase;
        }
        .section-title { font-size: 2.25rem; font-weight: 800; color: #0f172a; line-height: 1.2; margin-bottom: 16px; }
        .section-desc { font-size: 1.05rem; color: #64748b; line-height: 1.7; max-width: 560px; }
        .section-header { text-align: center; margin-bottom: 64px; }
        .section-header .section-desc { margin: 0 auto; }

        /* FEATURES */
        #ve-chung { background: white; }
        .features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px; }
        .feature-card {
            background: #f8fafc; border-radius: 20px; padding: 32px;
            border: 1px solid #e2e8f0; transition: all 0.3s; text-align: left;
        }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 12px 36px rgba(14,165,233,0.1); border-color: #bae6fd; }
        .feature-icon {
            width: 52px; height: 52px; border-radius: 14px; display: flex;
            align-items: center; justify-content: center; margin-bottom: 20px; font-size: 1.5rem;
        }
        .feature-card h3 { font-size: 1.05rem; font-weight: 700; color: #0f172a; margin-bottom: 10px; }
        .feature-card p { font-size: 0.875rem; color: #64748b; line-height: 1.65; }

        /* SERVICES */
        #dich-vu { background: linear-gradient(135deg, #f8faff 0%, #f0f9ff 100%); }
        .services-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        .service-card {
            background: white; border-radius: 20px; padding: 32px;
            border: 1px solid #e2e8f0; transition: all 0.3s;
            display: flex; flex-direction: column; gap: 12px;
        }
        .service-card:hover { transform: translateY(-4px); box-shadow: 0 12px 36px rgba(99,102,241,0.1); border-color: #c7d2fe; }
        .service-icon { font-size: 2.2rem; }
        .service-card h3 { font-size: 1rem; font-weight: 700; color: #0f172a; }
        .service-card p { font-size: 0.85rem; color: #64748b; line-height: 1.65; flex: 1; }
        .service-tag { font-size: 0.75rem; font-weight: 600; color: #6366f1; background: #eef2ff; padding: 3px 10px; border-radius: 999px; display: inline-block; width: fit-content; }

        /* DOCTORS */
        #bac-si { background: white; }
        .doctors-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
        .doctor-card {
            background: #f8fafc; border-radius: 20px; padding: 28px 20px;
            text-align: center; border: 1px solid #e2e8f0; transition: all 0.3s;
        }
        .doctor-card:hover { transform: translateY(-4px); box-shadow: 0 12px 36px rgba(14,165,233,0.1); background: white; border-color: #bae6fd; }
        .doctor-avatar {
            width: 84px; height: 84px; border-radius: 50%; margin: 0 auto 16px;
            background: linear-gradient(135deg, #0ea5e9, #6366f1);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; font-weight: 800; color: white;
            overflow: hidden; border: 3px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .doctor-avatar img { width: 100%; height: 100%; object-cover: cover; }
        .doctor-card h3 { font-size: 0.95rem; font-weight: 700; color: #0f172a; margin-bottom: 6px; }
        .doctor-specialty { font-size: 0.8rem; color: #0284c7; font-weight: 600; background: #f0f9ff; padding: 3px 10px; border-radius: 999px; display: inline-block; margin-bottom: 10px; }
        .doctor-card p { font-size: 0.8rem; color: #64748b; line-height: 1.5; }
        .no-doctors { text-align: center; color: #94a3b8; font-size: 0.95rem; padding: 40px; grid-column: 1/-1; }

        /* BOOKING */
        #dat-lich { background: linear-gradient(135deg, #f0f9ff 0%, #eef2ff 100%); }
        .booking-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: start; }
        .booking-info { }
        .booking-steps { display: flex; flex-direction: column; gap: 20px; margin-top: 32px; }
        .step { display: flex; align-items: flex-start; gap: 14px; }
        .step-num {
            width: 36px; height: 36px; flex-shrink: 0; border-radius: 50%;
            background: linear-gradient(135deg, #0ea5e9, #6366f1); color: white;
            font-weight: 800; font-size: 0.9rem; display: flex; align-items: center; justify-content: center;
        }
        .step-text h4 { font-size: 0.9rem; font-weight: 700; color: #0f172a; margin-bottom: 2px; }
        .step-text p { font-size: 0.82rem; color: #64748b; }
        .booking-form-card {
            background: white; border-radius: 24px; padding: 40px;
            box-shadow: 0 20px 60px rgba(14,165,233,0.1), 0 4px 16px rgba(0,0,0,0.05);
        }
        .booking-form-card h3 { font-size: 1.2rem; font-weight: 700; color: #0f172a; margin-bottom: 6px; }
        .booking-form-card p { font-size: 0.85rem; color: #64748b; margin-bottom: 28px; }
        .form-grid { display: grid; gap: 16px; }
        .form-grid label { font-size: 0.82rem; font-weight: 600; color: #374151; margin-bottom: 5px; display: block; }
        .form-grid label .req { color: #ef4444; }
        .form-grid input, .form-grid select, .form-grid textarea {
            width: 100%; padding: 11px 15px; border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: 0.9rem; font-family: 'Inter', sans-serif; transition: border-color 0.2s;
        }
        .form-grid input:focus, .form-grid select:focus, .form-grid textarea:focus { outline: none; border-color: #0ea5e9; box-shadow: 0 0 0 3px rgba(14,165,233,0.1); }
        .form-grid textarea { resize: vertical; min-height: 80px; }
        .form-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        #bookMsg {
            padding: 12px 16px; border-radius: 10px; font-size: 0.875rem; font-weight: 600;
            text-align: center; margin-top: 12px; display: none;
        }
        #bookMsg.success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        #bookMsg.error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* CONTACT */
        #lien-he { background: white; }
        .contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: center; }
        .contact-items { display: flex; flex-direction: column; gap: 24px; }
        .contact-item { display: flex; align-items: flex-start; gap: 16px; }
        .contact-icon {
            width: 48px; height: 48px; flex-shrink: 0; border-radius: 12px;
            background: linear-gradient(135deg, rgba(14,165,233,0.1), rgba(99,102,241,0.1));
            display: flex; align-items: center; justify-content: center; font-size: 1.3rem;
        }
        .contact-item h4 { font-size: 0.875rem; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
        .contact-item p { font-size: 0.875rem; color: #64748b; line-height: 1.6; }
        .map-placeholder {
            background: linear-gradient(135deg, #f0f9ff, #eef2ff);
            border-radius: 20px; height: 320px; display: flex; flex-direction: column;
            align-items: center; justify-content: center; gap: 12px;
            border: 2px dashed #bae6fd; color: #64748b;
        }
        .map-placeholder .map-icon { font-size: 3rem; }
        .map-placeholder p { font-size: 0.9rem; font-weight: 500; }
        .hours-table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        .hours-table td { padding: 8px 0; font-size: 0.875rem; border-bottom: 1px solid #f1f5f9; }
        .hours-table td:first-child { color: #374151; font-weight: 600; }
        .hours-table td:last-child { color: #0284c7; font-weight: 600; text-align: right; }

        /* FOOTER */
        footer {
            background: #0f172a; color: #94a3b8;
            padding: 40px 48px; text-align: center; font-size: 0.875rem;
        }
        footer a { color: #38bdf8; text-decoration: none; }
        footer .footer-brand { font-size: 1rem; font-weight: 700; color: white; margin-bottom: 8px; }

        /* TOAST */
        .msg-box {
            text-align: center; padding: 12px; border-radius: 10px; font-size: 0.875rem;
            font-weight: 600; margin-top: 12px; display: none;
        }
        .msg-box.success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; display: block; }
        .msg-box.error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; display: block; }

        @media (max-width: 900px) {
            .hero-inner, .booking-grid, .contact-grid { grid-template-columns: 1fr; }
            .features-grid, .services-grid { grid-template-columns: 1fr 1fr; }
            .doctors-grid { grid-template-columns: 1fr 1fr; }
            nav { padding: 0 20px; }
            .nav-links { display: none; }
            section, .hero-inner { padding-left: 20px; padding-right: 20px; }
            h1.hero-title { font-size: 2.2rem; }
            .section-title { font-size: 1.75rem; }
        }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav id="mainNav">
    <a href="#" class="nav-brand">
        <div class="nav-logo">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l7.5-7.5 7.5 7.5m-15 6l7.5-7.5 7.5 7.5"/></svg>
        </div>
        <div class="nav-title">Phòng Khám Da Liễu<small>Chuyên sâu · Tận tâm</small></div>
    </a>
    <div class="nav-links">
        <a href="#ve-chung">Giới thiệu</a>
        <a href="#dich-vu">Dịch vụ</a>
        <a href="#bac-si">Bác sĩ</a>
        <a href="#lien-he">Liên hệ</a>
        <a href="{{ route('login') }}" class="login-link">Đăng nhập</a>
        <a href="#dat-lich" class="btn-booking-nav">Đặt lịch ngay</a>
    </div>
</nav>

{{-- HERO --}}
<section class="hero" id="home">
    <div class="hero-inner">
        <div>
            <div class="hero-badge"><span>🏥</span> Phòng Khám Uy Tín #1</div>
            <h1 class="hero-title">Chăm sóc làn da<br><span class="highlight">chuyên sâu & tận tâm</span></h1>
            <p class="hero-desc">Đội ngũ bác sĩ da liễu giàu kinh nghiệm, trang thiết bị hiện đại, quy trình khám bệnh chuẩn quốc tế. Sức khỏe làn da của bạn là ưu tiên hàng đầu của chúng tôi.</p>
            <div class="hero-ctas">
                <a href="#dat-lich" class="btn-primary">📅 Đặt lịch khám</a>
                <a href="#dich-vu" class="btn-secondary">Xem dịch vụ →</a>
            </div>
            <div class="hero-stats">
                <div class="stat-item"><div class="stat-number">500+</div><div class="stat-label">Bệnh nhân điều trị</div></div>
                <div class="stat-item"><div class="stat-number">{{ $doctors->count() > 0 ? $doctors->count() : '5' }}+</div><div class="stat-label">Bác sĩ chuyên khoa</div></div>
                <div class="stat-item"><div class="stat-number">10+</div><div class="stat-label">Năm kinh nghiệm</div></div>
            </div>
        </div>

        {{-- Mini booking card in hero --}}
        <div class="hero-card">
            <div class="floating-badge">✨ Đặt nhanh</div>
            <div class="hero-card-title">Đặt lịch khám ngay</div>
            <div class="hero-card-sub">Điền thông tin, chúng tôi sẽ xác nhận trong 30 phút</div>
            <div class="quick-form" id="quickForm">
                <div>
                    <label for="q_name">Họ và tên <span class="req">*</span></label>
                    <input type="text" id="q_name" placeholder="Nguyễn Văn A">
                </div>
                <div>
                    <label for="q_phone">Số điện thoại <span class="req">*</span></label>
                    <input type="tel" id="q_phone" placeholder="0901 234 567">
                </div>
                <div class="row2">
                    <div>
                        <label for="q_date">Ngày khám <span class="req">*</span></label>
                        <input type="date" id="q_date" min="{{ date('Y-m-d') }}">
                    </div>
                    <div>
                        <label for="q_time">Giờ khám <span class="req">*</span></label>
                        <input type="time" id="q_time" value="08:00">
                    </div>
                </div>
                <div id="quickMsg"></div>
                <button class="btn-submit" id="quickSubmitBtn" onclick="submitQuick()">📅 Đặt lịch ngay</button>
            </div>
        </div>
    </div>
</section>

{{-- GIỚI THIỆU --}}
<section id="ve-chung">
    <div class="container">
        <div class="section-header">
            <div class="section-badge">Về chúng tôi</div>
            <h2 class="section-title">Vì sao chọn chúng tôi?</h2>
            <p class="section-desc">Phòng khám da liễu với đội ngũ bác sĩ chuyên khoa, hệ thống quản lý bệnh nhân hiện đại và dịch vụ tận tâm.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon" style="background: rgba(14,165,233,0.1);">🩺</div>
                <h3>Bác sĩ chuyên khoa</h3>
                <p>Đội ngũ bác sĩ da liễu được đào tạo tại các trường đại học y khoa hàng đầu, có nhiều năm kinh nghiệm lâm sàng.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background: rgba(99,102,241,0.1);">🔬</div>
                <h3>Thiết bị hiện đại</h3>
                <p>Trang bị máy móc chẩn đoán da tiên tiến, đảm bảo kết quả chẩn đoán chính xác và điều trị hiệu quả tối ưu.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background: rgba(16,185,129,0.1);">📱</div>
                <h3>Đặt lịch trực tuyến</h3>
                <p>Hệ thống đặt lịch 24/7, nhận xác nhận ngay qua điện thoại và hoàn toàn miễn phí — không cần chờ đợi lâu.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background: rgba(245,158,11,0.1);">💊</div>
                <h3>Dược phẩm chính hãng</h3>
                <p>Kho thuốc đa dạng, nguồn gốc rõ ràng, đảm bảo chất lượng điều trị từ toa thuốc đến tận tay bệnh nhân.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background: rgba(236,72,153,0.1);">📋</div>
                <h3>Hồ sơ bệnh án điện tử</h3>
                <p>Lưu trữ lịch sử khám bệnh đầy đủ, giúp bác sĩ theo dõi tiến trình điều trị và đưa ra phác đồ phù hợp nhất.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background: rgba(14,165,233,0.1);">🛡️</div>
                <h3>Bảo mật thông tin</h3>
                <p>Thông tin bệnh nhân được bảo mật tuyệt đối theo đúng quy định của Bộ Y tế và tiêu chuẩn an toàn thông tin.</p>
            </div>
        </div>
    </div>
</section>

{{-- DỊCH VỤ --}}
<section id="dich-vu">
    <div class="container">
        <div class="section-header">
            <div class="section-badge">Dịch vụ</div>
            <h2 class="section-title">Các bệnh da liễu thường gặp</h2>
            <p class="section-desc">Chúng tôi khám, chẩn đoán và kê đơn thuốc điều trị cho các bệnh lý da liễu phổ biến.</p>
        </div>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">🌞</div>
                <h3>Mụn trứng cá</h3>
                <p>Khám và kê đơn thuốc uống, thuốc bôi phù hợp theo mức độ mụn — từ mụn đầu đen đến mụn viêm nặng.</p>
                <div class="service-tag">Phổ biến nhất</div>
            </div>
            <div class="service-card">
                <div class="service-icon">💧</div>
                <h3>Viêm da — Dị ứng da</h3>
                <p>Chẩn đoán viêm da tiếp xúc, eczema, nổi mề đay và kê đơn thuốc kháng histamine, thuốc bôi corticoid phù hợp.</p>
                <div class="service-tag">Khám & kê đơn</div>
            </div>
            <div class="service-card">
                <div class="service-icon">🔴</div>
                <h3>Viêm da cơ địa</h3>
                <p>Tư vấn chăm sóc da và kê toa thuốc kiểm soát triệu chứng, giảm ngứa và ngăn tái phát hiệu quả.</p>
                <div class="service-tag">Mãn tính</div>
            </div>
            <div class="service-card">
                <div class="service-icon">🦠</div>
                <h3>Nấm da — Lang beng</h3>
                <p>Khám lâm sàng và kê đơn thuốc kháng nấm dạng uống hoặc bôi theo từng loại và mức độ nhiễm nấm.</p>
                <div class="service-tag">Kê đơn đặc hiệu</div>
            </div>
            <div class="service-card">
                <div class="service-icon">🩹</div>
                <h3>Vảy nến</h3>
                <p>Chẩn đoán và kê đơn thuốc kiểm soát vảy nến dạng bôi hoặc uống, kết hợp hướng dẫn chăm sóc hàng ngày.</p>
                <div class="service-tag">Theo dõi định kỳ</div>
            </div>
            <div class="service-card">
                <div class="service-icon">👶</div>
                <h3>Da liễu trẻ em</h3>
                <p>Khám các bệnh da đặc thù ở trẻ như hăm tã, chàm sữa, viêm da và kê toa thuốc an toàn phù hợp lứa tuổi.</p>
                <div class="service-tag">Chuyên biệt</div>
            </div>
        </div>
    </div>
</section>


{{-- ĐỘI NGŨ BÁC SĨ --}}
<section id="bac-si">
    <div class="container">
        <div class="section-header">
            <div class="section-badge">Đội ngũ</div>
            <h2 class="section-title">Bác sĩ chuyên khoa</h2>
            <p class="section-desc">Gặp gỡ đội ngũ bác sĩ da liễu tận tâm và giàu kinh nghiệm của chúng tôi.</p>
        </div>
        <div class="doctors-grid">
            @forelse($doctors as $doctor)
            <div class="doctor-card">
                <div class="doctor-avatar">
                    @if($doctor->user && $doctor->user->avatar)
                        <img src="{{ asset($doctor->user->avatar) }}" alt="{{ $doctor->user->full_name }}">
                    @else
                        {{ mb_substr($doctor->user->full_name ?? 'B', 0, 1) }}
                    @endif
                </div>
                <h3>BS. {{ $doctor->user->full_name ?? 'Chuyên khoa' }}</h3>
                <div class="doctor-specialty">{{ $doctor->specialty ?? 'Da Liễu' }}</div>
                <p>Chuyên gia điều trị các bệnh da liễu, nhiều năm kinh nghiệm lâm sàng.</p>
            </div>
            @empty
            <div class="no-doctors">
                <p>🩺 Đội ngũ bác sĩ đang được cập nhật.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ĐẶT LỊCH --}}
<section id="dat-lich">
    <div class="container">
        <div class="booking-grid">
            <div class="booking-info">
                <div class="section-badge">Đặt lịch</div>
                <h2 class="section-title">Đặt lịch khám<br>trực tuyến</h2>
                <p class="section-desc">Chúng tôi sẽ xác nhận lịch hẹn trong vòng 30 phút và nhắc nhở bạn trước 1 ngày.</p>
                <div class="booking-steps">
                    <div class="step">
                        <div class="step-num">1</div>
                        <div class="step-text">
                            <h4>Điền thông tin</h4>
                            <p>Nhập họ tên, số điện thoại và thông tin lịch hẹn mong muốn.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-num">2</div>
                        <div class="step-text">
                            <h4>Phòng khám xác nhận</h4>
                            <p>Chúng tôi liên hệ xác nhận lịch và tư vấn trước khi đến khám.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-num">3</div>
                        <div class="step-text">
                            <h4>Đến và được khám</h4>
                            <p>Đến đúng giờ, lễ tân tiếp nhận nhanh chóng không cần chờ đợi lâu.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="booking-form-card">
                <h3>📅 Thông tin đặt lịch</h3>
                <p>Điền đầy đủ để chúng tôi sắp xếp lịch phù hợp nhất cho bạn.</p>
                <div class="form-grid">
                    <div>
                        <label for="b_name">Họ và tên <span class="req">*</span></label>
                        <input type="text" id="b_name" placeholder="Nguyễn Văn A">
                    </div>
                    <div class="form-row2">
                        <div>
                            <label for="b_phone">Số điện thoại <span class="req">*</span></label>
                            <input type="tel" id="b_phone" placeholder="0901 234 567">
                        </div>
                        <div>
                            <label for="b_gender">Giới tính</label>
                            <select id="b_gender">
                                <option value="">-- Chọn --</option>
                                <option value="1">Nam</option>
                                <option value="0">Nữ</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="b_doctor">Chọn bác sĩ</label>
                        <select id="b_doctor">
                            <option value="">-- Bất kỳ bác sĩ nào --</option>
                            @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">BS. {{ $doctor->user->full_name ?? '?' }} — {{ $doctor->specialty ?? 'Da Liễu' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-row2">
                        <div>
                            <label for="b_date">Ngày khám <span class="req">*</span></label>
                            <input type="date" id="b_date" min="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label for="b_time">Giờ khám <span class="req">*</span></label>
                            <input type="time" id="b_time" value="08:00">
                        </div>
                    </div>
                    <div>
                        <label for="b_note">Triệu chứng / ghi chú</label>
                        <textarea id="b_note" placeholder="Mô tả triệu chứng hoặc yêu cầu đặc biệt (không bắt buộc)..."></textarea>
                    </div>
                    <div id="bookMsg"></div>
                    <button class="btn-submit" id="bookSubmitBtn" onclick="submitBooking()">✅ Xác nhận đặt lịch</button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- LIÊN HỆ --}}
<section id="lien-he">
    <div class="container">
        <div class="contact-grid">
            <div>
                <div class="section-badge">Liên hệ</div>
                <h2 class="section-title">Thông tin liên hệ</h2>
                <p class="section-desc" style="margin-bottom: 32px;">Chúng tôi luôn sẵn sàng giải đáp thắc mắc và hỗ trợ bạn đặt lịch khám.</p>
                <div class="contact-items">
                    <div class="contact-item">
                        <div class="contact-icon">📍</div>
                        <div>
                            <h4>Địa chỉ</h4>
                            <p>123 Đường Da Liễu, Phường Bình Thạnh<br>TP. Hồ Chí Minh</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">📞</div>
                        <div>
                            <h4>Điện thoại đặt lịch</h4>
                            <p>(028) 3800 1234<br>Hotline: 0901 234 567</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">⏰</div>
                        <div>
                            <h4>Giờ làm việc</h4>
                            <table class="hours-table">
                                <tr><td>Thứ Hai – Thứ Sáu</td><td>8:00 – 17:00</td></tr>
                                <tr><td>Thứ Bảy</td><td>8:00 – 12:00</td></tr>
                                <tr><td>Chủ Nhật</td><td>Nghỉ</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="map-placeholder">
                <div class="map-icon">🗺️</div>
                <p>123 Đường Da Liễu, Q. Bình Thạnh, TP.HCM</p>
                <p style="font-size: 0.8rem; color: #94a3b8;">Bản đồ sẽ được tích hợp</p>
            </div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer>
    <div class="footer-brand">🏥 Phòng Khám Da Liễu</div>
    <p>© {{ date('Y') }} Phòng Khám Da Liễu. Tất cả quyền được bảo lưu. |
       <a href="{{ route('login') }}">Đăng nhập hệ thống</a></p>
</footer>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// Navbar scroll effect
window.addEventListener('scroll', () => {
    document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 30);
});

// ===== QUICK FORM (Hero) =====
async function submitQuick() {
    const btn = document.getElementById('quickSubmitBtn');
    const msg = document.getElementById('quickMsg');
    msg.className = ''; msg.textContent = '';

    const name  = document.getElementById('q_name').value.trim();
    const phone = document.getElementById('q_phone').value.trim();
    const date  = document.getElementById('q_date').value;
    const time  = document.getElementById('q_time').value;

    if (!name || !phone || !date || !time) {
        msg.className = 'msg-box error';
        msg.textContent = 'Vui lòng điền đầy đủ họ tên, SĐT, ngày và giờ.';
        return;
    }

    btn.disabled = true; btn.textContent = '⏳ Đang gửi...';

    try {
        const res = await fetch('{{ route("public.booking") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ full_name: name, phone, date, time })
        }).then(r => r.json());

        if (res.status === 'success') {
            msg.className = 'msg-box success';
            msg.textContent = '✅ ' + res.message;
            document.getElementById('q_name').value = '';
            document.getElementById('q_phone').value = '';
            document.getElementById('q_date').value = '';
        } else {
            const errs = res.errors ? Object.values(res.errors).flat().join(' ') : (res.message || 'Có lỗi xảy ra.');
            msg.className = 'msg-box error';
            msg.textContent = '❌ ' + errs;
        }
    } catch (e) {
        msg.className = 'msg-box error';
        msg.textContent = '❌ Không thể kết nối. Vui lòng thử lại.';
    }

    btn.disabled = false; btn.textContent = '📅 Đặt lịch ngay';
}

// ===== MAIN BOOKING FORM =====
async function submitBooking() {
    const btn = document.getElementById('bookSubmitBtn');
    const msg = document.getElementById('bookMsg');
    msg.className = ''; msg.textContent = ''; msg.style.display = 'none';

    const name     = document.getElementById('b_name').value.trim();
    const phone    = document.getElementById('b_phone').value.trim();
    const gender   = document.getElementById('b_gender').value;
    const doctorId = document.getElementById('b_doctor').value;
    const date     = document.getElementById('b_date').value;
    const time     = document.getElementById('b_time').value;
    const note     = document.getElementById('b_note').value.trim();

    if (!name || !phone || !date || !time) {
        msg.style.display = 'block';
        msg.className = 'msg-box error';
        msg.textContent = '❌ Vui lòng điền họ tên, số điện thoại, ngày và giờ khám.';
        return;
    }

    btn.disabled = true; btn.textContent = '⏳ Đang gửi...';

    try {
        const payload = { full_name: name, phone, date, time };
        if (gender !== '') payload.gender = gender;
        if (doctorId) payload.doctor_id = doctorId;
        if (note) payload.note = note;

        const res = await fetch('{{ route("public.booking") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(payload)
        }).then(r => r.json());

        msg.style.display = 'block';
        if (res.status === 'success') {
            msg.className = 'msg-box success';
            msg.textContent = '✅ ' + res.message;
            // Clear form
            ['b_name','b_phone','b_date','b_note'].forEach(id => document.getElementById(id).value = '');
            document.getElementById('b_gender').value = '';
            document.getElementById('b_doctor').value = '';
            document.getElementById('b_time').value = '08:00';
        } else {
            const errs = res.errors ? Object.values(res.errors).flat().join(' ') : (res.message || 'Có lỗi xảy ra.');
            msg.className = 'msg-box error';
            msg.textContent = '❌ ' + errs;
        }
    } catch (e) {
        msg.style.display = 'block';
        msg.className = 'msg-box error';
        msg.textContent = '❌ Không thể kết nối. Vui lòng thử lại.';
    }

    btn.disabled = false; btn.textContent = '✅ Xác nhận đặt lịch';
}

// Set min date to today
document.getElementById('q_date').value = new Date().toISOString().split('T')[0];
document.getElementById('b_date').value = new Date().toISOString().split('T')[0];
</script>
</body>
</html>
