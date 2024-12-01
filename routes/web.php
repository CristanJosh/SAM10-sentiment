<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SentimentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisteredUserController; // Ensure this controller exists
use Illuminate\Support\Facades\Auth; // Import Auth facade
use App\Models\Sentiment;
use App\Http\Controllers\FileProcessingController;

Route::post('/extract-text', [FileProcessingController::class, 'extractText'])->name('extract.text');


// Route for showing the custom register page
Route::get('/register', function () {
    return view('livewire.pages.auth.custom-register'); // Adjust the path as needed
})->name('register')->middleware('guest');

// Route for handling the registration submission
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.submit');


// Dashboard Routes (Protected)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/add-phrase', [DashboardController::class, 'addPhrase'])->name('add.phrase');
    Route::post('/delete-phrase', [DashboardController::class, 'deletePhrase'])->name('delete.phrase');
});

// Sentiment Analysis Routes (Protected)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/analyze', [SentimentController::class, 'analyze'])->name('analyze.sentiment');
    Route::get('/history', [SentimentController::class, 'history'])->name('sentiments.history');
    Route::get('/sentiments/{id}', function ($id) {
        $sentiment = Sentiment::findOrFail($id);
        return response()->json(['text' => $sentiment->highlighted_text]);
    })->name('sentiments.show');
    Route::delete('/sentiments/{id}', [SentimentController::class, 'destroy'])->name('sentiments.destroy');
});

// Static Pages
Route::view('/', 'welcome')->name('home');
Route::view('/profile', 'profile')->middleware(['auth'])->name('profile');
Route::view('/analysis', 'analysis')->name('analysis');

// About and Contact Pages
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

// Authentication Routes (Guest Only)
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('/login', function () {
        return view('livewire.pages.auth.custom-login'); // Adjust path to your custom view
    })->name('login');

    Route::post('/login', function (\Illuminate\Http\Request $request) {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard'); // Redirect after login
        }

        return back()->withErrors([
            'email' => 'The provided credentials are incorrect.',
        ]);
    })->name('login.submit');
}); // <-- This closing brace was missing

// Include Laravel's Auth Scaffolding
require __DIR__ . '/auth.php';
