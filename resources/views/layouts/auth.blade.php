<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SINFAS</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #1e40af 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
            position: relative;
            overflow-x: hidden;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }

        /* Animated diagonal stripes - lebih lambat */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: repeating-linear-gradient(
                -45deg,
                transparent,
                transparent 40px,
                rgba(255, 255, 255, 0.08) 40px,
                rgba(255, 255, 255, 0.08) 42px,
                transparent 42px,
                transparent 80px,
                rgba(255, 255, 255, 0.15) 80px,
                rgba(255, 255, 255, 0.15) 84px
            );
            background-size: 200% 200%;
            animation: stripeMove 60s linear infinite;
            z-index: 0;
        }

        @keyframes stripeMove {
            0% { background-position: 0% 0%; }
            100% { background-position: 200% 200%; }
        }

        /* Floating circles - lebih slow dan smooth */
        .floating-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            z-index: 0;
        }

        .floating-circle:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation: float1 25s ease-in-out infinite;
        }

        .floating-circle:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation: float2 30s ease-in-out infinite;
        }

        .floating-circle:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation: float3 35s ease-in-out infinite;
        }

        .floating-circle:nth-child(4) {
            width: 100px;
            height: 100px;
            top: 30%;
            right: 20%;
            animation: float4 28s ease-in-out infinite;
        }

        .floating-circle:nth-child(5) {
            width: 70px;
            height: 70px;
            bottom: 30%;
            right: 30%;
            animation: float5 32s ease-in-out infinite;
        }

        @keyframes float1 {
            0%, 100% { transform: translateY(0) translateX(0); }
            50% { transform: translateY(-20px) translateX(15px); }
        }

        @keyframes float2 {
            0%, 100% { transform: translateY(0) translateX(0); }
            50% { transform: translateY(-15px) translateX(-20px); }
        }

        @keyframes float3 {
            0%, 100% { transform: translateY(0) translateX(0); }
            50% { transform: translateY(-25px) translateX(10px); }
        }

        @keyframes float4 {
            0%, 100% { transform: translateY(0) translateX(0); }
            50% { transform: translateY(-18px) translateX(-15px); }
        }

        @keyframes float5 {
            0%, 100% { transform: translateY(0) translateX(0); }
            50% { transform: translateY(-22px) translateX(12px); }
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 28rem;
        }
    </style>
</head>
<body>
    <!-- Floating animated circles -->
    <div class="floating-circle"></div>
    <div class="floating-circle"></div>
    <div class="floating-circle"></div>
    <div class="floating-circle"></div>
    <div class="floating-circle"></div>

    <div class="content-wrapper">
        @yield('content')
    </div>
</body>
</html>