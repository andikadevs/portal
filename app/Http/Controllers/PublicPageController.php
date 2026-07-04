<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PublicPageController extends Controller
{
    /**
     * Halaman "Tentang" / profil penerbit (PRD F-06).
     */
    public function about(): View
    {
        return view('about');
    }
}
