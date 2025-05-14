<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminEquipmentController;
use App\Http\Controllers\AdminSuppliesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserPanel\UserController;
use App\Http\Controllers\UserPanel\TransactionOfficeController;
use App\Http\Controllers\UserPanel\TransactionOfficeController as AdminSiteOffice;

use App\Http\Controllers\UserPanel\TeacherborrowController;
use App\Http\Controllers\UserPanel\NotificationController;
use App\Http\Controllers\UserPanel\Office_UserController;
use App\Http\Controllers\OfficePanel\OfficeController;
use App\Http\Controllers\OfficePanel\CalendaroController;
use App\Http\Controllers\OfficePanel\SuppliesController;
use App\Http\Controllers\OfficePanel\EquipmentController;
use App\Http\Controllers\DeanPanel\DeanController;
use App\Http\Controllers\LaboratoryPanel\LaboratoryController;
use App\Http\Controllers\LaboratoryPanel\ComputerEngineeringController;
use App\Http\Controllers\LaboratoryPanel\ConstructionController;
use App\Http\Controllers\LaboratoryPanel\FluidController;
use App\Http\Controllers\LaboratoryPanel\SurveyingController;
use App\Http\Controllers\LaboratoryPanel\TestingController;
use App\Http\Controllers\LaboratoryPanel\CalendarController;
use App\Http\Controllers\DeanPanel\DComputerController;
use App\Http\Controllers\DeanPanel\DConstructionController;
use App\Http\Controllers\DeanPanel\DFluidController;
use App\Http\Controllers\DeanPanel\DSurveyingController;
use App\Http\Controllers\DeanPanel\DTestingController;
use App\Http\Controllers\DeanPanel\DEquipmentController;
use App\Http\Controllers\DeanPanel\DSuppliesController;
use App\Http\Controllers\GeneralReportsController;
use App\Http\Controllers\LaboratoryItemsController;
use App\Http\Controllers\OfficeRequisitionController;
use App\Http\Controllers\Print\ComputuerEngPrintingController;
use App\Http\Controllers\Print\PrintManagementController;
use App\Http\Controllers\Print\SurveyingPrintController;
use App\Http\Controllers\SuperAdminPanel\SComputerController;
use App\Http\Controllers\SuperAdminPanel\SConstructionController;
use App\Http\Controllers\SuperAdminPanel\SFluidController;
use App\Http\Controllers\SuperAdminPanel\SSurveyingController;
use App\Http\Controllers\SuperAdminPanel\STestingController;
use App\Http\Controllers\SuperAdminPanel\SEquipmentController;
use App\Http\Controllers\SuperAdminPanel\SSuppliesController;
use App\Http\Controllers\SuperAdminPanel\SuperadminController;
use App\Http\Controllers\SuperAdminPanel\UserManagementController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/check-auth', function () {
    return response()->json(['authenticated' => auth()->check()]);
});


Route::get('/dashboard', [UserController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('change-password', AccountController::class);
});



