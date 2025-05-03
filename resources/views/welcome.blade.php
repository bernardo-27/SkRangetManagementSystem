<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rise & Lead | QR-Integrated SK System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom, #003366, #00509d, #0084ff);
            color: white;
            text-align: center;
        }

        .hero {
            background: url("/images/SK's.png") center/cover no-repeat;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            position: relative;
        }
        .hero-overlay {
            background: rgba(0, 0, 0, 0.5);
            position: absolute;
            inset: 0;
        }
        .hero-content {
            position: relative;
            z-index: 1;
            padding: 20px;
            border-radius: 10px;
            backdrop-filter: blur(8px);
            background: rgba(255, 255, 255, 0.1);
        }
        h1 {
            font-weight: 700;
            font-size: 3rem;
        }
        p {
            font-size: 1.2rem;
        }
        .btn-custom {
            background: #ffcc00;
            color: #003366;
            border-radius: 50px;
            padding: 12px 24px;
            font-weight: 600;
            text-transform: uppercase;
            transition: 0.4s ease-in-out;
        }
        .btn-custom:hover {
            background: #ff9900;
            color: white;
        }
        .qr-code {
            margin-top: 20px;
        }
        .qr-code img {
            width: 150px;
            border-radius: 10px;
        }
        /* General Button Styles */
.nav-buttons {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease-in-out;
    cursor: pointer;
}

/* Dashboard Button (Primary Action) */
.dashboard-btn {
    background-color: #1d4ed8; /* Blue */
    color: white;
    border: none;
}

.dashboard-btn:hover {
    background-color: #1e40af; /* Darker Blue */
}

/* Login Button (Minimalist Look) */
.login-btn {
    background-color: white;
    color: #333;
    border: 2px solid #ddd;
}

.login-btn:hover {
    background-color: #f3f3f3;
    color: #222;
}

/* Register Button (Gradient Effect) */
.register-btn {
    background: linear-gradient(45deg, #000b6e,#0f00e2 );
    color: white;
    border: none;
}

.register-btn:hover {
    background: linear-gradient(45deg, #1320d4, #0f167c);
}

/* Focus (Accessibility) */
.btn:focus {
    outline: 2px solid #b4b5ff;
    outline-offset: 2px;
}



        .section {
            padding: 60px 20px;
        }
        .section-light {
            background: white;
            color: #003366;
        }
        .feature-icon {
            font-size: 40px;
            color: #ff9900;
        }
        .testimonials {
            background: #003366;
            padding: 60px 20px;
        }
        .contact {
            padding: 60px 20px;
            background: #00509d;
        }
        .contact iframe {
            width: 100%;
            height: 300px;
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content text-center" data-aos="fade-up">
            <h1>Rise & Lead</h1>
            <p>Empowering the youth of Ranget, Tagudin, Ilocos Sur through seamless digital access.</p>
            @if (Route::has('login'))
            <nav class="nav-buttons">
                @if(auth()->check())
                @if(auth()->user()->usertype === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn dashboard-btn">Dashboard</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn dashboard-btn">Dashboard</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn login-btn">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn register-btn">Register</a>
                @endif
            @endif
            </nav>
        @endif


        </div>
    </section>

    <!-- About Section -->
    <section class="section section-light" data-aos="fade-up">
        <div class="container">
            <h2>About Us</h2>
            <p>Rise & Lead is a QR-integrated information system designed to streamline youth engagement, events, and services within the SK of Ranget.</p>
        </div>
    </section>

    <!-- Community Impact Section -->
    <section class="testimonials text-white text-center" data-aos="fade-up">
        <div class="container">
            <h2>Community Impact</h2>
            <p>See how Rise & Lead is transforming youth engagement.</p>
            <blockquote>“This system makes accessing SK services so easy!” </blockquote>
            <blockquote>“A game-changer for local youth participation.”</blockquote>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact text-white text-center" data-aos="fade-up">
        <div class="container">
            <h2>Contact Us</h2>
            <p>Have questions? Reach out to us.</p>
            <form>
                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Your Name">
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control" placeholder="Your Email">
                </div>
                <div class="mb-3">
                    <textarea class="form-control" placeholder="Your Message"></textarea>
                </div>
                <button type="submit" class="btn btn-custom">Send</button>
            </form>
            <div class="mt-4">
                <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3930.201039888565!2d120.43601227461456!3d16.9329343839029!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3391e7f1b27fdc95%3A0x8f1c0dd35493f50d!2sRanget%2C%20Tagudin%2C%20Ilocos%20Sur%2C%20Philippines!5e0!3m2!1sen!2sph!4v1709101234567"
                width="100%"
                height="300"
                style="border:0; border-radius:10px;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000
        });
    </script>
</body>
</html>
