<?php


use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\SKOfficialsController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AdminAnnouncementController;
use App\Http\Controllers\AdminKabataanController;
use App\Http\Controllers\AdminOfficialsController;
// use App\Http\Controllers\AdminQRCodeController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\OfficialController;
use App\Http\Controllers\KabataanController;
use App\http\Controllers\SKYouthFormController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KabataanlistController;
use App\Http\Controllers\Admin\EventController;
// use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\User\EventController as UserEventController;
use App\Http\Controllers\AdminGeneratorController;



Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


});




require __DIR__.'/auth.php';


// User Routes
Route::middleware(['auth', 'userMiddleware'])->group(function() {


    Route::get('/dashboard', function () {
        $userId = auth()->id();
        $isRegistered = \App\Models\SkYouthForm::where('user_id', $userId)->exists();

        return view('dashboard', compact('isRegistered'));
    })->name('dashboard');

    // Registration form submission
    Route::post('/sk-youth-form', [SkYouthFormController::class, 'store'])->name('sk-youth-form.store');

    // // Routes that should only be accessible after registration
    // Route::middleware('check.registration')->group(function () {
    //     // SK Officials routes
    //     Route::get('/users/sk-officials', [SKOfficialsController::class, 'index'])->name('users.sk-officials');
    //     Route::get('users/sk-officials/{id}', [SKOfficialsController::class, 'show'])->name('users.sk-official.show');

    //     // Kabataan list routes
    //     Route::get('/users/kabataan-list', [KabataanlistController::class, 'index'])->name('users.kabataan-list');

    //     // Announcement routes
    //     Route::get('/users/announcements', [AnnouncementController::class, 'showAnnouncements'])->name('users.announcement');
    //     Route::get('/announcements/{id}', [AnnouncementController::class, 'show'])->name('users.announcements.show');

    //     // Events routes
    //     Route::get('/users/events', [UserEventController::class, 'index'])->name('users.events');
    //     Route::get('/users/events/{id}', [UserEventController::class, 'show'])->name('users.events.show');

    //     // Profile update routes
    //     Route::get('/users/update-info/{id}', [KabataanController::class, 'edit'])->name('users.edit-info');
    //     Route::put('/users/update-info/{id}', [KabataanController::class, 'update'])->name('users.update-info');
    // });


    // Route::get('/dashboard', [UserController::class, 'index'])->middleware('check.registration')->name('dashboard');

    Route::get('/dashboard', function () {
        return view('dashboard');})->name('dashboard');

    // route for newly register
    Route::post('/sk-youth-form', [SkYouthFormController::class, 'store'])->name('sk-youth-form.store');



    // update users information
    // Route::get('/update-info/{id}', [KabataanController::class, 'edit'])->name('users.update');
    // Route::get('/update/{id}', [KabataanController::class, 'edit'])->name('users.edit');
    // Route::put('/update/{id}', [KabataanController::class, 'update'])->name('users.update');

    Route::middleware('auth')->prefix('users')->group(function () {
        Route::get('/update-info/{id}', [KabataanController::class, 'edit'])->name('users.edit-info');
        Route::put('/update-info/{id}', [KabataanController::class, 'update'])->name('users.update-info');
    });




    // announcement
    Route::get('announcement', [AnnouncementController::class, 'index'])->name('announcement');
    Route::get('/users/announcement', [AnnouncementController::class, 'index'])->name('user.announcement');
    // Route::get('/users/announcement', [AnnouncementController::class, 'index'])->name('users.announcement');
    Route::get('/users/announcements', [AnnouncementController::class, 'showAnnouncements'])->name('users.announcement');
    Route::get('/announcements/{id}', [AnnouncementController::class, 'show'])->name('users.announcements.show');






    // list of SKO's
    Route::get('/users/sk-officials', [SKOfficialsController::class, 'index'])->name('users.sk-officials');
    Route::get('users/sk-officials/{id}', [SKOfficialsController::class, 'show'])->name('users.sk-official.show');


    // list of kabataan
    Route::get('/users/kabataan-list', [KabataanlistController::class, 'index'])->name('users.kabataan-list');



// EVENTS/CONTROLLER
    Route::prefix('users')->group(function () {
        Route::get('/events', [UserEventController::class, 'index'])->name('users.events');
        Route::get('/events/{id}', [UserEventController::class, 'show'])->name('users.events.show');
        Route::get('/events', [UserEventController::class, 'showEvents'])->name('users.events');
    });




});




