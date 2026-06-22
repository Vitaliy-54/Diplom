<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:10240'], // до 10MB
        ]);

        $user = Auth::user();
        $avatarDir = "avatars/{$user->id}";

        // Удалить старые аватары
        foreach (Storage::files($avatarDir) as $file) {
            if (str_starts_with(basename($file), 'avatar.')) {
                Storage::delete($file);
            }
        }

        $extension = $request->file('avatar')->getClientOriginalExtension();
        $filename = "avatar.{$extension}";

        Storage::makeDirectory($avatarDir);
        $request->file('avatar')->storeAs($avatarDir, $filename);

        return back()->with('status', 'Аватар успешно загружен.');
    }

    public function serve($user, $filename)
{
    $path = "avatars/{$user}/{$filename}";

    if (!Storage::exists($path)) {
        abort(404);
    }

    $file = Storage::path($path);
    $lastModified = Storage::lastModified($path);
    $etag = md5_file($file);

    // Проверяем заголовки If-None-Match и If-Modified-Since
    if (
        (request()->header('If-None-Match') === $etag) ||
        (request()->header('If-Modified-Since') && strtotime(request()->header('If-Modified-Since')) >= $lastModified)
    ) {
        return response()->noContent()->setStatusCode(304);
    }

    return response()->file($file, [
        'Content-Type' => Storage::mimeType($path),
        'Cache-Control' => 'public, max-age=31536000, immutable', // 1 год
        'Expires' => now()->addYear()->toRfc7231String(),
        'Last-Modified' => gmdate('D, d M Y H:i:s T', $lastModified),
        'Etag' => $etag,
    ]);
}

    /**
     * Вернуть URL к аватару или null, если не загружен.
     */
    public function getAvatarData()
    {
        $user = Auth::user();
        $avatarFilename = $this->getUserAvatarFilename($user->id);

        if ($avatarFilename) {
            $avatarUrl = route('avatar.serve', ['user' => $user->id, 'filename' => $avatarFilename]);
            return response()->json([
                'avatar_url' => $avatarUrl,
                'initials' => null,
            ]);
        }

        return response()->json([
            'avatar_url' => null,
            'initials' => $this->getInitials($user->name),
        ]);
    }

    /**
     * Определить, есть ли загруженный аватар у пользователя.
     */
    private function getUserAvatarFilename($userId): ?string
    {
        $avatarDir = "avatars/{$userId}";
        $files = Storage::files($avatarDir);

        foreach ($files as $file) {
            if (str_starts_with(basename($file), 'avatar.')) {
                return basename($file);
            }
        }

        return null;
    }

    /**
     * Получить инициалы пользователя.
     */
    private function getInitials(string $name): string
    {
        $words = explode(' ', trim($name));
        return strtoupper(
            mb_substr($words[0] ?? '', 0, 1) . mb_substr($words[1] ?? '', 0, 1)
        );
    }
}