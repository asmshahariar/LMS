@extends('frontend.layouts.master')

@section('content')
    <!--===========================
                BREADCRUMB START
            ============================-->
    <section class="wsus__breadcrumb" style="background: url({{ asset(config('settings.site_breadcrumb')) }});">
        <div class="wsus__breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__breadcrumb_text">
                            <h1>Student Dashboard</h1>
                            <ul>
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li>Student Dashboard</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--===========================
                BREADCRUMB END
            ============================-->


    <!--===========================
                DASHBOARD OVERVIEW START
            ============================-->
    <section class="wsus__dashboard mt_90 xs_mt_70 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                @include('frontend.student-dashboard.sidebar')
                <div class="col-xl-9 col-md-8">


                    <div class="row">
                        <div class="col-xl-4 col-sm-6 wow fadeInUp">
                            <div class="wsus__dash_earning">
                                <h6>Enrolled Courses</h6>
                                <h3>{{ $userCourses }}</h3>
                            </div>
                        </div>
                        <div class="col-xl-4 col-sm-6 wow fadeInUp">
                            <div class="wsus__dash_earning">
                                <h6>Total Reviews</h6>
                                <h3>{{ $reviewCount }}</h3>
                            </div>
                        </div>
                        <div class="col-xl-4 col-sm-6 wow fadeInUp">
                            <div class="wsus__dash_earning">
                                <h6>Total Orders</h6>
                                <h3>{{ $orderCount }}</h3>
                            </div>
                        </div>
                    </div>

                    
                    <div class="card mt-4">
                        <table class="table">
                            <thead>

                                <th>No.</th>
                                <th>Invoice</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>

                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $order->invoice_id }}</td>
                                        <td>{{ $order->total_amount }} {{ $order->currency }}</td>
                                        <td><span class="badge bg-success text-green-fg">{{ $order->status }}</span></td>
                                        <td><a href="">view</a></td>

                                    </tr>
                                @empty

                                    <tr>
                                        <td>No Data Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                 
                </div>
            </div>
        </div>
    </section>
    <!--===========================
                DASHBOARD OVERVIEW END
            ============================-->
@endsection
