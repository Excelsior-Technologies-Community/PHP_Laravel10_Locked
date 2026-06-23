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

                            <th width="550">
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

                                    <!-- View Button -->
                                    <a href="{{ route('posts.show', $post) }}"
                                        class="btn btn-info btn-sm btn-action">
                                        View
                                    </a>

                                    <!-- History Button -->
                                    <a href="{{ route('posts.history', $post) }}"
                                        class="btn btn-dark btn-sm btn-action">
                                        History
                                    </a>

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

                                    <form method="POST"
                                        action="{{ route('posts.destroy', $post) }}"
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