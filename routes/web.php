<?php
/**
 * ============================================================
 * HOTEL RESERVATION SYSTEM - Definisi Routes
 * ============================================================
 * 
 * Format: App::get('/url', 'Controller@method');
 *         App::post('/url', 'Controller@method');
 * 
 * Parameter dinamis menggunakan {id}
 */

// ============================================================
// HALAMAN PUBLIK
// ============================================================
App::get('/',                   'RoomController@index');
App::get('/rooms',              'RoomController@index');
App::get('/rooms/detail/{id}',  'RoomController@detail');

// ============================================================
// AUTHENTICATION
// ============================================================
App::get('/login',              'AuthController@loginForm');
App::post('/login',             'AuthController@login');
App::get('/register',           'AuthController@registerForm');
App::post('/register',          'AuthController@register');
App::get('/logout',             'AuthController@logout');

// ============================================================
// USER - BOOKING
// ============================================================
App::get('/booking/create/{id}',    'BookingController@create');
App::post('/booking/store',         'BookingController@store');
App::get('/booking/history',        'BookingController@history');
App::get('/booking/detail/{id}',    'BookingController@detail');

// ============================================================
// USER - PAYMENT
// ============================================================
App::get('/payment/upload/{id}',    'PaymentController@uploadForm');
App::post('/payment/upload/{id}',   'PaymentController@upload');

// ============================================================
// ADMIN - DASHBOARD
// ============================================================
App::get('/admin/dashboard',        'AdminController@dashboard');

// ============================================================
// ADMIN - KELOLA KAMAR
// ============================================================
App::get('/admin/rooms',            'RoomController@adminIndex');
App::get('/admin/rooms/create',     'RoomController@createForm');
App::post('/admin/rooms/store',     'RoomController@store');
App::get('/admin/rooms/edit/{id}',  'RoomController@editForm');
App::post('/admin/rooms/update/{id}', 'RoomController@update');
App::get('/admin/rooms/delete/{id}',  'RoomController@delete');

// ============================================================
// ADMIN - KELOLA BOOKING
// ============================================================
App::get('/admin/bookings',                     'BookingController@adminIndex');
App::post('/admin/bookings/status/{id}',        'BookingController@updateStatus');

// ============================================================
// ADMIN - VERIFIKASI PEMBAYARAN
// ============================================================
App::post('/admin/payments/verify/{id}',        'PaymentController@verify');

// ============================================================
// ADMIN - LAPORAN
// ============================================================
App::get('/admin/reports',          'ReportController@index');
App::get('/admin/reports/monthly',  'ReportController@monthly');

// ============================================================
// ADMIN - ACTIVITY LOGS
// ============================================================
App::get('/admin/logs',             'AdminController@logs');