Route::middleware(['auth', 'role:site secretary'])->group(function () {

    Route::get('office/dashboard', [OfficeController::class, 'index'])->name('office.dashboardo');
    Route::resource('/supplies', SuppliesController::class);
    Route::resource('equipment', EquipmentController::class);
    Route::get('/office/supplies', [SuppliesController::class, 'index'])->name('supplies.index');
    Route::get('/office/equipment', [EquipmentController::class, 'index'])->name('site.equipment.index');
    Route::get('/office/equipment-items', [EquipmentController::class, 'equipment_items'])->name('site.equipment-items.index');
    Route::get('/office/equipment-items/history/{id}', [EquipmentController::class, 'equipment_items_history'])->name('site.equipment-items-history.index');


    Route::get('office/calendaro', [CalendaroController::class, 'index'])->name('office.calendaro');
    Route::get('/office/transactions', [TransactionOfficeController::class, 'index'])->name('office-admin.transactions');
    Route::get('/office/print-transaction', [TransactionOfficeController::class, 'print'])->name('office.transaction-print');


    Route::get('/office/transactions/details/{id}', [TransactionOfficeController::class, 'details'])->name('office-admin.transactions-details');
    Route::get('/office/transactions/view-details/{id}', [TransactionOfficeController::class, 'view_details'])->name('office-transactions-details-data');

    //mark each items as damage

    Route::post('/office/submit-added-notes', [TransactionOfficeController::class, 'submitAddedNotes'])->name('office.submit-added-notes');
    Route::post('/office/submit-mark-damaged', [TransactionOfficeController::class, 'submitMarkAsDamaged'])->name('office.submit-as-damaged');
    Route::post('/office/submit-selected-items', [TransactionOfficeController::class, 'submitGoodCondition'])->name('office.submit-good-items');
    Route::post('/office/approved-selected-items', [TransactionOfficeController::class, 'approveAllSelected'])->name('office.approve-selected-items');
    Route::post('/office/received-selected-items', [TransactionOfficeController::class, 'RecievedAllSelected'])->name('office.mark-recieved-items');
    Route::post('/office/return-all-items', [TransactionOfficeController::class, 'ReturnAllItems'])->name('office.returned-all');

    Route::post('site-office/transactions/update', [TransactionOfficeController::class, 'decisions'])->name('office.transaction-update');
    Route::post('/office/transactions/{id}/disapprove', [TransactionOfficeController::class, 'disapprove'])->name('office.transactions.disapprove');
    Route::post('/office/transactions/{id}/returned', [TransactionOfficeController::class, 'returned'])->name('office.transactions.returned');
    Route::post('/office/transactions/{id}/damaged', [TransactionOfficeController::class, 'damaged'])->name('office.transactions.damaged');
    Route::get('/office/supplies', [SuppliesController::class, 'index'])->name('office-supplies');
    Route::post('office/notify-borrower', [TransactionOfficeController::class, 'notifyBorrower'])->name('notify.user');

    //chart
    Route::get('/office/requisitions/chart', [TeacherborrowController::class, 'getChartData'])->name('office.requisitions.chart');
    Route::get('/office/chart', [TeacherborrowController::class, 'offcieChartData'])->name('site-office.chart');

    Route::post('office/low-stock-notification', [SuppliesController::class, 'sendLowStockNotification'])->name('office.low-stock.notification');

    Route::post('office/notifications/{notification}/read', function ($notification) {
        /**
         * @var App\Models\User;
         */
        $user = auth()->user();
        $notification = $user->notifications()->find($notification);
        $notification->markAsRead();
        return response()->json(['message' => 'Notification marked as read']);
    });

    Route::post('office/notifications/mark-all-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['message' => 'All notifications marked as read']);
    });

    Route::get('/office/requisition', [OfficeRequisitionController::class, 'requisitions'])->name('office.requisition');
    Route::get('/office/all-requisition-request', [OfficeRequisitionController::class, 'forUser'])->name('office.requisition.request');
    Route::post('/office/requisition', [OfficeRequisitionController::class, 'getRequisitions'])->name('office.requisition.post');
    Route::get('/office/print-record/{id}', [OfficeRequisitionController::class, 'print'])->name('print-record');
});

