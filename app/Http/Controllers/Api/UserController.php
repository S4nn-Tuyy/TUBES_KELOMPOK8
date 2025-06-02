<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get all users
     * GET /api/users
     */
    public function index()
    {
        try {
            $users = User::where('is_banned', false)
                ->select('id', 'name', 'email', 'phone', 'role')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user profile
     * GET /api/users/{id}
     */
    public function show($id)
    {
        try {
            $user = User::select('id', 'name', 'email', 'phone', 'role')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get user's products
     * GET /api/users/{id}/products
     */
    public function products($id)
    {
        try {
            $user = User::findOrFail($id);
            $products = $user->products()
                ->where('is_hidden', false)
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'data' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get user products',
                'error' => $e->getMessage()
            ], 404);
        }
    }
} 