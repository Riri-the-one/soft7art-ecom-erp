<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Nouveau mot de passe</h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Choisissez un mot de passe pour votre compte.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Adresse e-mail</label>
            <div class="relative mt-1.5">
                <i data-lucide="mail" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                    class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-950 dark:text-gray-100 dark:focus:ring-indigo-900/40">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-5">
            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nouveau mot de passe</label>
            <div class="relative mt-1.5">
                <i data-lucide="lock" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-950 dark:text-gray-100 dark:focus:ring-indigo-900/40">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-5">
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Confirmer le mot de passe</label>
            <div class="relative mt-1.5">
                <i data-lucide="lock" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-950 dark:text-gray-100 dark:focus:ring-indigo-900/40">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-indigo-200/50 transition hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:shadow-none dark:focus:ring-offset-slate-900">
            <i data-lucide="key-round" class="h-4 w-4"></i>
            Réinitialiser le mot de passe
        </button>
    </form>
</x-guest-layout>
