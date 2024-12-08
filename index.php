<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>VGZ</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Minecraft';
            src: url('https://cdnjs.cloudflare.com/ajax/libs/pixelated-fonts/1.0.0/MinecraftRegular-Bmg3.woff2') format('woff2');
        }

        :root {
            --primary-color: #40A84B;
            --primary-hover: #4AC857;
            --discord-color: #5865F2;
            --discord-hover: #6B78FF;
            --glow-color: rgba(64, 168, 75, 0.5);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        html, body {
            width: 100%;
            height: 100%;
            overflow: hidden;
            position: fixed;
        }

        body {
            font-family: 'Minecraft', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #000;
            color: white;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            perspective: 1000px;
        }

        .video-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-color: #000;
            overflow: hidden;
        }

        #bg-video {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -1;
            filter: brightness(0.7) blur(3px);
            object-fit: cover;
            opacity: 0;
            transition: opacity 0.5s ease, filter 0.5s ease;
        }

        #bg-video.loaded {
            opacity: 1;
        }

        #bg-video:hover {
            filter: brightness(0.8) blur(2px);
        }

        #bg-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-image: url('assets/bg-fallback.png');
            background-size: cover;
            background-position: center;
            display: none;
            filter: brightness(0.7) blur(3px);
            transition: filter 0.5s ease;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, 
                rgba(0,0,0,0.2) 0%, 
                rgba(0,0,0,0.6) 50%,
                rgba(0,0,0,0.8) 100%
            );
            z-index: -1;
            pointer-events: none;
        }

        .controls {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }

        .control-btn {
            background: rgba(0, 0, 0, 0.6);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .control-btn:hover {
            background: rgba(0, 0, 0, 0.8);
            border-color: rgba(255, 255, 255, 0.5);
            transform: scale(1.1);
        }

        .control-btn.active {
            background: var(--primary-color);
            border-color: var(--primary-hover);
        }

        .control-btn i {
            font-size: 16px;
        }

        .control-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .content {
            text-align: center;
            z-index: 1;
            padding: 20px;
            animation: fadeIn 1.2s ease-out;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            transform-style: preserve-3d;
        }

        .minecraft-logo {
            width: min(300px, 80vw);
            height: auto;
            aspect-ratio: 1/1;
            margin-bottom: 40px;
            filter: drop-shadow(0 0 25px rgba(255,255,255,0.4));
            transition: all 0.5s ease;
            animation: floatAnimation 4s ease-in-out infinite;
            -webkit-user-select: none;
            user-select: none;
            transform: translateZ(50px);
        }

        .minecraft-logo:hover {
            transform: translateZ(70px) scale(1.05);
            filter: drop-shadow(0 0 35px rgba(255,255,255,0.6));
        }

        .buttons {
            display: flex;
            gap: clamp(20px, 4vw, 35px);
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
            padding: 0 20px;
            transform: translateZ(30px);
        }

        .button {
            padding: clamp(14px, 3vw, 18px) clamp(30px, 5vw, 45px);
            font-size: clamp(16px, 4vw, 20px);
            text-decoration: none;
            color: white;
            background-color: var(--primary-color);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-transform: uppercase;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.3),
                        0 0 20px var(--glow-color);
            -webkit-tap-highlight-color: transparent;
            white-space: nowrap;
            backdrop-filter: blur(5px);
            background: linear-gradient(135deg, 
                rgba(64, 168, 75, 0.9), 
                rgba(74, 200, 87, 0.9)
            );
        }

        .button i {
            font-size: clamp(14px, 3.5vw, 18px);
            transition: transform 0.3s ease;
        }

        .button:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                120deg,
                transparent,
                rgba(255, 255, 255, 0.3),
                transparent
            );
            transition: 0.6s;
        }

        .button:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 8px 25px rgba(0,0,0,0.4),
                        0 0 30px var(--glow-color);
        }

        .button:hover:before {
            left: 100%;
        }

        .button:hover i {
            transform: scale(1.2) rotate(-5deg);
        }

        .button:active {
            transform: translateY(-2px) scale(0.98);
        }

        .button.discord {
            background: linear-gradient(135deg, 
                rgba(88, 101, 242, 0.9), 
                rgba(107, 120, 255, 0.9)
            );
            box-shadow: 0 6px 20px rgba(0,0,0,0.3),
                        0 0 20px rgba(88, 101, 242, 0.5);
        }

        .button.discord:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.4),
                        0 0 30px rgba(88, 101, 242, 0.6);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes floatAnimation {
            0% {
                transform: translateZ(50px) translateY(0);
            }
            50% {
                transform: translateZ(50px) translateY(-15px);
            }
            100% {
                transform: translateZ(50px) translateY(0);
            }
        }

        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: particleFloat linear infinite;
        }

        @keyframes particleFloat {
            0% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
            100% {
                transform: translateY(-20vh) scale(1);
                opacity: 0;
            }
        }

        @media (max-width: 768px) {
            .controls {
                bottom: 10px;
                right: 10px;
            }

            .control-btn {
                width: 35px;
                height: 35px;
            }
        }

        @media (max-width: 360px) {
            .buttons {
                flex-direction: column;
                width: 100%;
                max-width: 280px;
                margin-left: auto;
                margin-right: auto;
            }

            .button {
                width: 100%;
                justify-content: center;
            }

            .minecraft-logo {
                margin-bottom: 25px;
            }
        }

        @media (max-height: 500px) and (orientation: landscape) {
            .content {
                padding: 15px;
            }

            .minecraft-logo {
                width: min(200px, 30vh);
                margin-bottom: 20px;
            }

            .buttons {
                gap: 15px;
                margin-top: 15px;
            }

            .button {
                padding: 10px 25px;
                font-size: 16px;
            }
        }

        @media (hover: none) {
            .button:before {
                display: none;
            }
            
            .minecraft-logo:hover {
                transform: translateZ(50px);
            }
        }
    </style>
