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