Route::middleware(['auth', 'role:laboratory'])->group(function () {
    Route::get('laboratory/dashboardo', [LaboratoryController::class, 'index'])->name('laboratory.dashboardo');
    Route::resource('laboratory-computer-engineering', ComputerEngineeringController::class);
    Route::resource('constructions', ConstructionController::class);
    Route::resource('fluids', FluidController::class);
    Route::resource('surveyings', SurveyingController::class);
    Route::get('/surveying', [SurveyingController::class, 'index'])->name('surveying.index');
    Route::get('/laboratory/surveying/print-all', [SurveyingController::class, 'printAll'])->name('surveying.printAll');
    Route::resource('testings', TestingController::class);
    Route::get('/computer-engineering/print-all', [ComputerEngineeringController::class, 'printAll'])->name('computer_engineering.printAll');
    Route::get('/laboratory/testing/print-all', [TestingController::class, 'printAll'])->name('testing.printAll');
    Route::get('/laboratory/computer_engineering', [ComputerEngineeringController::class, 'index'])->name('computer_engineering.index');
    Route::get('laboratory/construction', [ConstructionController::class, 'index'])->name('construction.index');
    Route::get('laboratory/fluid', [FluidController::class, 'index'])->name('fluid.index');
    Route::get('laboratory/surveying', [SurveyingController::class, 'index'])->name('surveying.index');

    Route::get('laboratory/testing', [TestingController::class, 'index'])->name('testing.index');
    Route::get('laboratory/calendar', [CalendarController::class, 'index'])->name('laboratory.calendar');
    Route::get('/laboratory/transaction', [TeacherborrowController::class, 'index'])->name('transaction.index');
    Route::get('/laboratory/view-requisition-details/{id}', [TeacherborrowController::class, 'retrieve'])->name('borrows.show');
    Route::put('/laboratory/update-requisition-details/{id}', [TeacherborrowController::class, 'decision'])->name('teachers-borrows.update');
    Route::post('/laboratory/approve-requisition-items', [TeacherborrowController::class, 'approve_selected'])->name('laboratory.approve-requisition-items');
    Route::post('/laborator/item-received', [TeacherBorrowController::class, 'item_received'])->name('laboratory.item-received');
    Route::post('/laborator/item-add-notes', [TeacherBorrowController::class, 'item_notes'])->name('laboratory.item-add-notes');
    Route::post('/laborator/item-mark-damaged', [TeacherBorrowController::class, 'item_damaged'])->name('laboratory.item-damaged');
    Route::post('/laborator/item-mark-returned', [TeacherBorrowController::class, 'item_returned'])->name('laboratory.item-returned');




    Route::post('/laboratory/transaction/{id}/approve', [TeacherborrowController::class, 'approve'])->name('laboratory.transaction.approve');
    Route::post('/laboratory/transaction/{id}/disapprove', [TeacherborrowController::class, 'disapprove'])->name('laboratory.transaction.disapprove');
    Route::post('/laboratory/transaction/{id}/returned', [TeacherborrowController::class, 'returned'])->name('laboratory.transaction.returned');
    Route::post('/laboratory/transaction/{id}/damaged', [TeacherborrowController::class, 'damaged'])->name('laboratory.transaction.damaged');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/laboratory/requisitions/print-data/{id}', [TeacherborrowController::class, 'print'])->name('laboratory.print-requisition');
    Route::post('office/transactions/update', [TeacherborrowController::class, 'return_damaged'])->name('lab.transactions.update');

    Route::post('/laboratory/notify-borrower', [TransactionOfficeController::class, 'notifyBorrower'])->name('notify.user');
    Route::get('/laboratory/requisitions/chart', [TeacherborrowController::class, 'getChartData'])->name('laboratory-office.chart');

    Route::get('/laboratory/equipment-items', [LaboratoryController::class, 'equipment_items'])->name('laboratory-equipments.index');
    Route::get('/laboratory/equipment-items-history/{id}', [LaboratoryController::class, 'history'])->name('laboratory-items-history');
});

