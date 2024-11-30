<link href="{{ mix('css/app.css') }}" rel="stylesheet">
<link href="{{ asset('css/sentiment_analysis.css') }}" rel="stylesheet">

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center mb-6">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex-1">
                {{ __('Sentiment History') }}
            </h2>
            <div class="relative">
            <button 
                    id="dropdown-button" 
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    Filter
                 </button>
                <div 
                    id="dropdown-menu" 
                    class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg hidden z-10">
                    <button class="dropdown-item w-full text-left px-4 py-2 text-gray-700 hover:bg-blue-100" data-filter="all">
                        All
                    </button>
                    <button class="dropdown-item w-full text-left px-4 py-2 text-gray-700 hover:bg-green-100" data-filter="Positive">
                        Positive
                    </button>
                    <button class="dropdown-item w-full text-left px-4 py-2 text-gray-700 hover:bg-yellow-100" data-filter="Neutral">
                        Neutral
                    </button>
                    <button class="dropdown-item w-full text-left px-4 py-2 text-gray-700 hover:bg-red-100" data-filter="Negative">
                        Negative
                    </button>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="container">
        @if($sentiments->isEmpty())
            <div class="text-center text-gray-500">
                <p>No sentiment analyses have been performed yet.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($sentiments as $sentiment)
                <div class="bg-white p-6 rounded-3xl shadow-lg border border-gray-200 sentiment-card" data-grade="{{ $sentiment->grade }}">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Analysis for:</h2>
                       <button 
                            class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 delete-sentiment"
                            data-id="{{ $sentiment->id }}">
                            &times;
                        </button>
                    </div>

                    <!-- Display a shortened version of the text -->
                    <p class="text-gray-700 mb-4 font-medium">
                        {!! \Illuminate\Support\Str::limit($sentiment->highlighted_text, 150, '...') !!}
                        @if(strlen(strip_tags($sentiment->highlighted_text)) > 150)
                            <a href="#" data-id="{{ $sentiment->id }}" class="text-blue-500 underline read-more">Read More</a>
                        @endif
                    </p>
                    <!-- Sentiment Score -->
                    <div class="mt-4">
                        <h3 class="text-md font-semibold text-gray-800">Sentiment Score</h3>
                        <div class="score-container bg-gray-100 rounded-full h-6 relative mt-2">
                            <div class="score-indicator rounded-full h-full" 
                                 style="width: {{ (($sentiment->score + 1) / 2) * 100 }}%; background-color: #3b82f6;">
                            </div>
                        </div>
                        <div class="flex justify-between text-sm mt-2">
                            <span class="text-red-500">Negative</span>
                            <span class="text-gray-500">Neutral</span>
                            <span class="text-blue-500">Positive</span>
                        </div>
                    </div>

                    <!-- Metrics -->
                    <div class="grid grid-cols-2 gap-4 mt-6">
                        <!-- Positive Count -->
                        <div class="bg-blue-100 shadow-lg rounded-3xl flex flex-col items-center p-4">
                            <i class="fas fa-smile text-blue-600 text-2xl"></i>
                            <h4 class="text-blue-600 mt-2 text-sm font-semibold">Positive Count</h4>
                            <p class="text-blue-600 font-bold text-xl">{{ $sentiment->positive_count }}</p>
                        </div>

                        <!-- Negative Count -->
                        <div class="bg-red-100 shadow-lg rounded-3xl flex flex-col items-center p-4">
                            <i class="fas fa-frown text-red-600 text-2xl"></i>
                            <h4 class="text-red-600 mt-2 text-sm font-semibold">Negative Count</h4>
                            <p class="text-red-600 font-bold text-xl">{{ $sentiment->negative_count }}</p>
                        </div>

                        <!-- Neutral Count -->
                        <div class="bg-green-100 shadow-lg rounded-3xl flex flex-col items-center p-4">
                            <i class="fas fa-meh text-green-600 text-2xl"></i>
                            <h4 class="text-green-600 mt-2 text-sm font-semibold">Neutral Count</h4>
                            <p class="text-green-600 font-bold text-xl">{{ $sentiment->neutral_count }}</p>
                        </div>

                        <!-- Total Words -->
                        <div class="bg-gray-100 shadow-lg rounded-3xl flex flex-col items-center p-4">
                            <i class="fas fa-align-left text-gray-600 text-2xl"></i>
                            <h4 class="text-gray-600 mt-2 text-sm font-semibold">Total Words</h4>
                            <p class="text-gray-600 font-bold text-xl">{{ $sentiment->total_word_count }}</p>
                        </div>
                    </div>

                    <!-- Sentiment Grade -->
                    <div class="bg-green-100 shadow-lg rounded-3xl flex flex-col items-center p-4 mt-6">
                        <h4 class="text-green-600 text-sm font-semibold">Grade</h4>
                        <p class="text-green-600 font-bold text-xl">{{ $sentiment->grade }}</p>
                    </div>

                    <!-- Percentages -->
                    <div class="grid grid-cols-3 gap-4 mt-6">
                        <!-- Positive Percentage -->
                        <div class="bg-blue-100 shadow-lg rounded-3xl flex flex-col items-center p-4">
                            <h4 class="text-blue-600 text-sm font-semibold">Positive %</h4>
                            <p class="text-blue-600 font-bold text-xl">{{ $sentiment->positive_percentage }}%</p>
                        </div>

                        <!-- Negative Percentage -->
                        <div class="bg-red-100 shadow-lg rounded-3xl flex flex-col items-center p-4">
                            <h4 class="text-red-600 text-sm font-semibold">Negative %</h4>
                            <p class="text-red-600 font-bold text-xl">{{ $sentiment->negative_percentage }}%</p>
                        </div>

                        <!-- Neutral Percentage -->
                        <div class="bg-green-100 shadow-lg rounded-3xl flex flex-col items-center p-4">
                            <h4 class="text-green-600 text-sm font-semibold">Neutral %</h4>
                            <p class="text-green-600 font-bold text-xl">{{ $sentiment->neutral_percentage }}%</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

