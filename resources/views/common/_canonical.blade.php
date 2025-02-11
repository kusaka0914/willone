@if(empty($noindex))
<link rel="canonical" href="{{ getCanonicalUrl(\Route::currentRouteName(), url()->current()) }}">
@endif
