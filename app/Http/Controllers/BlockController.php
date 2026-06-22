<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    // Отображение списка блоков
    public function index()
    {
        $blocks = Block::all();
        return view('blocks.index', compact('blocks'));
    }

    // Форма создания блока
    public function create()
    {
        return view('blocks.create');
    }

    // Сохранение нового блока
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string',
            'link' => 'required|url',
        ]);

        Block::create($request->all());

        return redirect()->route('blocks.index')->with('success', 'Блок успешно создан!');
    }

    // Форма редактирования блока
    public function edit(Block $block)
    {
        return view('blocks.edit', compact('block'));
    }

    // Обновление блока
    public function update(Request $request, Block $block)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string',
            'link' => 'required|url',
        ]);

        $block->update($request->all());

        return redirect()->route('blocks.index')->with('success', 'Блок успешно обновлен!');
    }

    // Удаление блока
    public function destroy(Block $block)
    {
        $block->delete();
        return redirect()->route('blocks.index')->with('success', 'Блок успешно удален!');
    }
}