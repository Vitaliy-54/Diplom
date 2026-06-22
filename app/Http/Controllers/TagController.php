<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    /**
     * Store a newly created tag via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Валидация данных
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:tags,name'
        ], [
            'name.required' => 'Название тега обязательно для заполнения',
            'name.unique' => 'Тег с таким названием уже существует'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('name')
            ], 422);
        }
    
        try {
            $tag = Tag::create([
                'name' => $request->name,
                'user_id' => auth()->id() // если теги привязаны к пользователю
            ]);
    
            return response()->json([
                'success' => true,
                'tag' => [
                    'id' => $tag->id,
                    'name' => $tag->name
                ],
                'message' => 'Тег успешно создан'
            ]);
    
        } catch (\Exception $e) {    
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при создании тега'
            ], 500);
        }
    }

    /**
     * Display a listing of tags for API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexApi()
    {
        $tags = Tag::orderBy('name')->get();
        
        return response()->json([
            'success' => true,
            'tags' => $tags
        ]);
    }

    /**
     * Remove the specified tag.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Tag $tag)
    {
        try {
            // Проверяем, не используется ли тег в заметках
            if ($tag->notes()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Невозможно удалить тег, так как он используется в заметках'
                ], 422);
            }

            $tag->delete();

            return response()->json([
                'success' => true,
                'message' => 'Тег успешно удален'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении тега: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified tag.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Tag $tag)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:tags,name,'.$tag->id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('name')
            ], 422);
        }

        try {
            $tag->update(['name' => $request->name]);

            return response()->json([
                'success' => true,
                'tag' => $tag,
                'message' => 'Тег успешно обновлен'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении тега: ' . $e->getMessage()
            ], 500);
        }
    }
}