Route::middleware(['auth', 'role:superadmin'])->group(function () {

    Route::get('superadmin/dashboard', [SuperadminController::class, 'index'])->name('superadmin.dashboard');
    Route::get('superadmin/computer_engineering', [SComputerController::class, 'index'])->name('superadmin.computer_engineering.index');
    Route::get('superadmin/computer_engineering/create', [SComputerController::class, 'create'])->name('superadmin.computer_engineering.create');
    Route::post('superadmin/computer_engineering/store', [SComputerController::class, 'store'])->name('superadmin.computer_engineering.store');
    Route::get('superadmin/computer_engineering/{id}/edit', [SComputerController::class, 'edit'])->name('superadmin.computer_engineering.edit');
    Route::get('superadmin/computer_engineering/{id}/show', [SComputerController::class, 'show'])->name('superadmin.computer_engineering.show');
    Route::put('superadmin/computer_engineering/{id}/update', [SComputerController::class, 'update'])->name('superadmin.computer_engineering.update');
    Route::delete('superadmin/computer_engineering/{id}/destroy', [SComputerController::class, 'destroy'])->name('superadmin.computer_engineering.destroy');


    Route::get('superadmin/testing', [STestingController::class, 'index'])->name('superadmin.testing.index');
    Route::get('superadmin/testing/create', [STestingController::class, 'create'])->name('superadmin.testing.create');
    Route::post('superadmin/store', [STestingController::class, 'store'])->name('superadmin.testing.store');
    Route::get('superadmin/testing/{id}/edit', [STestingController::class, 'edit'])->name('superadmin.testing.edit');
    Route::get('superadmin/testing/{id}/show', [STestingController::class, 'show'])->name('superadmin.testing.show');
    Route::put('superadmin/testing/{id}/update', [STestingController::class, 'update'])->name('superadmin.testing.update');
    Route::delete('superadmin/testing/{id}/destroy', [STestingController::class, 'destroy'])->name('superadmin.testing.destroy');

    Route::get('superadmin/construction', [SConstructionController::class, 'index'])->name('superadmin.construction.index');
    Route::get('superadmin/construction/create', [SConstructionController::class, 'create'])->name('superadmin.construction.create');
    Route::post('superadmin/construction/store', [SConstructionController::class, 'store'])->name('superadmin.construction.store');
    Route::get('superadmin/construction/{id}/edit', [SConstructionController::class, 'edit'])->name('superadmin.construction.edit');
    Route::get('superadmin/construction/{id}/show', [SConstructionController::class, 'show'])->name('superadmin.construction.show');
    Route::put('superadmin/construction/{id}/update', [SConstructionController::class, 'update'])->name('superadmin.construction.update');
    Route::delete('superadmin/construction/{id}/destroy', [SConstructionController::class, 'destroy'])->name('superadmin.construction.destroy');

    Route::get('superadmin/surveying', [SSurveyingController::class, 'index'])->name('superadmin.surveying.index');
    Route::get('superadmin/surveying/create', [SSurveyingController::class, 'create'])->name('superadmin.surveying.create');
    Route::post('superadmin/surveying/store', [SSurveyingController::class, 'store'])->name('superadmin.surveying.store');
    Route::get('superadmin/surveying/{id}/edit', [SSurveyingController::class, 'edit'])->name('superadmin.surveying.edit');
    Route::get('superadmin/surveying/{id}/show', [SSurveyingController::class, 'show'])->name('superadmin.surveying.show');
    Route::put('superadmin/surveying/{id}/update', [SSurveyingController::class, 'update'])->name('superadmin.surveying.update');
    Route::delete('superadmin/surveying/{id}/destroy', [SSurveyingController::class, 'destroy'])->name('superadmin.surveying.destroy');


    Route::get('superadmin/fluid', [SFLuidController::class, 'index'])->name('superadmin.fluid.index');
    Route::get('superadmin/fluid/create', [SFLuidController::class, 'create'])->name('superadmin.fluid.create');
    Route::post('superadmin/fluid/store', [SFLuidController::class, 'store'])->name('superadmin.fluid.store');
    Route::get('superadmin/fluid/{id}/show', [SFLuidController::class, 'show'])->name('superadmin.fluid.show');
    Route::get('superadmin/fluid/{id}/edit', [SFLuidController::class, 'edit'])->name('superadmin.fluid.edit');
    Route::put('superadmin/fluid/{id}/update', [SFLuidController::class, 'update'])->name('superadmin.fluid.update');
    Route::delete('superadmin/fluid/{id}/destroy', [SFLuidController::class, 'destroy'])->name('superadmin.fluid.destroy');



    Route::get('superadmin/equipment', [SEquipmentController::class, 'index'])->name('superadmin.equipment.index');
    Route::get('superadmin/supplies', [SSuppliesController::class, 'index'])->name('superadmin.supplies.index');
    Route::resource('users', UserManagementController::class);
    Route::get('superadmin/transaction', [TeacherborrowController::class, 'index'])->name('superadmin.transaction.index');
    Route::get('superadmin/site-transactions', [AdminSiteOffice::class, 'index'])->name('superadmin.site.index');

    Route::get('superadmin/site-office-transaction', [AdminSiteOffice::class, 'index'])->name('superadmin.site-transactions.index');
    Route::get('/superadmin/requisitions/chart', [TeacherborrowController::class, 'getChartData'])->name('requisitions.chart');
    Route::get('/superadmin/office/chart', [TeacherborrowController::class, 'offcieChartData'])->name('office.chart');


    Route::get('/superadmin/laboratory-items', [LaboratoryController::class, 'equipment_items'])->name('superadmin.lab-items');
    Route::get('/superadmin/equipment-items-history/{id}', [LaboratoryController::class, 'history'])->name('superadmin-items-history');

    Route::get('/superadmin/site-office-equipment', [EquipmentController::class, 'equipment_items'])->name('superadmin.site-equipment-items.index');
    Route::get('/superadmin/site-office-equipment-history/{id}', [EquipmentController::class, 'equipment_items_history'])->name('superadmin.site-equipment-items-history.index');



    Route::get('/superadmin/equipment/create', [AdminEquipmentController::class, 'create'])->name('admin.equipment.create');
    Route::post('/superadmin/equipment/store', [AdminEquipmentController::class, 'store'])->name('admin.equipment.store');
    Route::get('/superadmin/equipment/{id}/show', [AdminEquipmentController::class, 'show'])->name('admin.equipment.show');
    Route::put('/superadmin/equipment/{id}/update', [AdminEquipmentController::class, 'update'])->name('admin.equipment.update');
    Route::get('/superadmin/equipment/{id}/edit', [AdminEquipmentController::class, 'edit'])->name('admin.equipment.edit');
    Route::delete('/superadmin/equipment/{id}/delete', [AdminEquipmentController::class, 'destroy'])->name('admin.equipment.destroy');


    Route::get('/superadmin/supplies/create', [AdminSuppliesController::class, 'create'])->name('admin.supplies.create');
    Route::post('/superadmin/supplies/store', [AdminSuppliesController::class, 'store'])->name('admin.supplies.store');
    Route::get('/superadmin/supplies/{id}/show', [AdminSuppliesController::class, 'show'])->name('admin.supplies.show');
    Route::put('/superadmin/supplies/{id}/update', [AdminSuppliesController::class, 'update'])->name('admin.supplies.update');
    Route::get('/superadmin/supplies/{id}/edit', [AdminSuppliesController::class, 'edit'])->name('admin.supplies.edit');
    Route::delete('/superadmin/supplies/{id}/delete', [AdminSuppliesController::class, 'destroy'])->name('admin.supplies.destroy');
});



Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/supplies/select/{id}', [Office_UserController::class, 'select'])->name('supplies.select');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
    Route::get('/office_user/create', [TransactionOfficeController::class, 'create'])->name('office_user.create');
    Route::post('/office_user/selectCategory', [TransactionOfficeController::class, 'selectCategory'])->name('office_user.selectCategory');
    Route::get('/office/selected-items/{id}', [TransactionOfficeController::class, 'selectedItems'])->name('office_user.items-selected');
    Route::get('/teachersborrow/create', [TeacherBorrowController::class, 'create'])->name('teachersborrow.create');
    Route::post('/teachersborrow/selectCategory', [TeacherBorrowController::class, 'selectCategory'])->name('teachersborrow.selectCategory');

    Route::post('/teachersborrow/store', [TeacherBorrowController::class, 'store'])->name('teachersborrow.store');
    Route::post('/office_user', [TransactionOfficeController::class, 'store'])->name('office_user.store');
    Route::get('profile/notification', [NotificationController::class, 'index'])->name('notification.index');
    Route::get('/notification/markAllAsRead', [NotificationController::class, 'markAllAsRead'])->name('notification.markAllAsRead');
    Route::delete('/notification/remove/{index}', [NotificationController::class, 'removeNotification'])->name('notification.remove');

    Route::get('/items/items-selected', [TeacherBorrowController::class, 'findMatchingItems'])->name('find-items');
});



