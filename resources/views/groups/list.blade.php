<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Group List
                </h2>
            </x-slot>
            
            <div>
                @foreach ($groups as $group)
                    <div>
                      <a href="/moviechat/group/{{ $group->id }}">・{{ $group->name }}</a>  
                    </div>
                @endforeach
                
            </div>
        </x-app-layout>
    </body>
</html>