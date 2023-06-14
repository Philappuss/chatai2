<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ChatbotSettingController;
use App\Http\Controllers\MarketingWebsiteController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\PdfDataSourceController;
use App\Http\Controllers\WebsiteDataSourceController;
use App\Http\Middleware\IframeMiddleware;
use Illuminate\Http\Middleware\FrameGuard;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/register', [AuthController::class, 'marketingRegister'])->name('marketing.register');
Route::get('pricing', [MarketingWebsiteController::class, 'pricing'])->name('marketing.pricing');
Route::get('/', [MarketingWebsiteController::class, 'index'])->name('marketing');
Route::get('/login', [AuthController::class, 'marketingLogin'])->name('marketing.login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/privacy-policy', [MarketingWebsiteController::class, 'privacyPolicy'])->name('marketing.privacy-policy');
Route::get('/callback/google', [AuthController::class, 'handleGoogleCallback'])->name('callback.google');
Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth:web'])->group(function () {

    /**
     * Dashboard
     */
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('index');


    /**
     * Chatbot Settings
     */
    Route::get('/app/{id}', [ChatbotSettingController::class, 'generalSettings'])->name('chatbot.settings');
    Route::post('/app/{id}', [ChatbotSettingController::class, 'generalSettingsUpdate'])->name('chatbot.settings.update');
    Route::get('/app/{id}/try-and-share', [ChatbotSettingController::class, 'themeSettings'])->name('chatbot.settings-theme');
    Route::get('/app/{id}/data', [ChatbotSettingController::class, 'dataSettings'])->name('chatbot.settings-data');
    Route::get('/app/{id}/analytics', [ChatbotSettingController::class, 'analyticsSettings'])->name('chatbot.settings-analytics');
    Route::get('/app/{id}/integrations', [ChatbotSettingController::class, 'integrationsSettings'])->name('chatbot.settings-integrations');
    Route::get('widget/data-sources-updates/{id}', [ChatbotSettingController::class, 'dataSourcesUpdates'])->name('widget.data-sources-updates');


    /**
     * Onboarding Frontend
     */
    Route::get('/onboarding/welcome', [OnboardingController::class, 'welcome'])->name('onboarding.welcome');
    Route::get('/onboarding/data-source', [OnboardingController::class, 'dataSources'])->name('onboarding.data-source');
    Route::get('/onboarding/website', [OnboardingController::class, 'dataSourcesWebsite'])->name('onboarding.website');
    Route::get('/onboarding/pdf', [OnboardingController::class, 'dataSourcesPdf'])->name('onboarding.pdf');
    Route::get('/onboarding/codebase', [OnboardingController::class, 'dataSourcesCodebase'])->name('onboarding.codebase');


    /**
     * Onboarding Backend
     */
    Route::post('/onboarding/website', [ChatbotController::class, 'createViaWebsiteFlow'])->name('onboarding.website.create');
    Route::post('/onboarding/pdf', [ChatbotController::class, 'createViaPdfFlow'])->name('onboarding.pdf.create');
    Route::post('/onboarding/codebase', [ChatbotController::class, 'createViaCodebaseFlow'])->name('onboarding.codebase.create');
    Route::post('/onboarding/{id}/config', [ChatbotController::class, 'updateCharacterSettings'])->name('onboarding.config.create');
    Route::get('/onboarding/{id}/config', [OnboardingController::class, 'config'])->name('onboarding.config');

    Route::get('/app/{id}/data/pdf', [PdfDataSourceController::class, 'show'])->name('onboarding.other-data-sources-pdf');
    Route::post('/app/{id}/data/pdf', [PdfDataSourceController::class, 'create'])->name('onboarding.other-data-sources-pdf.create');

    Route::get('/app/{id}/data/web', [WebsiteDataSourceController::class, 'show'])->name('onboarding.other-data-sources-web');
    Route::post('/app/{id}/data/web', [WebsiteDataSourceController::class, 'create'])->name('onboarding.other-data-sources-web.create');

    Route::get('/onboarding/{id}/done', [OnboardingController::class, 'done'])->name('onboarding.done');
});

Route::get('/chat/{token}', [ChatbotController::class, 'getChatView'])->name('chat')->withoutMiddleware([FrameGuard::class])->middleware([IframeMiddleware::class]);
Route::post('/chat/{token}/send-message', [ChatbotController::class, 'sendMessage'])->name('sendMessage');
