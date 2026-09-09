<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Vérifiez votre e-mail</h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
            Un lien de vérification vient de vous être envoyé. Cliquez dessus pour activer votre compte.
            Si vous ne l'avez pas reçu, nous pouvons vous en renvoyer un.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-5 flex items-start gap-2 rounded-xl border border-teal-200 bg-teal-50 px-4 py-3 text-sm text-teal-700 dark:border-teal-700 dark:bg-teal-900/25 dark:text-teal-100">
            <i data-lucide="check-circle" class="mt-0.5 h-4 w-4 shrink-0"></i>
            <span>Un nouveau lien de vérification a été envoyé à votre adresse e-mail.</span>
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-indigo-200/50 transition hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:shadow-none dark:focus:ring-offset-slate-900">
            <i data-lucide="send" class="h-4 w-4"></i>
            Renvoyer l'e-mail de vérification
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="flex w-full items-center justify-center gap-1.5 text-sm font-medium text-slate-500 transition hover:text-rose-600 dark:text-slate-400 dark:hover:text-rose-400">
            <i data-lucide="log-out" class="h-4 w-4"></i>
            Se déconnecter
        </button>
    </form>
</x-guest-layout>
