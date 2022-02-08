@extends('statamic::layout')
@section('title', __('business-hours::settings.name'))

@section('content')
    <publish-form
        title="@lang('business-hours::settings.name')"
        action="{{ cp_route('luckymedia.businesshours.update') }}"
        method="put"
        :blueprint='@json($blueprint)'
        :meta='@json($meta)'
        :values='@json($values)'
    ></publish-form>
@endsection
