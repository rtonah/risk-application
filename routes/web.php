<?php

use App\Http\Livewire\BootstrapTables;
use App\Http\Livewire\Components\Buttons;
use App\Http\Livewire\Components\Forms;
use App\Http\Livewire\Components\Modals;
use App\Http\Livewire\Components\Notifications;
use App\Http\Livewire\Components\Typography;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Err404;
use App\Http\Livewire\Err500;
use App\Http\Livewire\ResetPassword;
use App\Http\Livewire\ForgotPassword;
use App\Http\Livewire\Lock;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Profile;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\ForgotPasswordExample;
use App\Http\Livewire\Index;
use App\Http\Livewire\LoginExample;
use App\Http\Livewire\ProfileExample;
use App\Http\Livewire\RegisterExample;
use App\Http\Livewire\Transactions;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\ResetPasswordExample;
use App\Http\Livewire\UpgradeToPro;
use App\Http\Livewire\Users;

use App\Http\Controllers\BlacklistController;
use App\Exports\BlacklistExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Admin\UserRoleController;

use App\Http\Controllers\Ticket\TicketController;
use App\Http\Controllers\Ticket\MessageController;
use App\Http\Controllers\Ticket\AttachmentController;
use App\Http\Controllers\Ticket\Conformite\GestionController;
use App\Http\Controllers\Ticket\Conformite\BaseConnaissanceController;
use Spatie\Permission\Models\Role;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/login');

Route::get('/register', Register::class)->name('register');

Route::get('/login', Login::class)->name('login');

Route::get('/forgot-password', ForgotPassword::class)->name('forgot-password');

Route::get('/reset-password/{id}', ResetPassword::class)->name('reset-password')->middleware('signed');

Route::get('/404', Err404::class)->name('404');
Route::get('/500', Err500::class)->name('500');
Route::get('/upgrade-to-pro', UpgradeToPro::class)->name('upgrade-to-pro');

Route::middleware('auth')->group(function () {
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/profile-example', ProfileExample::class)->name('profile-example');
    Route::get('/users', Users::class)->name('users');
    Route::get('/login-example', LoginExample::class)->name('login-example');
    Route::get('/register-example', RegisterExample::class)->name('register-example');
    Route::get('/forgot-password-example', ForgotPasswordExample::class)->name('forgot-password-example');
    Route::get('/reset-password-example', ResetPasswordExample::class)->name('reset-password-example');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/transactions', Transactions::class)->name('transactions');
    Route::get('/bootstrap-tables', BootstrapTables::class)->name('bootstrap-tables');
    Route::get('/lock', Lock::class)->name('lock');
    Route::get('/buttons', Buttons::class)->name('buttons');
    Route::get('/notifications', Notifications::class)->name('notifications');
    Route::get('/forms', Forms::class)->name('forms');
    Route::get('/modals', Modals::class)->name('modals');
    Route::get('/typography', Typography::class)->name('typography');

    Route::get('/admin/users/{user}/edit', [UserRoleController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}/password', [UserRoleController::class, 'updatePassword'])->name('admin.pass.update');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/blacklists', [BlacklistController::class, 'index'])->name('blacklists.index');
    Route::get('/blacklists/create', [BlacklistController::class, 'create'])->name('blacklists.create');
    Route::post('/blacklists', [BlacklistController::class, 'store'])->name('blacklists.store');
    Route::get('/blacklists/filter', [BlacklistController::class, 'filter'])->name('blacklists.filter');
});

Route::group(['middleware' => ['role:admin']], function () {
    Route::post('/blacklists/{id}/unblock', [BlacklistController::class, 'unblock'])->name('blacklists.unblock');
});

Route::get('/blacklist/export/excel', function () {
    return Excel::download(new BlacklistExport, 'blacklist.xlsx');
})->name('blacklist.export.excel');

