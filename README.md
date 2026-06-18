# PHP_Laravel10_Locked


## Project Description

PHP_Laravel10_Locked is a Laravel 10 CRUD application that demonstrates record locking and unlocking functionality using the Laravel Locked package.

The application allows users to create, edit, delete, search, filter, lock, and unlock posts while preventing modifications to locked records. It features search, status filtering, pagination, flash notifications, and a responsive Bootstrap 5 user interface.


## Key Features

🔐 Lock and Unlock Posts

📝 Complete CRUD Operations

🔍 Search Posts by Title

📊 Filter Posts by Lock Status

📄 Pagination Support

🚫 Prevent Editing Locked Records

🚫 Prevent Deleting Locked Records

✅ Success and Error Notifications

🎨 Modern Bootstrap 5 UI

📱 Responsive Design

📦 Laravel Package Integration

🛠 Clean Project Structure



## Technologies Used

* Laravel 10
* PHP 8+
* MySQL
* Bootstrap 5
* HTML5
* CSS3
* Laravel Eloquent ORM
* Laravel Pagination
* Laravel Locked Package (sfolador/laravel-locked)



## Project Highlights

* Integration of Laravel Locked Package
* Record Locking and Unlocking System
* Search and Status Filtering
* Pagination with Bootstrap Styling
* User-Friendly Dashboard Interface
* Flash Success and Error Messages
* Protection Against Editing Locked Records
* Protection Against Deleting Locked Records
* Clean MVC Architecture
* Professional CRUD Implementation



## Application Flow

1. User opens Posts Dashboard
2. User creates a new post
3. Post is stored in the database
4. User can lock or unlock any post
5. Locked posts cannot be edited
6. Locked posts cannot be deleted
7. User can search posts by title
8. User can filter locked or unlocked posts
9. Results are displayed with pagination
10. Success and error messages are shown after actions


## Requirements

- PHP 8.1+
- Composer
- MySQL
- Laravel 10


---



## Installation Steps


---


## STEP 1: Create Laravel 10 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel10_Locked "^10.0"

```

### Go inside project:

```
cd PHP_Laravel10_Locked

```

#### Explanation:

Creates a fresh Laravel 10 application with all required dependencies.

This serves as the foundation for building the Laravel Locked package demo project.


## STEP 2: Database Setup 

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel10_Locked
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel10_Locked


```



#### Explanation:

Configures the MySQL database connection using the .env file.

The database stores posts and lock status information.



## STEP 3: Install Package

### Run:

```
composer require sfolador/laravel-locked

```

#### Explanation:

Installs the Laravel Locked package developed by sfolador.

The package provides lock and unlock functionality for Eloquent models.



## STEP 4: Publish Config

### Run:

```
php artisan vendor:publish --tag="locked-config"

```

#### Explanation:

Publishes the package configuration file into the Laravel application.

Allows customization of locking behavior and column settings.



## STEP 5: Create Model + Migration + Controller

### Run:

```
php artisan make:model Post -mcr

```

