<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'ERP') }} — ERP e-commerce</title>
    <meta name="description" content="Plateforme de gestion e-commerce : commandes, catalogue et stock, CRM clients, statistiques et suivi de livraison.">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    {{-- Même initialisation du thème que dans l'application, pour éviter un
         flash de la mauvaise couleur au chargement. --}}
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!localStorage.getItem('color-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-gray-100">

    {{-- Halos décoratifs en arrière-plan --}}
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute -top-40 -left-40 h-96 w-96 rounded-full bg-indigo-200/50 blur-3xl dark:bg-indigo-500/10"></div>
        <div class="absolute -top-20 right-0 h-96 w-96 rounded-full bg-violet-200/50 blur-3xl dark:bg-violet-500/10"></div>
    </div>

    <div class="relative">

        {{-- ================= EN-TÊTE ================= --}}
        <header class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-6 py-6 lg:px-8">
            <a href="/" class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white shadow-md shadow-indigo-200/60 dark:shadow-none">
                    <i data-lucide="boxes" class="h-6 w-6"></i>
                </span>
                <span class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">{{ config('app.name', 'ERP') }}</span>
            </a>

            <div class="flex items-center gap-2 sm:gap-3">
                <button id="theme-toggle" type="button" aria-label="Changer de thème" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/70 p-2.5 text-slate-500 backdrop-blur transition hover:bg-white dark:border-slate-800 dark:bg-slate-900/70 dark:text-slate-300 dark:hover:bg-slate-900">
                    <i id="theme-toggle-dark-icon" data-lucide="moon" class="hidden h-5 w-5"></i>
                    <i id="theme-toggle-light-icon" data-lucide="sun" class="hidden h-5 w-5"></i>
                </button>

                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-200/50 transition hover:shadow-lg dark:shadow-none">
                        <i data-lucide="layout-dashboard" class="h-4 w-4"></i>
                        <span class="hidden sm:inline">Tableau de bord</span>
                        <span class="sm:hidden">Entrer</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-200/50 transition hover:shadow-lg dark:shadow-none">
                        <i data-lucide="log-in" class="h-4 w-4"></i> Se connecter
                    </a>
                @endauth
            </div>
        </header>

        {{-- ================= HERO ================= --}}
        <section class="mx-auto max-w-7xl px-6 pb-16 pt-10 lg:px-8 lg:pt-20">
            <div class="grid items-center gap-12 lg:grid-cols-2">

                <div>
                    <span class="inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700 dark:border-indigo-500/20 dark:bg-indigo-500/10 dark:text-indigo-300">
                        <i data-lucide="zap" class="h-3.5 w-3.5"></i>
                        Pensé pour le paiement à la livraison
                    </span>

                    <h1 class="mt-6 text-4xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-5xl lg:text-6xl dark:text-white">
                        Pilotez votre activité
                        <span class="bg-gradient-to-r from-indigo-500 to-violet-600 bg-clip-text text-transparent">e-commerce</span>
                        d'un seul endroit.
                    </h1>

                    <p class="mt-6 max-w-xl text-lg leading-relaxed text-slate-600 dark:text-slate-400">
                        Commandes, catalogue et stock, fiches clients, statistiques et suivi de livraison :
                        toute la chaîne, de la prise de commande au colis livré, dans une seule interface.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-indigo-200/50 transition hover:shadow-lg dark:shadow-none">
                                <i data-lucide="layout-dashboard" class="h-4 w-4"></i> Accéder au tableau de bord
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-indigo-200/50 transition hover:shadow-lg dark:shadow-none">
                                <i data-lucide="log-in" class="h-4 w-4"></i> Accéder à mon espace
                            </a>
                        @endauth

                        <a href="#modules" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                            Découvrir les modules
                            <i data-lucide="arrow-down" class="h-4 w-4"></i>
                        </a>
                    </div>
                </div>

                {{-- Aperçu stylisé de l'interface --}}
                <div class="relative">
                    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-[0_30px_80px_-40px_rgba(15,23,42,0.35)] dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-4 dark:border-slate-800">
                            <span class="h-3 w-3 rounded-full bg-rose-400"></span>
                            <span class="h-3 w-3 rounded-full bg-amber-400"></span>
                            <span class="h-3 w-3 rounded-full bg-emerald-400"></span>
                            <span class="ml-3 text-xs font-medium text-slate-400">Tableau de bord</span>
                        </div>

                        <div class="grid gap-3 pt-5 sm:grid-cols-2">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                                    <i data-lucide="shopping-cart" class="h-4 w-4"></i>
                                </span>
                                <p class="mt-3 text-2xl font-bold text-slate-900 dark:text-white">128</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Commandes du mois</p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-50 text-teal-600 dark:bg-teal-500/10 dark:text-teal-400">
                                    <i data-lucide="truck" class="h-4 w-4"></i>
                                </span>
                                <p class="mt-3 text-2xl font-bold text-slate-900 dark:text-white">87 %</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Taux de livraison</p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:col-span-2 dark:border-slate-800 dark:bg-slate-950">
                                <div class="flex items-center justify-between">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Chiffre d'affaires</p>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                                        <i data-lucide="trending-up" class="h-3 w-3"></i> +12 %
                                    </span>
                                </div>
                                <div class="mt-4 flex h-20 items-end gap-1.5">
                                    <div class="h-[35%] flex-1 rounded-t-md bg-gradient-to-t from-indigo-500 to-violet-500 opacity-40"></div>
                                    <div class="h-[55%] flex-1 rounded-t-md bg-gradient-to-t from-indigo-500 to-violet-500 opacity-50"></div>
                                    <div class="h-[40%] flex-1 rounded-t-md bg-gradient-to-t from-indigo-500 to-violet-500 opacity-45"></div>
                                    <div class="h-[70%] flex-1 rounded-t-md bg-gradient-to-t from-indigo-500 to-violet-500 opacity-60"></div>
                                    <div class="h-[60%] flex-1 rounded-t-md bg-gradient-to-t from-indigo-500 to-violet-500 opacity-55"></div>
                                    <div class="h-[85%] flex-1 rounded-t-md bg-gradient-to-t from-indigo-500 to-violet-500 opacity-80"></div>
                                    <div class="h-full flex-1 rounded-t-md bg-gradient-to-t from-indigo-500 to-violet-600"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ================= MODULES ================= --}}
        <section id="modules" class="mx-auto max-w-7xl scroll-mt-8 px-6 py-16 lg:px-8">
            <div class="max-w-2xl">
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl dark:text-white">Les modules</h2>
                <p class="mt-4 text-slate-600 dark:text-slate-400">
                    Chaque métier dispose de son espace, avec les seules informations dont il a besoin.
                </p>
            </div>

            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @php
                    $modules = [
                        [
                            'icon' => 'shopping-cart',
                            'title' => 'Commandes',
                            'text' => "Saisie rapide, vue liste ou Kanban, changement de statut suivi pas à pas et détection automatique des doublons.",
                            'tone' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400',
                        ],
                        [
                            'icon' => 'package',
                            'title' => 'Catalogue et stock',
                            'text' => "Fiches produits, marge protégée, alertes de rupture, emplacement en rayon et étiquettes code-barres imprimables.",
                            'tone' => 'bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400',
                        ],
                        [
                            'icon' => 'users',
                            'title' => 'CRM clients',
                            'text' => "Historique complet, taux de livraison par client et contact direct par WhatsApp en un clic.",
                            'tone' => 'bg-teal-50 text-teal-600 dark:bg-teal-500/10 dark:text-teal-400',
                        ],
                        [
                            'icon' => 'bar-chart-3',
                            'title' => 'Statistiques',
                            'text' => "Chiffre d'affaires, bénéfice net réel calculé sur les marges, et évolution du volume de commandes.",
                            'tone' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                        ],
                        [
                            'icon' => 'map-pin',
                            'title' => 'Suivi de livraison',
                            'text' => "Un lien public et sécurisé par jeton, à envoyer au client pour qu'il suive son colis sans créer de compte.",
                            'tone' => 'bg-cyan-50 text-cyan-600 dark:bg-cyan-500/10 dark:text-cyan-400',
                        ],
                        [
                            'icon' => 'file-spreadsheet',
                            'title' => 'Logistique',
                            'text' => "Bons de livraison au format thermique et export CSV prêt à charger chez le transporteur.",
                            'tone' => 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400',
                        ],
                    ];
                @endphp

                @foreach($modules as $module)
                    <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl {{ $module['tone'] }}">
                            <i data-lucide="{{ $module['icon'] }}" class="h-5 w-5"></i>
                        </span>
                        <h3 class="mt-5 text-lg font-bold text-slate-900 dark:text-white">{{ $module['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ $module['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ================= RÔLES ================= --}}
        <section class="mx-auto max-w-7xl px-6 pb-20 lg:px-8">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="border-b border-slate-100 px-8 py-8 dark:border-slate-800">
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Trois profils, trois vues</h2>
                    <p class="mt-2 text-slate-600 dark:text-slate-400">
                        Les droits sont contrôlés côté serveur : chacun n'accède qu'à ce qui relève de son poste.
                    </p>
                </div>

                <div class="grid divide-y divide-slate-100 sm:grid-cols-3 sm:divide-x sm:divide-y-0 dark:divide-slate-800">
                    @php
                        $roles = [
                            ['icon' => 'shield-check', 'title' => 'Directeur', 'text' => "Vision complète : finances, marges, statistiques et classement des agents."],
                            ['icon' => 'headset', 'title' => 'Agent', 'text' => "Prise de commandes, suivi des statuts et relation client au quotidien."],
                            ['icon' => 'warehouse', 'title' => 'Gestionnaire de stock', 'text' => "Catalogue, réapprovisionnement et préparation des colis en entrepôt."],
                        ];
                    @endphp

                    @foreach($roles as $role)
                        <div class="p-8">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white">
                                <i data-lucide="{{ $role['icon'] }}" class="h-5 w-5"></i>
                            </span>
                            <h3 class="mt-5 font-bold text-slate-900 dark:text-white">{{ $role['title'] }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ $role['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ================= PIED DE PAGE ================= --}}
        <footer class="border-t border-slate-200 bg-white/60 backdrop-blur dark:border-slate-800 dark:bg-slate-900/60">
            <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 px-6 py-8 sm:flex-row lg:px-8">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white">
                        <i data-lucide="boxes" class="h-4 w-4"></i>
                    </span>
                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ config('app.name', 'ERP') }}</span>
                </div>

                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Projet de fin d'études &middot; {{ date('Y') }}
                </p>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/lucide@0.462.0/dist/umd/lucide.min.js" defer></script>
    <script>
        function renderIcons() {
            if (window.lucide) window.lucide.createIcons();
        }
        document.addEventListener('DOMContentLoaded', renderIcons);
        window.addEventListener('load', renderIcons);

        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        function setTheme(isDark) {
            if (isDark) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
                if (themeToggleLightIcon) themeToggleLightIcon.classList.remove('hidden');
                if (themeToggleDarkIcon) themeToggleDarkIcon.classList.add('hidden');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
                if (themeToggleDarkIcon) themeToggleDarkIcon.classList.remove('hidden');
                if (themeToggleLightIcon) themeToggleLightIcon.classList.add('hidden');
            }
        }

        var storedTheme = localStorage.getItem('color-theme');
        var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        setTheme(storedTheme === 'dark' || (!storedTheme && prefersDark));

        var themeToggleBtn = document.getElementById('theme-toggle');
        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function () {
                setTheme(!document.documentElement.classList.contains('dark'));
            });
        }
    </script>
</body>
</html>