Route::get('/blacklist/export/pdf', function () {
    return Excel::download(new BlacklistExport, 'blacklist.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
})->name('blacklist.export.pdf');

Route::middleware(['auth', 'role:admin'])->group(function () {
// Route::middleware(['auth'])->group(function () {

    Route::post('/admin/users/{user}/roles', [UserRoleController::class, 'updateRoles'])->name('admin.users.updateRoles');
    Route::get('/admin/users', [UserRoleController::class, 'index'])->name('admin.users.index');
    Route::put('/admin/users/{user}', [UserRoleController::class, 'update'])->name('admin.users.update');

    Route::post('/admin/users/{user}/deactivate', [UserRoleController::class, 'deactivate'])->name('admin.users.deactivate');


    Route::get('/roles', [UserRoleController::class, 'roles'])->name('roles.index');
    Route::get('/roles/{role}/permissions', [UserRoleController::class, 'getPermissions']);
    
    Route::post('/roles', [UserRoleController::class, 'roles_store'])->name('roles.store');

    Route::post('/admin/store', [UserRoleController::class, 'store'])->name('users.store');

});

Route::middleware(['auth'])->group(function () {

    /**
     * 🟢 Agent Terrain : peut créer des tickets et discuter
     */
    Route::middleware(['role:Agent Terrain|admin'])->group(function () {
        Route::get('tickets/create', [TicketController::class, 'create'])->name('tickets.create');
        Route::post('tickets', [TicketController::class, 'store'])->name('tickets.store');
        Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index');
        Route::get('tickets/me', [TicketController::class, 'MonTicket'])->name('tickets.me');
        Route::get('tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
        Route::post('tickets/{ticket}/messages', [MessageController::class, 'store'])->name('tickets.messages.store');
        Route::post('tickets/{ticket}/attachments', [AttachmentController::class, 'store'])->name('tickets.attachments.store');
    });

    /**
     * 🟡 Superviseur : même droits que l'agent + vue plus globale
     */
    Route::middleware(['role:Superviseur'])->group(function () {
        Route::get('tickets', [TicketController::class, 'index'])->name('tickets.superviseur.index');
        Route::get('tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.superviseur.show');
        // Route::post('tickets/{ticket}/messages', [MessageController::class, 'store'])->name('tickets.superviseur.messages');
    });

    /**
     * 🔴 Équipe Conformité : traitement, escalade, clôture
     */
    Route::middleware(['role:Conformité|admin'])->group(function () {
        Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index');
        Route::get('tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.compliance.show');
        // Route::post('tickets/{ticket}/messages', [MessageController::class, 'store'])->name('tickets.compliance.messages');

        Route::post('tickets/{ticket}/escalate', [GestionController::class, 'escalate'])->name('tickets.compliance.escalate');
        Route::post('tickets/{ticket}/close', [GestionController::class, 'close'])->name('tickets.compliance.close');
        Route::get('tickets/filter', [GestionController::class, 'filter'])->name('compliance.filter');
        Route::get('tickets_gestion', [GestionController::class, 'index'])->name('tickets.compliance.index');
        // Route::post('tickets_gestion', [GestionController::class, 'assign'])->name('tickets.compliance.assign');
        Route::post('/tickets/{ticket}/assign', [GestionController::class, 'assign'])->name('tickets.compliance.assign');
        Route::post('/tickets/{ticket}/status', [GestionController::class, 'updateStatus'])->name('tickets.compliance.status');
    });

    /**
     * 📚 Base de connaissance : accès lecture pour Consultant
     */
    Route::middleware(['role:Consultant'])->group(function () {
        Route::get('base-connaissance', [BaseConnaissanceController::class, 'index'])->name('base.index');
        Route::get('base-connaissance/{id}', [BaseConnaissanceController::class, 'show'])->name('base.show');
    });

    /**
     * ⚙️ Admin : tous les accès
     */
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('tickets', TicketController::class)->except(['create', 'store']);
        Route::resource('base-connaissance', BaseConnaissanceController::class);
        
    });
});

