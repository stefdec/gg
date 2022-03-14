<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function generatePDF()
    {
        $data = [
            'title' => 'Welcome to OnlineWebTutorBlog.com',
            'author' => "Sanjay"
        ];

        $pdf = PDF::loadView('/customers/invoices/invoice', $data);

        return $pdf->download('invoice.pdf');
    }
}
