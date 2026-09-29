<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\ServiceTierController as AdminServiceTierController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController as WorkController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\ServiceTierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public site (plan.md #4)
|--------------------------------------------------------------------------
*/

Route::get('/', HomeController::class)->name('home');

Route::prefix('services')->name('services.')->group(function () {
    Route::get('/', [ServiceTierController::class, 'index'])->name('index');
    Route::get('/{tier}', [ServiceTierController::class, 'show'])->name('show');
});

Route::get('/how-it-works', [PageController::class, 'howItWorks'])->name('how-it-works');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');

Route::prefix('work')->name('work.')->group(function () {
    Route::get('/', [WorkController::class, 'index'])->name('index');
    Route::get('/{project}', [WorkController::class, 'show'])->name('show');
});

Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('contact.store');
Route::get('/contact/thank-you', [ContactController::class, 'thankYou'])->name('contact.thank-you');

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

/*
|--------------------------------------------------------------------------
| Admin (plan.md #26)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])
            ->middleware('throttle:5,1')
            ->name('login.store');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
        Route::get('/leads/export', [LeadController::class, 'export'])->name('leads.export');
        Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
        Route::patch('/leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
        Route::post('/leads/{lead}/notes', [LeadController::class, 'storeNote'])->name('leads.notes.store');
        Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');

        Route::middleware('content')->group(function () {
            Route::get('/services', [AdminServiceTierController::class, 'index'])->name('services.index');
            Route::get('/services/create', [AdminServiceTierController::class, 'create'])->name('services.create');
            Route::post('/services', [AdminServiceTierController::class, 'store'])->name('services.store');
            Route::get('/services/{tier}', [AdminServiceTierController::class, 'edit'])->name('services.edit');
            Route::put('/services/{tier}', [AdminServiceTierController::class, 'update'])->name('services.update');
            Route::delete('/services/{tier}', [AdminServiceTierController::class, 'destroy'])->name('services.destroy');
            Route::post('/services/{tier}/features', [AdminServiceTierController::class, 'storeFeature'])->name('services.features.store');
            Route::put('/services/{tier}/features/{feature}', [AdminServiceTierController::class, 'updateFeature'])->name('services.features.update');
            Route::delete('/services/{tier}/features/{feature}', [AdminServiceTierController::class, 'destroyFeature'])->name('services.features.destroy');

            Route::get('/projects', [AdminProjectController::class, 'index'])->name('projects.index');
            Route::get('/projects/create', [AdminProjectController::class, 'create'])->name('projects.create');
            Route::post('/projects', [AdminProjectController::class, 'store'])->name('projects.store');
            Route::get('/projects/{project}', [AdminProjectController::class, 'edit'])->name('projects.edit');
            Route::put('/projects/{project}', [AdminProjectController::class, 'update'])->name('projects.update');
            Route::delete('/projects/{project}', [AdminProjectController::class, 'destroy'])->name('projects.destroy');
            Route::post('/projects/{project}/features', [AdminProjectController::class, 'storeFeature'])->name('projects.features.store');
            Route::put('/projects/{project}/features/{feature}', [AdminProjectController::class, 'updateFeature'])->name('projects.features.update');
            Route::delete('/projects/{project}/features/{feature}', [AdminProjectController::class, 'destroyFeature'])->name('projects.features.destroy');

            Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');
            Route::get('/faqs/create', [FaqController::class, 'create'])->name('faqs.create');
            Route::post('/faqs', [FaqController::class, 'store'])->name('faqs.store');
            Route::get('/faqs/{faq}', [FaqController::class, 'edit'])->name('faqs.edit');
            Route::put('/faqs/{faq}', [FaqController::class, 'update'])->name('faqs.update');
            Route::delete('/faqs/{faq}', [FaqController::class, 'destroy'])->name('faqs.destroy');

            Route::get('/team', [TeamMemberController::class, 'index'])->name('team.index');
            Route::get('/team/create', [TeamMemberController::class, 'create'])->name('team.create');
            Route::post('/team', [TeamMemberController::class, 'store'])->name('team.store');
            Route::get('/team/{teamMember}', [TeamMemberController::class, 'edit'])->name('team.edit');
            Route::put('/team/{teamMember}', [TeamMemberController::class, 'update'])->name('team.update');
            Route::delete('/team/{teamMember}', [TeamMemberController::class, 'destroy'])->name('team.destroy');
        });

        Route::middleware('admin.only')->group(function () {
            Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
            Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
            Route::get('/audit-log', [SettingController::class, 'auditLog'])->name('audit-log');
        });
    });
});
