<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DepositRequisite;
use Illuminate\Http\Request;

class RequisitesController extends Controller
{
    public function index()
    {
        return response()->json([
            'requisites' => DepositRequisite::orderByDesc('id')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'network' => ['nullable', 'string', 'max:120'],
            'details' => ['required', 'string', 'max:5000'],
        ]);

        $r = DepositRequisite::create([
            'title' => $data['title'],
            'network' => $data['network'] ?? null,
            'details' => $data['details'],
            'is_active' => true,
        ]);

        return response()->json(['message' => 'created', 'requisite' => $r]);
    }

    public function toggle(int $id)
    {
        $r = DepositRequisite::findOrFail($id);
        $r->is_active = !$r->is_active;
        $r->save();

        return response()->json(['message' => 'toggled', 'requisite' => $r]);
    }
}