Route::middleware(['auth', 'role:dean'])->group(function () {
    Route::get('dean/dashboard', [DeanController::class, 'index'])->name('dean.dashboard');
    Route::get('dean/computer_engineering', [DComputerController::class, 'index'])->name('computer_engineering.index');
    Route::get('dean/construction', [DConstructionController::class, 'index'])->name('construction.index');
    Route::get('dean/fluid', [DFLuidController::class, 'index'])->name('fluid.index');
    Route::get('dean/testing', [DTestingController::class, 'index'])->name('testing.index');
    Route::get('dean/surveying', [DSurveyingController::class, 'index'])->name('surveying.index');
    Route::get('dean/equipment', [DEquipmentController::class, 'index'])->name('equipment.index');
    Route::get('dean/supplies', [DSuppliesController::class, 'index'])->name('supplies.index');
    Route::get('dean/transactions', [TeacherBorrowController::class, 'dean_index'])->name('dean.transactions');
    Route::get('dean/transactions/view-details/{id}', [TeacherBorrowController::class, 'show_data'])->name('dean.transactions.show');
    Route::put('dean/laboratory/change-requisition-details/{id}', [TeacherborrowController::class, 'dean_decision'])->name('dean.borrows.update');


    Route::get('/dean/requisitions/chart', [TeacherborrowController::class, 'getChartData'])->name('dean.requisitions.chart');
    Route::get('/dean/office/chart', [TeacherborrowController::class, 'offcieChartData'])->name('dean.office.chart');
    Route::get('/dean/site-transactions', [TransactionOfficeController::class, 'index'])->name('dean.transactions.site');


    Route::get('/dean/site-office-requisition', [OfficeRequisitionController::class, 'index'])->name('site-requisition.index');
    Route::get('/dean/site-office-requisition/{id}', [OfficeRequisitionController::class, 'show'])->name('site-requisition.show');
    Route::post('/dean/site-office-requisition', [OfficeRequisitionController::class, 'approve'])->name('site-requisition.approve');
    Route::get('/dean/laboratory-items', [DeanController::class, 'lab_items'])->name('dean.laboratory-items');
    Route::get('/dean/laboratory-items/history/{id}', [DeanController::class, 'history'])->name('dean.laboratory-items-history');

    Route::get('/dean/equipment-items', [DeanController::class, 'equipment_items'])->name('dean.equipment-items');
    Route::get('/dean/office-equipment-history/{id}', [DeanController::class, 'equipment_items_history'])->name('dean.equipment-items-history.index');
});


