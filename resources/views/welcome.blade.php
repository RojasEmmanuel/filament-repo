<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LotificaPro • Gestión Inteligente de Lotificaciones</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#f97316',
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
                        slate: {
                            950: '#0a0f1a',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                        'fade-in': 'fadeIn 1.4s ease-out forwards',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(32px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-12px)' },
                        }
                    },
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 min-h-screen antialiased">

    <!-- Hero -->
    <header class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-500/5 via-transparent to-amber-500/5 dark:from-primary-900/20 dark:via-transparent dark:to-amber-900/10"></div>
        
        <div class="relative max-w-7xl mx-auto px-6 pt-24 pb-32 lg:pt-32 lg:pb-48 text-center">
            <div class="animate-[fadeIn_1.4s] opacity-0 [animation-delay:200ms] [animation-fill-mode:forwards]">
                <span class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold bg-primary-100/80 dark:bg-primary-900/30 text-primary-800 dark:text-primary-300 rounded-full border border-primary-200/50 dark:border-primary-800/40 backdrop-blur-sm">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-primary-500"></span>
                    </span>
                    Plataforma #1 para lotificadoras 2026
                </span>
            </div>

            <h1 class="mt-10 text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-extrabold tracking-tight leading-none animate-[fadeInUp_1s] opacity-0 [animation-delay:400ms] [animation-fill-mode:forwards]">
                Gestiona tu lotificadora<br class="hidden sm:block">
                <span class="bg-gradient-to-r from-primary-600 via-amber-500 to-primary-500 bg-clip-text text-transparent">con inteligencia y control total</span>
            </h1>

            <p class="mt-8 text-lg sm:text-xl lg:text-2xl text-slate-600 dark:text-slate-300 max-w-4xl mx-auto font-light animate-[fadeInUp_1s] opacity-0 [animation-delay:600ms] [animation-fill-mode:forwards]">
                Control en tiempo real de lotes • Cobranza automatizada • Contratos digitales • Reportes ejecutivos • Todo en una plataforma moderna y fácil de usar.
            </p>

            <div class="mt-12 flex flex-col sm:flex-row gap-5 justify-center animate-[fadeInUp_1s] opacity-0 [animation-delay:800ms] [animation-fill-mode:forwards]">
                <a href="{{ url('/admin') }}"
                   class="group relative inline-flex items-center gap-3 px-10 py-6 text-lg font-semibold text-white bg-gradient-to-r from-primary-600 to-amber-600 rounded-2xl shadow-2xl shadow-primary-500/30 hover:shadow-primary-600/50 hover:scale-[1.04] transition-all duration-400 overflow-hidden">
                    <span class="relative z-10">Ingresar al Sistema</span>
                    <svg class="w-6 h-6 relative z-10 group-hover:translate-x-1.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                    <div class="absolute inset-0 bg-gradient-to-r from-primary-700 to-amber-700 scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-400"></div>
                </a>

                <a href="#caracteristicas"
                   class="inline-flex items-center px-10 py-6 text-lg font-semibold border-2 border-slate-300 dark:border-slate-700 hover:border-primary-500 rounded-2xl hover:bg-primary-50/50 dark:hover:bg-primary-950/30 transition-all duration-300">
                    Explorar funcionalidades
                </a>
            </div>
        </div>
    </header>

    <!-- Trust Bar -->
    <section class="py-12 border-t border-b border-slate-200/70 dark:border-slate-800/70 bg-white/60 dark:bg-slate-900/40 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-sm uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-8 font-medium">
                Empresas que ya transformaron su operación
            </p>
            <div class="flex flex-wrap justify-center items-center gap-12 md:gap-16 lg:gap-24 opacity-70 grayscale">
                <div class="text-3xl lg:text-4xl font-black text-slate-300 dark:text-slate-600">LotificaMX</div>
                <div class="text-3xl lg:text-4xl font-black text-slate-300 dark:text-slate-600">Terrenos del Sol</div>
                <div class="text-3xl lg:text-4xl font-black text-slate-300 dark:text-slate-600">Fracc. Primavera</div>
                <div class="text-3xl lg:text-4xl font-black text-slate-300 dark:text-slate-600">+180 lotificadoras</div>
            </div>
        </div>
    </section>

    <!-- Características clave -->
    <section id="caracteristicas" class="py-24 lg:py-32">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20">
                <h2 class="text-4xl lg:text-5xl font-bold tracking-tight mb-6 animate-[fadeInUp_1s] opacity-0 [animation-delay:200ms] [animation-fill-mode:forwards]">
                    Todo lo que tu lotificadora necesita
                </h2>
                <p class="text-xl text-slate-600 dark:text-slate-300 max-w-3xl mx-auto animate-[fadeInUp_1s] opacity-0 [animation-delay:400ms] [animation-fill-mode:forwards]">
                    Un sistema diseñado por y para quienes viven el día a día de la venta de terrenos.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12">
                <!-- Tarjeta 1 -->
                <div class="group bg-white dark:bg-slate-800/70 backdrop-blur-sm p-8 lg:p-10 rounded-3xl shadow-xl border border-slate-200/60 dark:border-slate-700/50 hover:border-primary-500/50 hover:shadow-2xl hover:-translate-y-3 transition-all duration-400 animate-[fadeInUp_1s] opacity-0 [animation-delay:300ms] [animation-fill-mode:forwards]">
                    <div class="w-16 h-16 rounded-2xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center mb-8 text-primary-600 dark:text-primary-400 group-hover:scale-110 transition-transform duration-400">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Control total de inventario</h3>
                    <p class="text-slate-600 dark:text-slate-300 text-lg leading-relaxed">Estado en tiempo real: disponible, reservado, enganchado, vendido. Mapa interactivo + filtros avanzados.</p>
                </div>

                <!-- Tarjeta 2 -->
                <div class="group bg-white dark:bg-slate-800/70 backdrop-blur-sm p-8 lg:p-10 rounded-3xl shadow-xl border border-slate-200/60 dark:border-slate-700/50 hover:border-primary-500/50 hover:shadow-2xl hover:-translate-y-3 transition-all duration-400 animate-[fadeInUp_1s] opacity-0 [animation-delay:450ms] [animation-fill-mode:forwards]">
                    <div class="w-16 h-16 rounded-2xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center mb-8 text-primary-600 dark:text-primary-400 group-hover:scale-110 transition-transform duration-400">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Cobranza inteligente</h3>
                    <p class="text-slate-600 dark:text-slate-300 text-lg leading-relaxed">Recordatorios automáticos, pagos por enlace, historial completo, alertas de morosidad y proyecciones de flujo.</p>
                </div>

                <!-- Tarjeta 3 -->
                <div class="group bg-white dark:bg-slate-800/70 backdrop-blur-sm p-8 lg:p-10 rounded-3xl shadow-xl border border-slate-200/60 dark:border-slate-700/50 hover:border-primary-500/50 hover:shadow-2xl hover:-translate-y-3 transition-all duration-400 animate-[fadeInUp_1s] opacity-0 [animation-delay:600ms] [animation-fill-mode:forwards]">
                    <div class="w-16 h-16 rounded-2xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center mb-8 text-primary-600 dark:text-primary-400 group-hover:scale-110 transition-transform duration-400">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2-5a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Reportes ejecutivos instantáneos</h3>
                    <p class="text-slate-600 dark:text-slate-300 text-lg leading-relaxed">Ventas por vendedor, ingresos proyectados, inventario disponible, morosidad y más — listos en 1 clic.</p>
                </div>

                <!-- Puedes agregar 3 tarjetas más aquí con delays 750ms, 900ms, 1050ms -->
            </div>
        </div>
    </section>

    <!-- Sección transformación (versión anterior mejorada) -->
    <section class="py-24 lg:py-32 relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold tracking-tight animate-[fadeInUp_1s] opacity-0 [animation-delay:200ms] [animation-fill-mode:forwards]">
                    Del caos manual...<br class="hidden sm:block">
                    <span class="text-primary-600 dark:text-primary-400">al control profesional</span>
                </h2>
            </div>

            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <!-- Antes -->
                <div class="relative animate-[fadeInUp_1.2s] opacity-0 [animation-delay:300ms] [animation-fill-mode:forwards]">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-600/5 to-rose-600/5 dark:from-red-900/20 dark:to-rose-900/20 rounded-3xl -rotate-1"></div>
                    <div class="relative bg-white/90 dark:bg-slate-800/90 backdrop-blur-md p-10 rounded-3xl shadow-2xl border border-red-200/40 dark:border-red-800/30">
                        <div class="flex items-center gap-4 mb-8">
                            <span class="text-5xl font-black text-red-500/30">×</span>
                            <h3 class="text-3xl font-bold text-red-700 dark:text-red-400">Antes</h3>
                        </div>
                        <ul class="space-y-6 text-lg text-slate-700 dark:text-slate-300">
                            <li class="flex items-start gap-4"><span class="text-2xl text-red-500">×</span> <span>Archivos Excel desactualizados y versiones perdidas</span></li>
                            <li class="flex items-start gap-4"><span class="text-2xl text-red-500">×</span> <span>Clientes que llaman insistentemente sin registro claro</span></li>
                            <li class="flex items-start gap-4"><span class="text-2xl text-red-500">×</span> <span>Imposible saber ventas reales del mes en tiempo real</span></li>
                            <li class="flex items-start gap-4"><span class="text-2xl text-red-500">×</span> <span>Reportes que toman días enteros de trabajo manual</span></li>
                        </ul>
                    </div>
                </div>

                <!-- Flecha -->
                <div class="hidden lg:block text-center animate-[fadeIn_1.4s] opacity-0 [animation-delay:500ms] [animation-fill-mode:forwards]">
                    <div class="inline-block w-40 h-40 rounded-full bg-gradient-to-br from-primary-500 to-amber-500 p-2 shadow-2xl animate-float">
                        <div class="w-full h-full rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center">
                            <svg class="w-24 h-24 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Después -->
                <div class="relative animate-[fadeInUp_1.2s] opacity-0 [animation-delay:700ms] [animation-fill-mode:forwards]">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/5 to-green-600/5 dark:from-emerald-900/20 dark:to-green-900/20 rounded-3xl rotate-1"></div>
                    <div class="relative bg-white/90 dark:bg-slate-800/90 backdrop-blur-md p-10 rounded-3xl shadow-2xl border border-emerald-200/40 dark:border-emerald-800/30">
                        <div class="flex items-center gap-4 mb-8">
                            <span class="text-5xl font-black text-emerald-500/30">✓</span>
                            <h3 class="text-3xl font-bold text-emerald-700 dark:text-emerald-400">Después</h3>
                        </div>
                        <ul class="space-y-6 text-lg text-slate-700 dark:text-slate-300">
                            <li class="flex items-start gap-4"><span class="text-2xl text-emerald-500">✓</span> <span>Dashboard vivo con métricas al instante</span></li>
                            <li class="flex items-start gap-4"><span class="text-2xl text-emerald-500">✓</span> <span>Pagos por enlace — sin perseguir clientes</span></li>
                            <li class="flex items-start gap-4"><span class="text-2xl text-emerald-500">✓</span> <span>Reportes ejecutivos listos en segundos</span></li>
                            <li class="flex items-start gap-4"><span class="text-2xl text-emerald-500">✓</span> <span>Equipo 100% enfocado en cerrar ventas</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section class="py-32 bg-gradient-to-br from-primary-600 via-amber-600 to-primary-700 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_70%,rgba(255,255,255,0.12),transparent_60%)]"></div>
        
        <div class="relative max-w-5xl mx-auto px-6 text-center">
            <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight mb-8 animate-[fadeInUp_1s] opacity-0 [animation-delay:200ms] [animation-fill-mode:forwards]">
                ¿Listo para profesionalizar tu lotificadora?
            </h2>
            <p class="text-xl sm:text-2xl mb-12 opacity-90 animate-[fadeInUp_1s] opacity-0 [animation-delay:400ms] [animation-fill-mode:forwards]">
                Miles de horas recuperadas. Más ventas cerradas. Cero estrés operativo.
            </p>
            <a href="{{ url('/admin') }}"
               class="group relative inline-flex items-center gap-4 px-14 py-8 text-2xl font-bold bg-white text-primary-700 rounded-3xl shadow-2xl hover:shadow-4xl hover:scale-[1.05] transition-all duration-400 overflow-hidden">
                <span class="relative z-10">Entrar al Sistema Ahora</span>
                <svg class="w-8 h-8 relative z-10 group-hover:translate-x-3 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
                <div class="absolute inset-0 bg-gradient-to-r from-amber-100 to-primary-100 scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-500"></div>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-16 text-center text-slate-500 dark:text-slate-400 border-t border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-950/40 backdrop-blur-sm">
        <p class="text-lg">© {{ date('Y') }} LotificaPro • Sistema de Gestión de Lotificaciones • Hecho en México</p>
    </footer>

</body>
</html>