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

use App\Http\Livewire\Transactions;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\ResetPasswordExample;
use App\Http\Livewire\UpgradeToPro;
use App\Http\Livewire\Users;

use App\Http\Controllers\BlacklistController;
use App\Exports\BlacklistExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\Musoni\GraceController;
use App\Http\Controllers\Musoni\SettingController;
use App\Http\Controllers\Musoni\SalaryPaymentController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\Ticket\TicketController;
use App\Http\Controllers\Ticket\MessageController;
use App\Http\Controllers\Ticket\AttachmentController;
use App\Http\Controllers\Ticket\Conformite\GestionController;
use App\Http\Controllers\Ticket\Conformite\BaseConnaissanceController;
use App\Http\Livewire\Purchase\MyPurchaseRequests;
use Spatie\Permission\Models\Role;

use App\Http\Livewire\Admin\RoleManager;
use App\Http\Livewire\Admin\RolePermissions;

use App\Http\Livewire\Purchase\PurchaseRequestList;
use App\Http\Livewire\Purchase\ReviewRequest;
   use App\Exports\SalaryPaymentsExport;  // Assure-toi d'importer la bonne classe


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


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Tableau de bord
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Route::get('/chart', \App\Http\Livewire\C::class)->name('chart');

// Routes accessibles à tous les utilisateurs authentifiés
Route::middleware('auth')->group(function () {
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/purchase-requests/mes-demandes', fn() => view('purchase.my-request'))->name('purchase-requests.mine');
    Route::get('/blacklists/search', [BlacklistController::class, 'search'])->name('blacklists.search');
    Route::get('tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::get('tickets/me', [TicketController::class, 'MonTicket'])->name('tickets.me');
    Route::get('/blacklists/filter', [BlacklistController::class, 'filter'])->name('blacklists.filter');
    Route::post('tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/purchase-requests', [PurchaseRequestController::class, 'index'])->name('purchase-requests.index');
    Route::get('/purchase-requests/create', [PurchaseRequestController::class, 'create'])->name('purchase-requests.create');

});

// Admin uniquement
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [UserRoleController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/{user}/edit', [UserRoleController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserRoleController::class, 'update'])->name('admin.users.update');
    Route::put('/admin/users/{user}/password', [UserRoleController::class, 'updatePassword'])->name('admin.pass.update');
    Route::put('/admin/users/{user}/profile', [UserRoleController::class, 'updatePhoto'])->name('admin.profil.update');

    Route::get('/admin/settings', fn() => view('admin.settings.index'))->name('admin.settings');
    Route::get('/admin/cbs-config', fn() => view('admin.settings.cbs-config'))->name('admin.cbs-config');

    Route::get('/roles', [UserRoleController::class, 'roles'])->name('roles.index');
    Route::post('/roles', [UserRoleController::class, 'roles_store'])->name('roles.store');

});

// Superviseur
Route::middleware(['auth', 'role:Superviseur|admin'])->group(function () {
    
    Route::get('/purchase-requests/{id}/review', fn($id) => 
        view('purchase.review', [
            'purchaseRequest' => \App\Models\PurchaseRequest::with('items', 'user')->findOrFail($id)
        ])
    )->name('purchase-requests.review');
    Route::get('/musoni_grace', [GraceController::class, 'index'])->name('grace.index');
});

// Conformité
Route::middleware(['auth', 'role:Conformité|admin'])->group(function () {
    Route::get('/conformite/tickets', [GestionController::class, 'index'])->name('compliance.tickets.index');
    Route::post('/conformite/tickets/filter', [GestionController::class, 'filter'])->name('compliance.tickets.filter');
    Route::post('/conformite/tickets/{id}/assign', [GestionController::class, 'assign'])->name('compliance.tickets.assign');
    Route::post('/conformite/tickets/{id}/status', [GestionController::class, 'status'])->name('compliance.tickets.status');
    Route::get('/blacklists/create', [BlacklistController::class, 'create'])->name('blacklists.create');
    Route::get('tickets_gestion', [GestionController::class, 'index'])->name('tickets.compliance.index');
    Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::post('/tickets/{ticket}/assign', [GestionController::class, 'assign'])->name('tickets.compliance.assign');
    Route::post('/tickets/{ticket}/status', [GestionController::class, 'updateStatus'])->name('tickets.compliance.status');
    Route::get('tickets/filter', [GestionController::class, 'filter'])->name('compliance.filter');

    Route::get('/blacklist/export/excel', function () {
        return Excel::download(new BlacklistExport, 'blacklist.xlsx');
    })->name('blacklist.export.excel');
    Route::get('/blacklist/export/pdf', function () {
        return Excel::download(new BlacklistExport, 'blacklist.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    })->name('blacklist.export.pdf');
    Route::get('/blacklist/template', function () {
        return response()->download(storage_path('/app/public/templates/blacklist_import_template.xlsx'));
    })->name('blacklist.template');
    Route::post('/blacklists/{id}/unblock', [BlacklistController::class, 'unblock'])->name('blacklists.unblock');
    Route::get('/blacklists', [BlacklistController::class, 'index'])->name('blacklists.index');

});

// Comptable
Route::middleware(['auth', 'role:Comptable|admin'])->group(function () {
    Route::get('/musoni/salary-payments', [SalaryPaymentController::class, 'index'])->name('salary-payments.index');
    // Route::get('/musoni/salary-payments/export', SalaryPaymentsExport::class)->name('salary-payments.export');
    Route::get('/musoni/salary-payments/export', [SalaryPaymentController::class, 'export'])->name('salary-payments.export');
    Route::resource('salary-payments', SalaryPaymentController::class);
    Route::get('/salary-payments/download/pdf', function () {
        return Excel::download(new SalaryPaymentsExport, 'virement.xlsx');
    })->name('salary-payments.download');
    Route::post('/salary-payments/import', [SalaryPaymentController::class, 'import'])->name('salary-payments.import');
    Route::get('/salary-payments/template', [SalaryPaymentController::class, 'downloadTemplate'])->name('salary-payments.template');
    Route::post('/salary-payments/deposit', [SalaryPaymentController::class, 'deposit'])->name('salary-payments.deposit');

});

// Procurement
Route::middleware(['auth', 'role:Procurement|admin'])->group(function () {
    Route::get('/purchase-requests', [PurchaseRequestController::class, 'index'])->name('purchase-requests.index');
});

// Autres rôles (à compléter si besoin)
Route::middleware(['auth', 'role:Agent Terrain|admin'])->group(function () {
    // Exemples de routes spécifiques à Agent Terrain
});

Route::middleware(['auth', 'role:Consultant|admin'])->group(function () {
    // Exemples de routes spécifiques à Consultant
});

Route::middleware(['auth', 'role:Momo Team|admin'])->group(function () {
    // Exemples de routes spécifiques à Momo Team
    Route::get('/taratra/mvola/create', function () {
        return view('musoni.taratra.create');
    })->name('mvola.create');
    
    Route::get('/taratra/mvola/repports', function () {
        return view('musoni.taratra.repports');
    })->name('mvola.repports');
});

Route::middleware(['auth', 'role:Directeur et Chef d\'agence'])->group(function () {
    // Exemples de routes spécifiques à la direction
});

Route::middleware(['auth', 'role:Collaborateur'])->group(function () {
    // Exemples de routes spécifiques à Collaborateur
});
