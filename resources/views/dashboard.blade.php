<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form id="shortenForm" class="mb-6">
                        @csrf
                        <div class="mb-4">
                            <label for="url" class="block text-sm font-medium text-gray-700 mb-2">
                                Put your URL here
                            </label>
                            <div class="flex">
                                <input type="text" id="url" name="url" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <button type="button" id="shortenButton"
                                        class="px-4 py-2 text-white rounded-r-md"
                                        style="background-color: #4768c9;"
                                        onmouseover="this.style.backgroundColor='#778fd9'"
                                        onmouseout="this.style.backgroundColor='#4768c9'">Shorten
                                </button>
                            </div>
                        </div>
                    </form>

                    <div id="message-container">
{{--                        @if (session('success'))--}}
{{--                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">--}}
{{--                                <span class="block sm:inline">{{ session('success') }}</span>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                        @if (session('error'))--}}
{{--                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">--}}
{{--                                <span class="block sm:inline">{{ session('error') }}</span>--}}
{{--                            </div>--}}
{{--                        @endif--}}
                    </div>

                    @if($links->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">No links available.</p>
                    @else
                        <h2 class="text-2xl font-bold mb-4 text-center">Your Links</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full bg-white border border-gray-300 table-auto">
                                <thead>
                                <tr>
                                    <th class="px-4 py-2 border">Original Link</th>
                                    <th class="px-4 py-2 border">Short Link</th>
                                    <th class="px-4 py-2 border">Created At</th>
                                    <th class="px-4 py-2 border">Valid To</th>
                                    <th class="px-4 py-2 border">Click Count</th>
                                    <th class="px-4 py-2 border">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($links as $link)
                                    <tr data-link-id="{{ $link->id }}">
                                        <td class="px-4 py-2 border truncate max-w-xs">
                                            <a href="{{ $link->url }}" target="_blank" class="text-blue-600 hover:underline">
                                                {{ $link->url }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 border">
                                            <a href="{{ route('links.redirect', ['short_link' => $link->short_link]) }}"
                                               target="_blank" class="text-blue-600 hover:underline">
                                                {{ $link->short_link }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 border">{{ $link->created_at->format('Y.m.d H:i') }}</td>
                                        <td class="px-4 py-2 border" data-field="valid_until">
                                            {{ $link->valid_until ? $link->valid_until->format('Y.m.d H:i') : 'No expiration' }}</td>
                                        <td class="px-4 py-2 border">{{ $link->click_count }}</td>
                                        <td class="px-4 py-2 border">
                                            <div class="flex space-x-2">
                                                <form id="updateForm" data-link-id="{{ $link->id }}">
                                                    @csrf
                                                    <button type="button" id="updateButton" class="px-4 py-2 text-white rounded"
                                                            style="background-color: #4768c9;"
                                                            onmouseover="this.style.backgroundColor='#778fd9'"
                                                            onmouseout="this.style.backgroundColor='#4768c9'">Update
                                                    </button>
                                                </form>
                                                <form id="deleteForm" data-link-id="{{ $link->id }}">
                                                    @csrf
                                                    <button type="button" id="deleteButton"
                                                            class="px-4 py-2 bg-red-400 text-white rounded hover:bg-red-300">Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script @vite(['resources/js/fetch.js'])></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</x-app-layout>
