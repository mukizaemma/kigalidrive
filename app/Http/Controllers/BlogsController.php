<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\Program;
use App\Models\Setting;
use App\Support\SchemaHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogsController extends Controller
{
    private function limitText(?string $text, int $limit = 100): string
    {
        $text = strip_tags((string) $text);
        if (strlen($text) <= $limit) {
            return $text;
        }

        return substr($text, 0, $limit).'...';
    }

    private function mapBlogPreview($post)
    {
        $post->short_body = $this->limitText($post->body, 100);

        return $post;
    }

    public function index()
    {
        $latestBlogs = Blog::latest()->get()->map(fn ($post) => $this->mapBlogPreview($post));

        $mostViewedQuery = Blog::query();
        if (SchemaHelper::hasColumn('blogs', 'views')) {
            $mostViewedQuery->orderByDesc('views');
        } else {
            $mostViewedQuery->latest();
        }
        $mostViewedBlogs = $mostViewedQuery->get()->map(fn ($post) => $this->mapBlogPreview($post));

        $programs = SchemaHelper::legacyProgramsEnabled() ? Program::all() : collect();
        $setting = Setting::first();

        return view('admin.posts.blogs', [
            'programs' => $programs,
            'latestBlogs' => $latestBlogs,
            'mostViewedBlogs' => $mostViewedBlogs,
            'setting' => $setting,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $fileName = '';
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images/blogs');
            $fileName = basename($path);
        }

        $slug = Str::of($request->input('title'))->slug('-');
        if (Blog::where('slug', $slug)->exists()) {
            $slug .= '-'.uniqid();
        }

        $payload = [
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'image' => $fileName,
            'slug' => $slug,
            'status' => $request->input('status', 'Active'),
        ];

        if (SchemaHelper::hasColumn('blogs', 'added_by')) {
            $payload['added_by'] = $request->user()->id;
        } elseif (SchemaHelper::hasColumn('blogs', 'user_id')) {
            $payload['user_id'] = $request->user()->id;
        }

        Blog::create($payload);

        return redirect()->route('getBlogs')->with('success', 'New Post has been saved successfully');
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);

        return view('admin.posts.blogUpdate', [
            'blog' => $blog,
        ]);
    }

    public function view($id)
    {
        $post = Blog::findOrFail($id);
        $comments = BlogComment::where('blog_id', $post->id)->latest()->get();
        $totalComments = $comments->count();
        $pendingComments = $comments->where('status', 'Unpublished')->count();
        $publishedComments = $comments->where('status', 'Published')->count();
        $program = SchemaHelper::legacyProgramsEnabled() ? Program::all() : collect();

        return view('admin.posts.blogView', [
            'post' => $post,
            'program' => $program,
            'comments' => $comments,
            'totalComments' => $totalComments,
            'pendingComments' => $pendingComments,
            'publishedComments' => $publishedComments,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $post = Blog::findOrFail($id);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('public/images/blogs');
                if ($post->image) {
                    Storage::delete('public/images/blogs/'.$post->image);
                }
                $post->image = basename($path);
            }

            foreach (['title', 'body', 'status'] as $field) {
                if ($request->has($field)) {
                    $post->{$field} = $request->input($field);
                }
            }

            if ($post->isDirty('title')) {
                $slug = Str::of($post->title)->slug('-');
                if (Blog::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                    $slug .= '-'.uniqid();
                }
                $post->slug = $slug;
            }

            $post->save();

            return redirect()->route('getBlogs')->with('success', 'Story has been updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function publish(Request $request, $id)
    {
        $post = Blog::findOrFail($id);
        $publishedStatus = SchemaHelper::hasColumn('blogs', 'status') ? 'Published' : 'Active';
        if ($post->status !== $publishedStatus) {
            $post->status = $publishedStatus;
            $post->save();
        }

        return redirect()->route('getBlogs')->with('success', 'Story has been updated successfully');
    }

    public function destroy($id)
    {
        $blogs = Blog::find($id);
        if (! $blogs) {
            return back()->with('error', 'Content not found');
        }
        if ($blogs->image) {
            Storage::delete('public/images/blogs/'.$blogs->image);
        }
        $blogs->delete();

        return back()->with('success', 'Story deleted successfully');
    }

    public function comments()
    {
        $comments = BlogComment::latest()->get();

        return view('admin.posts.comments', [
            'comments' => $comments,
        ]);
    }
}