</head>
<body>
    <div class="video-container">
        <video id="bg-video" autoplay loop muted playsinline preload="auto">
            <source src="assets/background.mp4" type="video/mp4">
            <source src="background.mp4" type="video/mp4">
        </video>
    </div>
    <div id="bg-image"></div>
    <div class="overlay"></div>
    <div class="particles"></div>

    <div class="controls">
        <button class="control-btn" id="toggleVideo" title="Toggle Video Background" disabled>
            <i class="fas fa-video"></i>
        </button>
        <button class="control-btn" id="toggleAudio" title="Toggle Audio" disabled>
            <i class="fas fa-volume-mute"></i>
        </button>
    </div>

    <div class="content">
        <img src="assets/minecraft-logo.png" alt="Minecraft Logo" class="minecraft-logo">
        <div class="buttons">
            <a href="https://discord.gg/zc4up76Rzu" class="button discord" target="_blank" rel="noopener">
                <i class="fab fa-discord"></i>
                Discord
            </a>
            <a href="VGZ Launcher-setup-2.2.1.exe" class="button">
                <i class="fas fa-download"></i>
                Download
            </a>
        </div>
    </div>

    <script>
        // Improved video handling
        const video = document.getElementById('bg-video');
        const fallbackImage = document.getElementById('bg-image');
        const toggleVideoBtn = document.getElementById('toggleVideo');
        const toggleAudioBtn = document.getElementById('toggleAudio');
        let isVideoPlaying = true;
        let isAudioMuted = true; // Start muted

        // Video loading handler
        function handleVideoLoad() {
            video.classList.add('loaded');
            toggleVideoBtn.disabled = false;
            toggleAudioBtn.disabled = false;
            console.log('Video loaded successfully');
        }

        // Video error handler
        function handleVideoError(error) {
            console.log('Video failed to load:', error);
            video.style.display = 'none';
            fallbackImage.style.display = 'block';
            toggleVideoBtn.disabled = true;
            toggleAudioBtn.disabled = true;
        }

        // Set up video event listeners
        video.addEventListener('loadeddata', handleVideoLoad);
        video.addEventListener('error', handleVideoError);

        // Timeout for video loading
        const videoLoadTimeout = setTimeout(() => {
            if (!video.classList.contains('loaded')) {
                handleVideoError('Video load timeout');
            }
        }, 5000);

        // Video play handler
        video.addEventListener('canplay', () => {
            clearTimeout(videoLoadTimeout);
            video.play().catch(error => {
                console.log('Auto-play failed:', error);
                handleVideoError(error);
            });
        });

        // Video toggle functionality
        toggleVideoBtn.addEventListener('click', function() {
            if (isVideoPlaying) {
                video.style.display = 'none';
                fallbackImage.style.display = 'block';
                toggleVideoBtn.innerHTML = '<i class="fas fa-video-slash"></i>';
                toggleVideoBtn.classList.add('active');
            } else {
                video.style.display = 'block';
                fallbackImage.style.display = 'none';
                toggleVideoBtn.innerHTML = '<i class="fas fa-video"></i>';
                toggleVideoBtn.classList.remove('active');
            }
            isVideoPlaying = !isVideoPlaying;
        });

        // Audio toggle functionality
        toggleAudioBtn.addEventListener('click', function() {
            if (isAudioMuted) {
                video.muted = false;
                toggleAudioBtn.innerHTML = '<i class="fas fa-volume-up"></i>';
                toggleAudioBtn.classList.add('active');
            } else {
                video.muted = true;
                toggleAudioBtn.innerHTML = '<i class="fas fa-volume-mute"></i>';
                toggleAudioBtn.classList.remove('active');
            }
            isAudioMuted = !isAudioMuted;
        });

        // Create particles
        const particlesContainer = document.querySelector('.particles');
        const particleCount = 30;

        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            
            // Random positions and animation duration
            const left = Math.random() * 100;
            const duration = 5 + Math.random() * 5;
            const delay = Math.random() * 5;
            const size = 1 + Math.random() * 3;
            
            particle.style.left = `${left}%`;
            particle.style.animationDuration = `${duration}s`;
            particle.style.animationDelay = `${delay}s`;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            
            particlesContainer.appendChild(particle);
        }

        // 3D effect on mouse move
        document.addEventListener('mousemove', function(e) {
            const content = document.querySelector('.content');
            const xAxis = (window.innerWidth / 2 - e.pageX) / 50;
            const yAxis = (window.innerHeight / 2 - e.pageY) / 50;
            
            content.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
        });

        // Reset transform on mouse leave
        document.addEventListener('mouseleave', function() {
            const content = document.querySelector('.content');
            content.style.transform = 'rotateY(0) rotateX(0)';
        });

        // Mobile optimization
        let lastTouchEnd = 0;
        document.addEventListener('touchend', function(event) {
            const now = (new Date()).getTime();
            if (now - lastTouchEnd <= 300) {
                event.preventDefault();
            }
            lastTouchEnd = now;
        }, false);

        // Performance optimization for particles
        function optimizeParticles() {
            const particles = document.querySelectorAll('.particle');
            if (window.innerWidth < 768) {
                particles.forEach((particle, index) => {
                    if (index > 15) { // Reduce particles on mobile
                        particle.style.display = 'none';
                    }
                });
            }
        }

        // Call on load and resize
        window.addEventListener('load', optimizeParticles);
        window.addEventListener('resize', optimizeParticles);

        // Preload background image
        const bgImage = new Image();
        bgImage.src = 'assets/bg-fallback.png';
        
        // Handle visibility change
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                video.pause();
            } else {
                if (isVideoPlaying) {
                    video.play().catch(function(error) {
                        console.log('Video play failed after visibility change:', error);
                    });
                }
            }
        });

        // Check for WebGL support and disable 3D effects if not supported
        function isWebGLAvailable() {
            try {
                const canvas = document.createElement('canvas');
                return !!(window.WebGLRenderingContext && 
                    (canvas.getContext('webgl') || canvas.getContext('experimental-webgl')));
            } catch(e) {
                return false;
            }
        }

        if (!isWebGLAvailable()) {
            document.querySelector('.content').style.transform = 'none';
            document.removeEventListener('mousemove', null);
            document.removeEventListener('mouseleave', null);
        }
    </script>
</body>
</html>