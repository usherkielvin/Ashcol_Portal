<x-guest-layout>
    <div class="text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100">Welcome to Ashcol Portal</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Ticketing, dashboards, and role-based access for Admin, Staff, and Customers.</p>

        <div class="mt-6 flex items-center justify-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-md bg-indigo-600 text-white font-semibold hover:bg-indigo-700">Go to Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-md bg-indigo-600 text-white font-semibold hover:bg-indigo-700">Login</a>
                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-md bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 ring-1 ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">Register</a>
            @endauth
        </div>
    </div>

    <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Ticketing System</h3>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Create, track, and manage tickets with comments, priorities, and statuses.</p>
        </div>
        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Role-based Dashboards</h3>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Dedicated views for Admin, Staff, and Customers for faster workflows.</p>
        </div>
        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Secure & Modern</h3>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Laravel 11, Breeze, Tailwind CSS, and robust authorization policies.</p>
        </div>
    </div>

    <div class="mt-10 text-center text-sm text-gray-500 dark:text-gray-400">
        <p>Need a quick start? Use admin@example.com / password to explore.</p>
    </div>
</x-guest-layout>