<!-- Modal for Full Text -->
<div id="full-text-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center">
    <div class="modal-content">
        <h2 class="text-xl font-semibold mb-4">Full Analysis Text</h2>
        <p id="full-text-content" class="text-gray-700"></p>
        <div class="mt-4 text-right">
            <button id="close-modal" class="bg-red-500 text-white px-4 py-2 rounded">Close</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('full-text-modal');
    const modalContent = document.getElementById('full-text-content');
    const closeModal = document.getElementById('close-modal');

    document.querySelectorAll('.read-more').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const sentimentId = this.dataset.id;

            // Fetch the full text using AJAX
            fetch(`/sentiments/${sentimentId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch sentiment text');
                    }
                    return response.json();
                })
                .then(data => {
                    modalContent.innerHTML = data.text;
                    modal.classList.remove('hidden');
                    modal.classList.add('show'); // Trigger animation
                })
                .catch(error => {
                    console.error(error);
                    alert('Failed to load full text.');
                });
        });
    });

    closeModal.addEventListener('click', () => {
        modal.classList.remove('show');
        setTimeout(() => {
            modal.classList.add('hidden'); // Ensure it's completely hidden after animation
        }, 300); // Match the duration of the CSS animation
    });

    // Close the modal when clicking outside the modal content
    modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        e.stopPropagation(); // Stop the event from bubbling
        closeModal.click();
    }
});

});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.delete-sentiment').forEach(button => {
        button.addEventListener('click', function () {
            const sentimentId = this.getAttribute('data-id');

            if (confirm('Are you sure you want to delete this sentiment?')) {
                fetch(`/sentiments/${sentimentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to delete sentiment');
                    }
                    return response.json();
                })
                .then(data => {
                    alert(data.message);
                    location.reload(); // Reload the page to reflect changes
                })
                .catch(error => {
                    console.error(error);
                    alert('An error occurred while deleting the sentiment.');
                });
            }
        });
    });
});
document.addEventListener('DOMContentLoaded', () => {
    const dropdownButton = document.getElementById('dropdown-button');
    const dropdownMenu = document.getElementById('dropdown-menu');
    const sentimentCards = document.querySelectorAll('.sentiment-card');

    // Toggle dropdown menu
    dropdownButton.addEventListener('click', () => {
        dropdownMenu.classList.toggle('hidden');
    });

    // Handle filter logic
    dropdownMenu.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', () => {
            const filter = item.getAttribute('data-filter');

            sentimentCards.forEach(card => {
                if (filter === 'all' || card.getAttribute('data-grade') === filter) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });

            // Close dropdown after selection
            dropdownMenu.classList.add('hidden');
        });
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!dropdownMenu.contains(e.target) && e.target !== dropdownButton) {
            dropdownMenu.classList.add('hidden');
        }
    });
});


</script>
</x-app-layout>