@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <h2 class="text-blue-500 uppercase tracking-wide font-semibold">Popular Games</h2>
        <!--Popular Games-->
        <livewire:popular-games />
        <!--End Popular Games-->

        <div class="flex flex-col lg:flex-row my-10">
            <!--Recently Reviewed-->
            <livewire:recently-reviewed />
            <!--End Recently Reviewed-->

            <div class="most-anticipated lg:w-1/4 mt-12 lg:mt-0 ">

                <!--Most Anticipated-->
                <h2 class="text-blue-500 uppercase tracking-wide font-semibold">Most Anticipated</h2>
               <livewire:most-anticipated />
                <!--End Most Anticipated-->

                <!--Coming Soon-->
                <h2 class="text-blue-500 uppercase tracking-wide font-semibold">Coming Soon</h2>
               <livewire:coming-soon />
                <!--End Coming Soon-->

            </div>
        </div>
    </div>
@endsection