### database/migrations/create_posts_table.php

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {

            $table->id();

            $table->string('title');

            $table->text('description');

            $table->timestamp(config('locked.locking_column'))->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

```


### Create database only:

```
php artisan migrate

```

### app/Models/Post.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sfolador\Locked\Traits\HasLocks;

class Post extends Model
{
    use HasLocks;

    protected $fillable = [
        'title',
        'description',
    ];
}

```


### app/Http/Controllers/PostController.php

```
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::query();

        // Search
        if ($request->filled('search')) {
            $posts->where('title', 'like', '%' . $request->search . '%');
        }

        // Status Filter
        if ($request->status == 'locked') {
            $posts->whereNotNull('locked_at');
        }

        if ($request->status == 'unlocked') {
            $posts->whereNull('locked_at');
        }

        $posts = $posts
            ->oldest()
            ->paginate(4)
            ->withQueryString();

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        Post::create($request->all());

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        if ($post->isLocked()) {

            return redirect()
                ->route('posts.index')
                ->with('error', 'Post is locked');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if ($post->isLocked()) {

            return back()
                ->with('error', 'Locked post cannot be updated');
        }

        $post->update($request->all());

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        if ($post->isLocked()) {

            return back()
                ->with('error', 'Locked post cannot be deleted');
        }

        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    public function lock(Post $post)
    {
        $post->lock();

        return back()
            ->with('success', 'Post locked successfully.');
    }

    public function unlock(Post $post)
    {
        $post->unlock();

        return back()
            ->with('success', 'Post unlocked successfully.');
    }
}

```


#### Explanation: 

Creates the Post model, migration, and controller for CRUD operations.

Integrates the HasLocks trait to enable record locking functionality.





## STEP 6: Add Routes

### routes/web.php

```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('posts', PostController::class);

Route::post(
    '/posts/{post}/lock',
    [PostController::class, 'lock']
)->name('posts.lock');

Route::post(
    '/posts/{post}/unlock',
    [PostController::class, 'unlock']
)->name('posts.unlock');

```

#### Explanation: 

Defines application URLs and connects them to controller methods.

Enables CRUD, lock, and unlock operations through web routes.



## STEP 7: Create View Folder and Blade Files

### Run:

```
mkdir resources/views/posts

```


### resources/views/posts/index.blade.php


``` 
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Locked - Posts</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .header-card {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            color: white;
            border-radius: 15px;
        }

        .table-card {
            border: none;
            border-radius: 15px;
        }

        .locked-badge {
            background: #dc3545;
        }

        .unlocked-badge {
            background: #198754;
        }

        .btn-action {
            min-width: 80px;
        }

        .stats-card {
            border: none;
            border-radius: 15px;
        }
    </style>


</head>

<body>

    <div class="container py-5">


        <div class="card header-card shadow mb-4">
            <div class="card-body text-center py-4">

                <h1 class="fw-bold">
                    🔐 Laravel Locked Package Demo
                </h1>

                <p class="mb-0">
                    Manage Posts with Lock & Unlock Functionality
                </p>

            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <strong>Success!</strong> {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <strong>Error!</strong> {{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>
            </div>
        @endif

        <div class="row mb-4">

            <div class="col-md-4">
                <div class="card bg-primary text-white shadow stats-card">
                    <div class="card-body text-center">
                        <h5>Total Posts</h5>
                        <h2>{{ \App\Models\Post::count() }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-danger text-white shadow stats-card">
                    <div class="card-body text-center">
                        <h5>Locked Posts</h5>
                        <h2>{{ \App\Models\Post::whereNotNull('locked_at')->count() }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-success text-white shadow stats-card">
                    <div class="card-body text-center">
                        <h5>Unlocked Posts</h5>
                        <h2>{{ \App\Models\Post::whereNull('locked_at')->count() }}</h2>
                    </div>
                </div>
            </div>

        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h3 class="fw-bold">
                Posts List
            </h3>

            <a href="{{ route('posts.create') }}" class="btn btn-primary">

                + Add Post

            </a>

        </div>

        <form method="GET" action="{{ route('posts.index') }}" class="mb-4">

            <div class="row g-2">

                <div class="col-md-5">

                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Search post title...">

                </div>

                <div class="col-md-3">

                    <select name="status" class="form-select">

                        <option value="">
                            All Status
                        </option>

                        <option value="locked" {{ request('status') == 'locked' ? 'selected' : '' }}>
                            Locked
                        </option>

                        <option value="unlocked" {{ request('status') == 'unlocked' ? 'selected' : '' }}>
                            Unlocked
                        </option>

                    </select>

                </div>

                <div class="col-md-2">

                    <button class="btn btn-primary w-100">
                        Search
                    </button>

                </div>

                <div class="col-md-2">

                    <a href="{{ route('posts.index') }}" class="btn btn-secondary w-100">

                        Reset

                    </a>

                </div>

            </div>

        </form>

        <div class="card table-card shadow">

            <div class="card-body">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>#</th>

                            <th>Title</th>

                            <th>Description</th>

                            <th>Status</th>

                            <th width="350">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($posts as $post)

                            <tr>

                                <td>
                                    {{ $posts->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    {{ $post->title }}
                                </td>

                                <td>
                                    {{ \Illuminate\Support\Str::limit($post->description, 50) }}
                                </td>

                                <td>

                                    @if($post->isLocked())

                                        <span class="badge locked-badge">
                                            🔒 Locked
                                        </span>

                                    @else

                                        <span class="badge unlocked-badge">
                                            🔓 Unlocked
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <div class="d-flex gap-2 flex-wrap">

                                        @if($post->isLocked())

                                            <form method="POST" action="{{ route('posts.unlock', $post) }}">

                                                @csrf

                                                <button class="btn btn-success btn-sm btn-action">
                                                    Unlock
                                                </button>

                                            </form>

                                        @else

                                            <form method="POST" action="{{ route('posts.lock', $post) }}">

                                                @csrf

                                                <button class="btn btn-warning btn-sm btn-action">
                                                    Lock
                                                </button>

                                            </form>

                                            <a href="{{ route('posts.edit', $post) }}"
                                                class="btn btn-primary btn-sm btn-action">
                                                Edit
                                            </a>

                                        @endif

                                        <form method="POST" action="{{ route('posts.destroy', $post) }}"
                                            onsubmit="return confirm('Delete this post?')">

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-danger btn-sm btn-action">
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="text-center py-4">
                                    No Posts Found
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

                <div class="d-flex justify-content-center mt-4">

                    {{ $posts->links('pagination::bootstrap-5') }}

                </div>

            </div>

        </div>

    </div>

</body>

</html>

```


### resources/views/posts/create.blade.php
   
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#f4f6f9">

<div class="container py-5">

    <div class="card shadow mx-auto" style="max-width:700px">

        <div class="card-header bg-primary text-white">

            <h3 class="mb-0">
                Create New Post
            </h3>

        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ route('posts.store') }}">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Title
                    </label>

                    <input type="text"
                           name="title"
                           class="form-control">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea name="description"
                              rows="5"
                              class="form-control"></textarea>

                </div>

                <button class="btn btn-success">
                    Save Post
                </button>

                <a href="{{ route('posts.index') }}"
                   class="btn btn-secondary">

                    Back

                </a>

            </form>

        </div>

    </div>

