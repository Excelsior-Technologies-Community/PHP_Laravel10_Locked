<!DOCTYPE html>

<html>

<head>

    <title>View Post</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>


<body class="bg-light">


    <div class="container py-5">


        <div class="card shadow">


            <div class="card-header bg-primary text-white">

                <h3>
                    Post Details
                </h3>

            </div>


            <div class="card-body">


                <h4>
                    {{ $post->title }}
                </h4>


                <p>

                    {{ $post->description }}

                </p>



                @if($post->isLocked())


                <div class="alert alert-danger">

                    🔒 This post is locked.
                    <br>

                    Only viewing is allowed.

                </div>


                @else


                <div class="alert alert-success">

                    🔓 This post is unlocked.

                </div>


                @endif



                <a href="{{route('posts.index')}}"
                    class="btn btn-secondary">

                    Back

                </a>


            </div>


        </div>


    </div>


</body>

</html>