// Admin Routes
Route::middleware(['auth', 'adminMiddleware'])->group(function() {

    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // announcement
    Route::get('admin/announcement', [AdminAnnouncementController::class, 'index'])->name('admin.announcement');
    Route::post('/admin/announcement', [AdminAnnouncementController::class, 'store'])->name('admin.announcement.store');
    Route::get('/admin/form/{announcements}/edit', [AdminAnnouncementController::class, 'edit'])->name('admin.announcement.edit');
    Route::put('/admin/announcement/{announcements}', [AdminAnnouncementController::class, 'update'])->name('admin.announcement.update');
    Route::delete('/admin/announcement/{announcements}', [AdminAnnouncementController::class, 'destroy'])->name('admin.announcement.destroy');



    // Events
    Route::prefix('admin/events')->group(function () {
        Route::get('/list', [EventController::class, 'index'])->name('admin.events.list');
        Route::get('/create', [EventController::class, 'create'])->name('admin.events.create');
        Route::post('/list', [EventController::class, 'store'])->name('admin.events.store');
        Route::get('/{event}/edit', [EventController::class, 'edit'])->name('admin.events.edit');
        Route::put('/{event}', [EventController::class, 'update'])->name('admin.events.update');
        Route::delete('/destroy/{event}', [EventController::class, 'destroy'])->name('admin.events.destroy');
    });


    // Documents
    Route::prefix('admin/documents')->group(function () {
        Route::get('/list', [DocumentController::class, 'index'])->name('admin.documents.list');
        Route::get('/create', [DocumentController::class, 'create'])->name('admin.documents.create');
        Route::post('/list', [DocumentController::class, 'store'])->name('admin.documents.store');
        Route::get('/{documents}/edit', [DocumentController::class, 'edit'])->name('admin.documents.edit');
        Route::put('/{documents}', [DocumentController::class, 'update'])->name('admin.documents.update');
        Route::delete('/destroy/{documents}', [DocumentController::class, 'destroy'])->name('admin.documents.destroy');
    });




    // list registered account
    Route::get('admin/kabataan/list', [AdminKabataanController::class, 'index'])->name('admin.kabataan.list');


    // officials List
    Route::prefix('/admin/officials')->group(function () {
        Route::get('/list', [OfficialController::class, 'index'])->name('admin.officials.index');
        Route::post('/list', [OfficialController::class, 'store'])->name('admin.officials.store');
        Route::get('/{official}/edit', [OfficialController::class, 'edit'])->name('admin.officials.edit');
        Route::put('/update/{official}', [OfficialController::class, 'update'])->name('admin.officials.update');
        Route::delete('/destroy/{official}', [OfficialController::class, 'destroy'])->name('admin.officials.destroy');
    });


    // Kabataan list
Route::prefix('/admin/kabataan')->group(function () {
    Route::get('/list', [SkYouthFormController::class, 'index'])->name('kabataan.index');
    Route::get('/{id}', [SkYouthFormController::class, 'show'])->name('kabataan.show');
    Route::get('/{id}/edit', [SkYouthFormController::class, 'edit'])->name('kabataan.edit');
    Route::post('/{id}/update', [SkYouthFormController::class, 'update'])->name('kabataan.update');
    Route::delete('/{id}', [SkYouthFormController::class, 'destroy'])->name('kabataan.destroy');
});


    // Generate  QR code
    // Route::get('/admin/qr-code', [AdminQRCodeController::class, 'index'])->name('admin.qr-code');
    // Route::get('/announcements/{id}', function ($id) {
    //     $announcement = App\Models\Announcement::findOrFail($id);
    //     return view('announcement-detail', compact('announcement'));
    // });


// list of the register account
Route::get('admin/users', [AdminUsersController::class, 'index'])->name('admin.users');
Route::post('/admin/users/{user}/status', [AdminUsersController::class, 'updateStatus'])->name('admin.users.updateStatus');
Route::delete('/admin/users/{id}', [AdminUsersController::class, 'destroy'])->name('admin.users.destroy');



});


