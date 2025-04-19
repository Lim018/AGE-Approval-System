<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Pengajuan Kegiatan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- AOS Animation Library -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            text-align: center;
            border-radius: 15px 15px 0 0 !important;
            padding: 30px 20px;
            border: none;
        }
        
        .card-header h3 {
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .card-body {
            padding: 30px;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            padding: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .login-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            color: white;
        }
        
        .form-floating > label {
            padding-left: 15px;
        }
        
        .form-floating > .form-control {
            height: calc(3.5rem + 2px);
            line-height: 1.25;
        }
        
        /* Animations */
        @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(15px); /* Reduced distance */
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
        }
        
        .animated {
            animation: fadeInUp 0.4s ease forwards; /* Reduced from 0.6s to 0.4s */
        }
        
        .delay-1 {
            animation-delay: 0.05s; /* Reduced from 0.1s */
        }
        
        .delay-2 {
            animation-delay: 0.1s; /* Reduced from 0.2s */
        }
        
        .delay-3 {
            animation-delay: 0.15s; /* Reduced from 0.3s */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card animated">
            <div class="card-header">
                <i class="fas fa-user-circle login-icon"></i>
                <h3>Sistem Pengajuan Kegiatan</h3>
                <p class="mb-0">Silakan login untuk melanjutkan</p>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger animated delay-1">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-4 form-floating animated delay-1">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                        <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="mb-4 form-floating animated delay-2">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" required>
                        <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="mb-4 form-check animated delay-2">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Ingat Saya</label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 animated delay-3">
                        <i class="fas fa-sign-in-alt me-2"></i> Login
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation Library -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
        duration: 400,
        easing: 'ease-out',
        once: true
    });
    </script>
</body>
</html>