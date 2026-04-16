@include('errors.layout', [
    'code'        => '500',
    'gradient'    => 'from-red-400 to-rose-400',
    'title'       => 'Server Error',
    'description' => 'Something went wrong on our end. We\'re working on it — please try again in a moment.',
])
