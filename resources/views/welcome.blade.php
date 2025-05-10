<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>2025 Election Survey</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />


    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body @php use Illuminate\Support\Str; @endphp
    class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 items-center lg:justify-center min-h-screen flex-col">
    <div
        class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        <main>


            <form action="{{ route('vote.store') }}" method="POST" class="rounded border p-6 space-y-6">
                @csrf

                @if (session('error'))
                    <p style="color:red;" class="mb-4">{{ session('error') }}</p>
                @elseif(session('success'))
                    <p style="color:green;" class="mb-4">{{ session('success') }}</p>
                @endif
                <div>
                    <p class="text-2xl font-bold">2025 Election Survey</p>
                    <p class="text-sm text-gray-600">Choose a candidate to cast your 2025 Governor and Vice-Governor
                        Negros Oriental survey.</p>
                </div>

                <div class="mt-2">
                    <p class="font-semibold text-xl ">Governor</p>
                    <div class=" p-[4]">
                        @foreach ($candidates->where('position', 'governor') as $candidate)
                            <div class="rounded candidate-container mb-2"
                                style="border:1px solid gray; border-color: gray;">
                                <label class="flex justify-between items-center cursor-pointer m-2"
                                    for="{{ Str::slug($candidate->name) }}">
                                    <div class="">
                                        <p class="font-semibold">{{ $candidate->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $candidate->party }}</p>
                                    </div>
                                    <input type="radio" id="{{ Str::slug($candidate->name) }}" name="governor_id"
                                        data-group="governor" value="{{ $candidate->id }}" class="h-5 w-5">
                                </label>
                            </div>
                        @endforeach

                    </div>

                    <div class="mt-2">
                        <p class="font-semibold text-xl ">Vice Governor</p>
                        <div class="p-[4]">

                            @foreach ($candidates->where('position', 'vice-governor') as $candidate)
                                <div class="rounded candidate-container mb-2"
                                    style="border:1px solid gray; border-color: gray;">
                                    <label class="flex justify-between items-center cursor-pointer m-2"
                                        for="{{ Str::slug($candidate->name) }}">
                                        <div class="">
                                            <p class="font-semibold">{{ $candidate->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $candidate->party }}</p>
                                        </div>
                                        <input type="radio" id="{{ Str::slug($candidate->name) }}"
                                            name="vice_governor_id" value="{{ $candidate->id }}" class="h-5 w-5"
                                            data-group="vice-governor">
                                    </label>
                                </div>
                            @endforeach

                        </div>

                    </div>

                    <!-- Vice-Governor and submit button to be added here -->
                    <input type="hidden" name="device_cookie" id="device_cookie">
                    <button type="submit" class="mt-4 px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Submit Vote
                    </button>
                </div>
            </form>
        </main>
        <script>
            // Helper to get a cookie by name
            function getCookie(name) {
                let m = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
                return m ? decodeURIComponent(m[2]) : null;
            }

            // Helper to set a cookie
            function setCookie(name, value, days) {
                let expires = "";
                if (days) {
                    let d = new Date();
                    d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
                    expires = "; expires=" + d.toUTCString();
                }
                document.cookie = name + "=" + encodeURIComponent(value) + expires + "; path=/";
            }

            // Check or create our unique vote token
            (function() {
                let token = getCookie('voted_token');
                if (!token) {
                    token = 'vote_' + Date.now() + '_' + Math.random().toString(36).substr(2);
                    setCookie('voted_token', token, 365); // Keep 1 year
                }

                // Populate hidden input with the token
                document.getElementById('device_cookie').value = token;
            })();

            document.addEventListener('DOMContentLoaded', function() {
                // Event listener for radio button change
                document.querySelectorAll('input[type="radio"]').forEach(radio => {
                    radio.addEventListener('change', function() {
                        const groupName = this.getAttribute('name');
                        const allRadios = document.querySelectorAll(`input[name="${groupName}"]`);

                        // Reset the border color for all candidate containers
                        allRadios.forEach(r => {
                            const container = r.closest('.candidate-container');
                            if (container) {
                                container.style.borderColor = 'gray'; // Default color
                            }
                        });

                        // Highlight the selected candidate container
                        const selectedContainer = this.closest('.candidate-container');
                        if (selectedContainer) {
                            selectedContainer.style.borderColor = 'blue'; // Selected color
                            selectedContainer.style.borderWidth = '2px'; // Make border thicker
                        }
                    });
                });

                // Check if there are any pre-selected candidates
                let selectedCandidates = getCookie('selected_candidates');
                console.log(selectedCandidates)
                if (selectedCandidates) {
                    selectedCandidates = JSON.parse(selectedCandidates); // Parse the JSON string

                    // Highlight the selected governor
                    const governorId = selectedCandidates.governor;
                    const governorRadio = document.querySelector(`input[name="governor_id"][value="${governorId}"]`);
                    if (governorRadio) {
                        const governorContainer = governorRadio.closest('.candidate-container');
                        if (governorContainer) {
                            governorContainer.style.borderColor = 'green'; // Highlight with green
                        }
                    }

                    // Highlight the selected vice-governor
                    const viceGovernorId = selectedCandidates.vice_governor;
                    const viceGovernorRadio = document.querySelector(
                        `input[name="vice_governor_id"][value="${viceGovernorId}"]`);
                    if (viceGovernorRadio) {
                        const viceGovernorContainer = viceGovernorRadio.closest('.candidate-container');
                        if (viceGovernorContainer) {
                            viceGovernorContainer.style.borderColor = 'green'; // Highlight with green
                        }
                    }
                }
            });
        </script>

    </div>
</body>



</html>
