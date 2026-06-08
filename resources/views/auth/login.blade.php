<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistem Gudang Toko Plastik</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --font-family: 'Outfit', sans-serif;
            --primary-color: #10b981;
            --primary-hover: #059669;
            --dark-color: #0f172a;
        }

        body {
            font-family: var(--font-family);
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #064e3b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #f1f5f9;
        }

        .login-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 440px;
            padding: 40px;
            transition: all 0.3s ease;
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            background: rgba(16, 185, 129, 0.15);
            border: 2px solid var(--primary-color);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.8rem;
            color: var(--primary-color);
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.3);
        }

        .login-title {
            text-align: center;
            font-weight: 700;
            font-size: 1.6rem;
            margin-bottom: 5px;
            color: #ffffff;
        }

        .login-subtitle {
            text-align: center;
            color: #94a3b8;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 500;
            font-size: 0.85rem;
            color: #cbd5e1;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .input-group {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .input-group:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: #64748b;
            padding-left: 15px;
        }

        .form-control {
            background: transparent;
            border: none;
            color: #ffffff;
            font-size: 0.95rem;
            padding: 12px 15px 12px 5px;
        }

        .form-control:focus {
            background: transparent;
            border: none;
            box-shadow: none;
            color: #ffffff;
        }

        .form-control::placeholder {
            color: #475569;
        }

        .form-check-input {
            background-color: rgba(15, 23, 42, 0.6);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-login {
            background-color: var(--primary-color);
            border: none;
            color: #ffffff;
            font-weight: 600;
            padding: 12px;
            border-radius: 10px;
            width: 100%;
            margin-top: 15px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-login:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Default credentials info box */
        .credential-box {
            background: rgba(16, 185, 129, 0.08);
            border: 1px dashed rgba(16, 185, 129, 0.3);
            border-radius: 12px;
            padding: 15px;
            margin-top: 25px;
            font-size: 0.8rem;
        }

        .credential-box table {
            width: 100%;
            margin: 0;
            color: #a7f3d0;
        }

        .credential-box table td {
            padding: 2px 0;
        }

        .text-error {
            color: #f87171;
            font-size: 0.8rem;
            margin-top: 5px;
        }

        .alert-dismissible {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            font-size: 0.85rem;
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <div class="login-card fade-in-up">
        <div class="brand-logo">
            <i class="fa-solid fa-boxes-packing"></i>
        </div>
        
        <h3 class="login-title">GUDANG PLASTIK</h3>
        <p class="login-subtitle">Sistem Manajemen Stok & Inventaris</p>

        <!-- General Error Alert -->
        @if($errors->any())
            <div class="alert alert-dismissible fade show p-3 mb-4" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                {{ $errors->first() }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close" style="padding: 1.1rem;"></button>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <!-- Email Field -->
            <div class="mb-4">
                <label for="email" class="form-label">EMAIL ADDRESS</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                    <input type="email" name="email" id="email" class="form-control" placeholder="admin@plastik.com" value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <!-- Password Field -->
            <div class="mb-4">
                <label for="password" class="form-label">SECURE PASSWORD</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="d-flex justify-content-between align-items-center mb-4" style="font-size: 0.85rem;">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label text-slate-300" for="remember">
                        Ingat Saya
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-login">
                Masuk Sistem <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
            </button>
        </form>

        <!-- Default Accounts Info Box -->
        <div class="credential-box">
            <div class="fw-bold mb-2 text-white"><i class="fa-solid fa-circle-info me-1"></i> Akun Uji Coba Default:</div>
            <table>
                <tr>
                    <td class="fw-semibold">Admin:</td>
                    <td>admin@plastik.com</td>
                    <td>/ password</td>
                </tr>
                <tr>
                    <td class="fw-semibold">Karyawan:</td>
                    <td>karyawan@plastik.com</td>
                    <td>/ password</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
