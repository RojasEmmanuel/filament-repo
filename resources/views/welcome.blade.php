<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Lotificación - Bienvenido</title>

    <!-- Tailwind CSS CDN (para desarrollo / prototipo - en producción compila) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        },
                        secondary: '#1e293b',
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                        'fade-in': 'fadeIn 1.2s ease-out forwards',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                    },
                }
            }
        }
    </script>

    <!-- Google Fonts (opcional - puedes quitarlo) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 via-white to-orange-50/40 dark:from-gray-950 dark:via-gray-900 dark:to-gray-800 text-gray-900 dark:text-gray-100 min-h-screen">

    <!-- Hero Section -->
    <header class="relative overflow-hidden">
        <!-- Fondo con gradiente y patrón sutil -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(251,146,60,0.08),transparent_50%)] dark:bg-[radial-gradient(circle_at_30%_20%,rgba(251,146,60,0.12),transparent_50%)]"></div>

        <div class="relative max-w-7xl mx-auto px-6 pt-16 pb-24 lg:pt-24 lg:pb-32 text-center">
            <div class="animate-[fadeIn_1.2s_ease-out] opacity-0 [animation-delay:200ms] [animation-fill-mode:forwards]">
                <span class="inline-block px-4 py-1.5 text-sm font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300 rounded-full mb-6 tracking-wide">
                    Sistema de Gestión Profesional
                </span>
            </div>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight leading-tight mb-6 animate-[fadeInUp_0.9s_ease-out] opacity-0 [animation-delay:400ms] [animation-fill-mode:forwards]">
                Gestiona tus ventas de lotes<br class="hidden sm:block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-amber-600">de forma inteligente</span>
            </h1>

            <p class="text-lg md:text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto mb-10 animate-[fadeInUp_0.9s_ease-out] opacity-0 [animation-delay:600ms] [animation-fill-mode:forwards]">
                Control total de lotes, clientes, pagos, contratos, reportes y seguimiento en tiempo real. 
                Simplifica la administración de tu negocio de lotificación.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center animate-[fadeInUp_0.9s_ease-out] opacity-0 [animation-delay:800ms] [animation-fill-mode:forwards]">
                <a href="{{ url('/admin') }}"
                   class="inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-white bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 rounded-xl shadow-lg shadow-orange-500/20 hover:shadow-orange-500/30 transition-all duration-300 transform hover:scale-[1.03] focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                    Ingresar al Sistema →
                </a>

                <a href="#caracteristicas"
                   class="inline-flex items-center justify-center px-8 py-4 text-base font-semibold border-2 border-gray-300 dark:border-gray-600 hover:border-orange-500 rounded-xl transition-all duration-300 hover:bg-orange-50 dark:hover:bg-orange-950/30">
                    Conocer más
                </a>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section id="caracteristicas" class="py-20 lg:py-32 bg-white/60 dark:bg-gray-900/40 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 animate-[fadeInUp_0.8s_ease-out] opacity-0 [animation-delay:200ms] [animation-fill-mode:forwards]">
                    Todo lo que necesitas para tu negocio
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto animate-[fadeInUp_0.8s_ease-out] opacity-0 [animation-delay:400ms] [animation-fill-mode:forwards]">
                    Un sistema completo diseñado específicamente para la venta y administración de lotes y fraccionamientos.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
                <!-- Feature 1 -->
                <div class="bg-white dark:bg-gray-800/70 p-8 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700/50 hover:border-orange-500/50 transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 group animate-[fadeInUp_0.9s_ease-out] opacity-0 [animation-delay:300ms] [animation-fill-mode:forwards]">
                    <div class="w-14 h-14 rounded-xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center mb-6 text-orange-600 dark:text-orange-400 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Control de Lotes</h3>
                    <p class="text-gray-600 dark:text-gray-400">Estado en tiempo real: disponible, reservado, vendido, enganchado. Mapa interactivo y filtros avanzados.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white dark:bg-gray-800/70 p-8 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700/50 hover:border-orange-500/50 transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 group animate-[fadeInUp_0.9s_ease-out] opacity-0 [animation-delay:500ms] [animation-fill-mode:forwards]">
                    <div class="w-14 h-14 rounded-xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center mb-6 text-orange-600 dark:text-orange-400 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a5 5 0 00-10 0v2m-2 0h14a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2v-7a2 2 0 012-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Gestión de Pagos</h3>
                    <p class="text-gray-600 dark:text-gray-400">Seguimiento de cuotas, morosidad, recibos automáticos, historial completo y alertas.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white dark:bg-gray-800/70 p-8 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700/50 hover:border-orange-500/50 transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 group animate-[fadeInUp_0.9s_ease-out] opacity-0 [animation-delay:700ms] [animation-fill-mode:forwards]">
                    <div class="w-14 h-14 rounded-xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center mb-6 text-orange-600 dark:text-orange-400 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Reportes & Dashboards</h3>
                    <p class="text-gray-600 dark:text-gray-400">Gráficas interactivas, ventas por vendedor, ingresos proyectados, inventario y más.</p>
                </div>
            </div>

            <div class="text-center mt-16 animate-[fadeInUp_0.9s_ease-out] opacity-0 [animation-delay:900ms] [animation-fill-mode:forwards]">
                <a href="{{ url('/admin') }}"
                   class="inline-flex items-center px-10 py-5 text-lg font-semibold text-white bg-gradient-to-r from-orange-500 to-amber-600 rounded-xl shadow-2xl shadow-orange-500/30 hover:shadow-orange-500/50 hover:from-orange-600 hover:to-amber-700 transition-all duration-300 transform hover:scale-105">
                    Acceder al Panel de Administración →
                </a>
            </div>
        </div>
    </section>

    <!-- Footer simple -->
    <footer class="py-12 text-center text-gray-500 dark:text-gray-400 border-t border-gray-200 dark:border-gray-800">
        <p>&copy; {{ date('Y') }} Sistema de Gestión de Lotificación. Todos los derechos reservados.</p>
    </footer>

</body>
</html>