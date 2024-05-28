<section class="space-y-6 text-white">
    <header>
        Boxes
    </header>
    <div>
        <ul>
            <p>Number of boxes available: {{ $availableBoxes }} 📦</p>
            <p>Number of daily boxes available: {{ $availableDailyBoxes }} ☀📦</p>
            <button type="button" class='inline-flex items-center px-6 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-black dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150' onclick="window.location='{{ url('/admin-boxes') }}'">
                All boxes
            </button>
        </ul>
    </div>
</section>