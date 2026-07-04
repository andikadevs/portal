<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ArticleRequest;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Mews\Purifier\Facades\Purifier;

class ArticleController extends Controller
{
    /**
     * CRUD Artikel + upload thumbnail (PRD F-10).
     */
    public function index(Request $request): View
    {
        $articles = Article::with(['category', 'author'])
            ->latest()
            ->paginate(10);

        return view('admin.articles.index', compact('articles'));
    }

    public function create(): View
    {
        return view('admin.articles.create', [
            'article' => new Article(['published_at' => now()]),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(ArticleRequest $request): RedirectResponse
    {
        $data = $this->prepareData($request);
        $data['user_id'] = $request->user()->id;
        $data['slug'] = $this->uniqueSlug($request->string('title'));

        Article::create($data);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Artikel dipublikasikan.');
    }

    public function edit(Article $article): View
    {
        return view('admin.articles.edit', [
            'article' => $article,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(ArticleRequest $request, Article $article): RedirectResponse
    {
        $data = $this->prepareData($request, $article);

        $article->update($data);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Artikel diperbarui.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $this->deleteLocalThumbnail($article);

        $article->delete();

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Artikel dihapus.');
    }

    /**
     * Siapkan data artikel (sanitasi body, upload thumbnail).
     *
     * @return array<string, mixed>
     */
    protected function prepareData(ArticleRequest $request, ?Article $article = null): array
    {
        $data = [
            'title' => $request->string('title'),
            'category_id' => $request->integer('category_id'),
            'excerpt' => $request->filled('excerpt') ? $request->string('excerpt') : null,
            // Sanitasi HTML dari editor sebelum disimpan (PRD §4.1 — anti-XSS).
            'body' => Purifier::clean($request->input('body')),
            'published_at' => $request->filled('published_at') ? $request->date('published_at') : now(),
        ];

        if ($request->hasFile('thumbnail')) {
            // Upload manual: file baru menggantikan thumbnail lama (jika berupa file lokal).
            $this->deleteLocalThumbnail($article);
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        } elseif ($request->filled('thumbnail_url')) {
            // Pilihan dari Pexels: simpan URL eksternal langsung.
            $this->deleteLocalThumbnail($article);
            $data['thumbnail'] = $request->string('thumbnail_url')->value();
        }

        return $data;
    }

    /**
     * Hapus file thumbnail lokal (abaikan jika thumbnail berupa URL eksternal).
     */
    protected function deleteLocalThumbnail(?Article $article): void
    {
        $thumbnail = $article?->thumbnail;

        if ($thumbnail && ! str_starts_with($thumbnail, 'http')) {
            Storage::disk('public')->delete($thumbnail);
        }
    }

    /**
     * Buat slug unik dari judul artikel.
     */
    protected function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (Article::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }
}
