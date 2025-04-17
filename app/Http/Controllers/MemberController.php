<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function getMemberByPhone(Request $request)
    {
        $noTelp = $request->query('no_telp');

        $member = Member::where('no_telp', $noTelp)->first();

        if ($member) {
            return response()->json([
                'nama' => $member->nama,
                'poin' => $member->poin
            ]);
        } else {
            return response()->json(null);
        }
    }
    }
