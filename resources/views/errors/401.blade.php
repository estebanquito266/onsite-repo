@extends('errors.minimal')

@section('title', __('No autorizado'))
@section('code', '401')
@section('message', __($exception->getMessage() ?: 'No autorizado'))