Route::middleware(['auth', 'exclude.user'])->group(function () {
    Route::controller(GeneralReportsController::class)->group(function () {
        Route::get('/reports', 'index')->name('auth.reports');
        Route::get('/reports/lost-damaged-items', 'filter')->name('auth.filter-reports');
    });
});

Route::middleware(['auth', 'site.exclusive'])->group(function () {
    Route::controller(GeneralReportsController::class)->group(function () {
        Route::get('/site-office/reports', 'site_reports')->name('site.reports');
        Route::get('/site-office/reports/lost-damaged-items', 'filter_type')->name('auth.site-filter-reports');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/check-unique-serial', [LaboratoryItemsController::class, 'check'])->name('check-serial');
});




Route::middleware(['auth'])->group(function () {
    Route::controller(PrintManagementController::class)->group(function () {
        Route::post('print', 'store')->name('print.store');
        Route::post('print-all', 'generateAll')->name('print-all-items');
        Route::post('print-supplies', 'printSupplies')->name('print-supplies');
        Route::post('print-all-supplies', 'printAllSupplies')->name('print-all-supplies');
        Route::post('print-equipments', 'printEquipments')->name('print-equipments');
        Route::post('print-all-equipments', 'printAllEquipments')->name('print-all-equipments');
        Route::post('print-site-equipment-reports', 'siteReports')->name('print-site-equipment-reports');
        Route::post('print-all-equipments-reports', 'AllSiteEquipmentReports')->name('print-all-equipments-reports');
        Route::post('laboratory-reports', 'printLabReport')->name('print-lab-report');
        Route::post('laboratory-reports-all', 'printAllLabReport')->name('print-Alllab-report');
        Route::post('laboratory-requisition-reports-print', 'requsitionPrint')->name('print-laboratory-requisition-reports');
        Route::post('laboratory-requisition-reports-print-all', 'printAllRequisitionReports')->name('print-laboratory-requisition-reports-all');
        Route::post('supplies-report-print', 'suppliesReportPrint')->name('suppliesReportPrint');
        Route::post('supplies-report-print-all', 'suppliesReportPrintAll')->name('suppliesReportPrintAll');
    });
});

require __DIR__ . '/auth.php';
