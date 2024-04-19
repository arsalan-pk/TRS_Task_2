<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>product Details</title>

    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <link rel="stylesheet" href="{{ asset('vendor/jquery-toast-plugin/jquery.toast.min.css') }}"> --}}
    @vite(['resources/scss/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card mt-3">
                    <div class="card-body">
                        <h2>{{ $product->name }}</h2>
                        <p>{{ $product->description }}</p>
                        <p>Price: ${{ $product->price }}</p>
                        @if ($hasImage)
                            <img src="{{ asset($product->images->first()->url) }}" alt="Product Image"
                                class="img-fluid">
                        @else
                            <img src="{{ asset('default.png') }}" class="card-img-top" alt="Product Image">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mt-3">
                    <div class="card-body">
                        <h3>Comments</h3>
                        <hr>
                        <h4>Add a Comment</h4>
                        <form method="POST" action="{{ route('comments.store') }}" id="storeComment">
                            @csrf
                            <input type="hidden" id="product_id" name="product_id" value="{{ $product->id }}">
                            <div class="form-group">
                                <label for="comment">Comment:</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                                <span class="text-danger comment_error" id=""></span>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Comment</button>
                        </form>

                        <hr>

                        <h4>Add a Review</h4>
                        <form method="POST" action="{{ route('reviews.store') }}" id="storeReview">
                            @csrf
                            <input type="hidden" id="product_id" name="product_id" value="{{ $product->id }}">
                            <div class="form-group">
                                <label for="rating">Rating:</label>
                                <select class="form-control" id="rating" name="rating">
                                    <option value="1">1 Star</option>
                                    <option value="2">2 Stars</option>
                                    <option value="3">3 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="5">5 Stars</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="review">Review:</label>
                                <textarea class="form-control" id="review" name="review" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mt-3">
                    <div class="card-body">
                        <div id="comment-container">
                            <h3>Comments</h3>
                            <span>
                                @if ($product->comments->isNotEmpty())
                                    @foreach ($product->comments as $comment)
                                        <div>

                                            <img src="{{ asset('default-user.jpg') }}" alt="User Image"
                                                style="width: 50px; height: 50px; border-radius: 50%;">

                                            <strong>{{ $comment->user->name }}</strong>
                                        </div>
                                        <p>{{ $comment->comment }}</p>
                                    @endforeach
                                @else
                                    <p>No comments yet. Be the first to comment on this product!</p>
                                @endif
                            </span>
                        </div>
                        <hr>
                        <div id="review-container">
                            <h3>Reviews</h3>
                            <samp>
                                @if ($product->reviews->isNotEmpty())
                                    @foreach ($product->reviews as $review)
                                        <div>
                                            <img src="{{ asset('default-user.jpg') }}" alt="User Image"
                                                style="width: 50px; height: 50px; border-radius: 50%;">
                                            <strong>{{ $review->user->name }}</strong>
                                            <br>
                                            <span>Rating: {{ $review->rating }}</span>
                                        </div>
                                        <p>{{ $review->review }}</p>
                                    @endforeach
                                @else
                                    <p>No reviews yet. Be the first to review this product!</p>
                                @endif
                            </samp>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> --}}
    {{-- <script src="{{ asset('vendor/jquery-toast-plugin/jquery.toast.min.js') }}"></script> --}}
    <script type="module">
        $(function() {
            var baseUrl = "{{ url('/') }}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // store script for comment
            $('#storeComment').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    type: 'post',
                    data: new FormData($('#storeComment')[0]),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforSend: function() {

                    },
                    complete: function() {},
                    success: function(data) {
                        if (data.status == 0) {
                            $.each(data.error, function(prefix, val) {
                                $('span.' + prefix + '_error').text(val[0]);
                            });
                        } else {

                            $("#comment-container").append("<span><div><img src='" + baseUrl +
                                "/default-user.jpg' style='width: 50px; height: 50px; border-radius: 50%;'><strong>" +
                                data.username + "</strong></div><p>" + $('#comment').val() +
                                "</p></span>");

                            $('#storeComment').trigger("reset");

                            Toastify({
                                text: "Comment is Added successfully",
                                duration: 3000,
                                destination: "",
                                newWindow: true,
                                close: true,
                                gravity: "top",
                                position: "right",
                                stopOnFocus: true,
                                style: {
                                    background: "green",
                                },
                            }).showToast();
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    },
                });

            });

            // store script for review
            $('#storeReview').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    type: 'post',
                    data: new FormData($('#storeReview')[0]),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforSend: function() {

                    },
                    complete: function() {},
                    success: function(data) {
                        if (data.status == 0) {
                            $.each(data.error, function(prefix, val) {
                                $('span.' + prefix + '_error').text(val[0]);
                            });
                        } else {

                            $("#review-container").append("<span><div><img src='" + baseUrl +
                                "/default-user.jpg' style='width: 50px; height: 50px; border-radius: 50%;'><strong>" +
                                data.username + "</strong><br><span>Rating:" + $('#rating')
                                .val() + "</span></div><p>" + $('#review').val() +
                                "</p></span>");

                            $('#storeReview').trigger("reset");

                            Toastify({
                                text: "Review is Added successfully",
                                duration: 3000,
                                destination: "",
                                newWindow: true,
                                close: true,
                                gravity: "top",
                                position: "right",
                                stopOnFocus: true,
                                style: {
                                    background: "green",
                                },
                            }).showToast();

                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    },
                });

            });

        });
    </script>
</body>

</html>
