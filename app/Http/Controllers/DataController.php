<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DataController extends Controller
{
    public function showForm()
    {
        return view('form');
    }

    public function saveData(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255|min:2',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|regex:/^\+?[0-9\s\-()]{7,15}$/',
        ], [
            'full_name.required' => 'Поле ФИО обязательно для заполнения.',
            'email.email' => 'Введите корректный адрес электронной почты.',
            'phone.regex' => 'Введите корректный номер телефона.',
        ]);

        $filename = 'submission_' . Str::uuid()->toString() . '.json';

        if (!Storage::exists('data')) {
            Storage::makeDirectory('data');
        }

        Storage::put('data/' . $filename, json_encode($validated, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        return redirect()->route('form.show')->with('success', 'Данные успешно сохранены в систему!');
    }

    public function showData()
    {
        $files = Storage::files('data');
        $allData = [];

        foreach ($files as $file) {
            $content = Storage::get($file);
            $allData[] = json_decode($content, true);
        }

        return view('table', compact('allData'));
    }
}