</div>

</body>
</html>

```


### resources/views/posts/edit.blade.php

```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#f4f6f9">

<div class="container py-5">

    <div class="card shadow mx-auto" style="max-width:700px">

        <div class="card-header bg-warning">

            <h3 class="mb-0">
                Edit Post
            </h3>

        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ route('posts.update',$post) }}">

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label">
                        Title
                    </label>

                    <input type="text"
                           name="title"
                           value="{{ $post->title }}"
                           class="form-control">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea name="description"
                              rows="5"
                              class="form-control">{{ $post->description }}</textarea>

                </div>

                <button class="btn btn-primary">
                    Update Post
                </button>

                <a href="{{ route('posts.index') }}"
                   class="btn btn-secondary">

                    Back

                </a>

            </form>

        </div>

    </div>

</div>

</body>
</html>

```



#### Explanation: 

Creates the user interface using Laravel Blade templates.

Provides pages for listing, creating, editing, locking, and unlocking posts.




## STEP 8: Run the Application  

### Start dev server:

```
php artisan serve

```


### Open in browser:

```
http://127.0.0.1:8000/posts 

```

#### Explanation:

Starts the Laravel development server for local testing.

Allows users to access and interact with the application through a browser.



## Expected Output:


### Posts Overview Dashboard 


<img width="1892" height="963" alt="Screenshot 2026-06-18 152620" src="https://github.com/user-attachments/assets/50f3ed3d-2b34-47cc-8cd9-dc95c25338a1" />


### Post Creation Form


<img width="1908" height="900" alt="Screenshot 2026-06-18 152650" src="https://github.com/user-attachments/assets/2626ed9d-3607-4057-acd6-5c3b8084a740" />

<img width="1887" height="960" alt="Screenshot 2026-06-18 152913" src="https://github.com/user-attachments/assets/1a52d2bf-4cb1-4beb-8974-44f4abd544f3" />


### Post Update Form


<img width="1910" height="958" alt="Screenshot 2026-06-18 152940" src="https://github.com/user-attachments/assets/4fa08059-312a-4584-b12e-85cf9bd8097f" />

<img width="1886" height="965" alt="Screenshot 2026-06-18 152958" src="https://github.com/user-attachments/assets/28309794-d400-4fc5-8b49-da62e7dc768b" />


### Locked Post Management


<img width="1889" height="962" alt="Screenshot 2026-06-18 153131" src="https://github.com/user-attachments/assets/f7561dbc-0953-4588-a5e4-d39cd691a059" />


### Unlocked Post Management


<img width="1891" height="960" alt="Screenshot 2026-06-18 153222" src="https://github.com/user-attachments/assets/ab123507-f08b-4d17-a6e3-6cf9911f684e" />


### Advanced Search & Filtering


<img width="1900" height="954" alt="Screenshot 2026-06-18 153245" src="https://github.com/user-attachments/assets/62ba0c5f-6da7-47c4-9c2f-ef204cb49fbb" />

<img width="1887" height="965" alt="Screenshot 2026-06-18 153314" src="https://github.com/user-attachments/assets/1cfd2655-b457-4d77-b207-0d27fafb9f6a" />

<img width="1911" height="952" alt="Screenshot 2026-06-18 153330" src="https://github.com/user-attachments/assets/1fd3c52e-0651-45c2-b419-fc557cc7a350" />

<img width="1878" height="957" alt="Screenshot 2026-06-18 153343" src="https://github.com/user-attachments/assets/0f25db48-e54f-4224-80b3-261e52c6bff1" />


### Pagination Management


<img width="1885" height="961" alt="Screenshot 2026-06-18 153358" src="https://github.com/user-attachments/assets/1113eb7e-8ebb-404c-9763-ae9e04e01fab" />


### Post Deletion Process 


<img width="1876" height="953" alt="Screenshot 2026-06-18 160848" src="https://github.com/user-attachments/assets/c59391ac-370e-48a1-b7c0-544f58629ecb" />

<img width="1882" height="956" alt="Screenshot 2026-06-18 160904" src="https://github.com/user-attachments/assets/33e642a7-e0d7-415f-9104-5f789e024a02" />



---



## Project Folder Structure

```
PHP_Laravel10_Locked
│
├── app
│   ├── Http
│   │   └── Controllers
│   │       └── PostController.php
│   │
│   └── Models
│       └── Post.php
│
├── bootstrap
│
├── config
│   └── locked.php
│
├── database
│   ├── factories
│   ├── migrations
│   │   └── xxxx_xx_xx_create_posts_table.php
│   │
│   └── seeders
│
├── public
│
├── resources
│   └── views
│       └── posts
│           ├── index.blade.php
│           ├── create.blade.php
│           └── edit.blade.php
│
├── routes
│   └── web.php
│
├── storage
│
├── tests
│
├── .env
├── composer.json
├── package.json
├── artisan
├── vite.config.js
└── README.md
```
