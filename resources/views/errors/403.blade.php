@php
//'message'=>$exception->getMessage(), 'data' => $exception->getHeaders()]

$data = $exception->getHeaders();
$message = $exception->getMessage();

@endphp
<h1>Error {{ $exception->getStatusCode()  }}</h1>
{{ $message }}

<a href = "{{$data['returnUrl']}}">{!!trans('messages.label_back_to_page')!!}</a>