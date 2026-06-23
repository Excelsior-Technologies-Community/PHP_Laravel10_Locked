<!DOCTYPE html>

<html>

<head>

    <title>Lock History</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


</head>


<body class="bg-light">


    <div class="container py-5">


        <div class="card shadow">


            <div class="card-header bg-dark text-white">

                <h3>
                    Lock History
                </h3>

            </div>



            <div class="card-body">



                <h5>
                    Post:
                    {{ $post->title }}
                </h5>



                <table class="table table-bordered mt-4">


                    <thead>

                        <tr>

                            <th>
                                Action
                            </th>


                            <th>
                                Reason
                            </th>


                            <th>
                                Date
                            </th>


                        </tr>

                    </thead>



                    <tbody>


                        @foreach($history as $item)


                        <tr>


                            <td>

                                @if($item->action=="locked")

                                <span class="badge bg-danger">

                                    Locked

                                </span>


                                @else

                                <span class="badge bg-success">

                                    Unlocked

                                </span>


                                @endif


                            </td>



                            <td>

                                {{$item->reason}}

                            </td>



                            <td>

                                {{$item->created_at->format('d-m-Y h:i A')}}

                            </td>


                        </tr>


                        @endforeach



                    </tbody>


                </table>



                <a href="{{route('posts.index')}}"
                    class="btn btn-secondary">

                    Back

                </a>



            </div>


        </div>


    </div>


</body>

</html>