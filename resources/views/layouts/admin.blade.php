<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Smart Perkemi') }}</title>

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/all.min.css') }}">

    <!-- Select2 & jQuery -->
    <link href="{{ asset('vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/select2/select2.min.js') }}"></script>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-50 overflow-x-hidden">
    @hasanyrole('Wasit|Contingent')
        <!-- Header / Mobile Layout for Perwasitan & Contingent -->
        <div class="min-h-screen flex flex-col pb-20" x-data="{ userDropdownOpen: false }">
            @include('layouts.admin.mobile-header')

            <!-- Main Content Area -->
            <main class="flex-1 max-w-full mx-auto w-full px-4 sm:px-6 lg:px-8 pt-4 md:pt-6 pb-2 md:pb-3">
                {{ $slot }}
            </main>

            @include('layouts.admin.bottombar')
        </div>
    @else
        <!-- Sidebar Layout for Admin and other management roles -->
        <div class="flex h-screen bg-slate-50 overflow-hidden" x-data="{ mobileMenuOpen: false }" @open-mobile-sidebar.window="mobileMenuOpen = true">
            <!-- Sidebar -->
            @include('layouts.admin.sidebar')

            <!-- Mobile Sidebar Backdrop & Menu (Optional, we can just use the existing sidebar conditionally hidden) -->
            <div x-show="mobileMenuOpen" class="fixed inset-0 z-50 flex md:hidden" x-cloak>
                <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" @click="mobileMenuOpen = false"></div>
                <div x-show="mobileMenuOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative flex w-full max-w-xs flex-1 flex-col bg-slate-900 pt-5 pb-4">
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button type="button" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="mobileMenuOpen = false">
                            <span class="sr-only">Close sidebar</span>
                            <i class="fas fa-times text-white text-xl"></i>
                        </button>
                    </div>
                    <!-- Reuse the sidebar content for mobile by including it again but without the hidden class, or we can just make the main sidebar responsive. Let's make main sidebar responsive via alpine if we need. Since we included sidebar above, let's keep it simple: the main sidebar is hidden on mobile, so we include it here without hidden md:flex -->
                    <div class="flex-1 overflow-y-auto">
                        @include('layouts.admin.sidebar')
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                @include('layouts.admin.topbar')

                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-4 md:p-6 custom-scrollbar">
                    {{ $slot }}
                </main>

                <div class="mt-auto">
                    @include('layouts.admin.footer')
                </div>
            </div>
        </div>
    @endhasanyrole
    <!-- SweetAlert2 -->
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script>
        window.addEventListener('swal', function (event) {
            const data = Array.isArray(event.detail) ? event.detail[0] : (event.detail[0] || event.detail);
            Swal.fire({
                title: data.title,
                text: data.text,
                icon: data.icon,
                timer: data.timer || 3000,
                showConfirmButton: data.showConfirmButton || false,
            });
        });

        // 🎙️ PUBLIC ADDRESS SYSTEM (TTS)
        let isPlayingAnnouncer = false;
        let currentAudio = null;

        window.addEventListener('play-announcer', event => {
            console.log('Announcer event received:', event.detail);
            const data = Array.isArray(event.detail) ? event.detail[0] : (event.detail[0] || event.detail);
            const text = formatAnnouncerText(data.text);
            console.log('Announcer text to speak:', text);

            stopAnnouncer(); // ⛔ pastikan tidak numpuk
            isPlayingAnnouncer = true;

            function playBeepAndSpeak() {
                if (!isPlayingAnnouncer) return;

                currentAudio = new Audio('/asset/music/nada-suara.mp3');
                currentAudio.volume = 0.6;

                let playPromise = currentAudio.play();

                if (playPromise !== undefined) {
                    playPromise.then(() => {
                        currentAudio.onended = () => {
                            if (!isPlayingAnnouncer) return;
                            setTimeout(() => speak(text), 500);
                        };
                    }).catch(() => {
                        // Jika audio gagal/tidak ada, langsung bicara
                        speak(text);
                    });
                } else {
                    // Fallback untuk browser lama
                    speak(text);
                }
            }

            function speak(text) {
                console.log('Attempting to speak:', text);
                if (!isPlayingAnnouncer) {
                    console.warn('Announcer is not playing, skipping speak.');
                    return;
                }

                window.speechSynthesis.cancel();
                const speech = new SpeechSynthesisUtterance(text);
                speech.lang = 'id-ID';
                speech.rate = 1.1;
                speech.pitch = 1;
                speech.volume = 1;

                function setVoice() {
                    const voices = window.speechSynthesis.getVoices();
                    console.log('Voices available:', voices.length);
                    let voice = voices.find(v => v.name.includes('Google Bahasa Indonesia')) ||
                                voices.find(v => v.lang === 'id-ID') ||
                                voices[0];

                    if (voice) {
                        console.log('Selected voice:', voice.name);
                        speech.voice = voice;
                    }

                    speech.onstart = () => console.log('TTS started.');
                    speech.onend = () => {
                        console.log('TTS ended.');
                        stopAnnouncer();
                    };
                    speech.onerror = (e) => console.error('TTS error:', e);

                    window.speechSynthesis.speak(speech);
                }

                if (window.speechSynthesis.getVoices().length) {
                    setVoice();
                } else {
                    console.log('Waiting for voices to be loaded...');
                    window.speechSynthesis.onvoiceschanged = setVoice;
                }
            }

            playBeepAndSpeak();
        });


        // ✅ FUNCTION STOP
        window.stopAnnouncer = function() {
            isPlayingAnnouncer = false;
            window.speechSynthesis.cancel();

            if (currentAudio) {
                currentAudio.pause();
                currentAudio.currentTime = 0;
            }
            console.log('Announcer stopped manually.');
        }

        // ✅ TIMER SOUND HELPERS
        window.playTimerTick = function(frequency = 1000, duration = 0.08) {
            // User requested to remove tick tick tick
            return;
        };

        window.playBuzzer = function(src) {
            try {
                const audio = new Audio(src);
                audio.play().catch(e => console.warn('Buzzer error:', e));
            } catch (e) {
                console.warn('Audio error:', e);
            }
        };

        // Pancing AudioContext agar aktif saat ada klik pertama di halaman
        document.addEventListener('click', function() {
            if (window.sharedAudioCtx && window.sharedAudioCtx.state === 'suspended') {
                window.sharedAudioCtx.resume();
            }
        }, { once: true });

        window.speakCountdown = function(text) {
            if (window.speechSynthesis.speaking) return; 
            const speech = new SpeechSynthesisUtterance(text);
            speech.lang = 'id-ID';
            speech.rate = 1.8; 
            speech.pitch = 1.2;
            window.speechSynthesis.speak(speech);
        };

        function formatAnnouncerText(text) {
            return text
                .toLowerCase()
                .replace(/\./g, '. ')
                .replace(/,/g, ', ')
                .replace(/-/g, ' ')
                .replace(/\s+/g, ' ')
                .trim();
        }
    </script>
</body>

</html>