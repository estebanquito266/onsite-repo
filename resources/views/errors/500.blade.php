@extends('errors.minimal')

@section('title', __('Error del Servidor'))
@section('code', '500')
@section('message', __($exception->getMessage() ?: 'Error del Servidor'))
