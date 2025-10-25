<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Helpers\UserActivityHelper;
use Illuminate\Support\Facades\Auth;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::middleware('auth:sanctum')->group(function () {

//     // Rota segura para contagem de usuario online
//     Route::get('/online-users-count', function () {
//         try {
//             return response()->json([
//                 'count' => getOnlineUsersCount(),
//             ]);
//         }
//         catch (\Exception $e) {
//             return response()->json($e->getMessage(), 500);
//         }
//     });

//     // Rota apenas para admins verem detalhes

//     Route::get('/online-users-details', function () {
//         try {
//             if(!Auth::user()->hasRole(['admin', 'super admin'])) {
//                 return response()->json([
//                     'error' => 'Acesso não autorizado',
//                 ], 403);
//             }
//             return response()->json([


//                 'users' =>  $this->getOnlineUsersDetails(), //UserActivityHelper::getOnlineUsersDetails(),
//             ]);
//         }
//         catch (\Exception $e) {
//             return response()->json($e->getMessage(), 500);
//         }
//     });


// });
